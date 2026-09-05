<?php

namespace App\Services;

use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Domain\Units\Models\UnitMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvImportExportService
{
    /**
     * Helper untuk membuat StreamedResponse CSV
     */
    public static function exportCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        $headersResponse = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // Write BOM for UTF-8 compatibility in Excel
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $headersResponse);
    }

    /**
     * Parse CSV uploaded file path
     */
    public static function parseCsvFile(string $filePath): array
    {
        $fullPath = Storage::disk('public')->path($filePath);
        if (! file_exists($fullPath)) {
            $fullPath = storage_path('app/'.$filePath);
        }

        if (! file_exists($fullPath)) {
            return [];
        }

        $rows = [];
        if (($handle = fopen($fullPath, 'r')) !== false) {
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $headers = fgetcsv($handle);
            if (! $headers) {
                fclose($handle);

                return [];
            }

            // Normalisasi header (lowercase, trim)
            $headers = array_map(fn ($h) => strtolower(trim((string) $h)), $headers);

            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) === count($headers)) {
                    $rows[] = array_combine($headers, array_map('trim', $data));
                }
            }
            fclose($handle);
        }

        return $rows;
    }

    /**
     * Import Master Kecamatan
     */
    public static function importDistricts(string $filePath): int
    {
        $rows = static::parseCsvFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $name = $row['name'] ?? $row['nama_kecamatan'] ?? null;
                if (! $name) {
                    continue;
                }

                $code = $row['kemendagri_code'] ?? $row['kode_kemendagri'] ?? null;
                $slug = Str::slug($name);

                RefDistrict::updateOrCreate(
                    ['name' => $name],
                    [
                        'kemendagri_code' => $code,
                        'slug' => $slug,
                        'latitude_center' => ! empty($row['latitude']) ? (float) $row['latitude'] : null,
                        'longitude_center' => ! empty($row['longitude']) ? (float) $row['longitude'] : null,
                    ]
                );
                $count++;
            }
        });

        return $count;
    }

    /**
     * Import Master Desa / Kelurahan
     */
    public static function importVillages(string $filePath): int
    {
        $rows = static::parseCsvFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $name = $row['name'] ?? $row['nama_desa'] ?? null;
                $districtName = $row['district_name'] ?? $row['nama_kecamatan'] ?? null;
                $districtId = $row['district_id'] ?? null;

                if (! $name) {
                    continue;
                }

                // Cari district jika diberikan nama
                if (! $districtId && $districtName) {
                    $districtId = RefDistrict::where('name', 'like', "%{$districtName}%")->value('id');
                }

                if (! $districtId) {
                    continue;
                }

                $code = $row['kemendagri_code'] ?? $row['kode_kemendagri'] ?? null;
                $type = $row['type'] ?? $row['tipe'] ?? 'Desa';
                $slug = Str::slug($name);

                RefVillage::updateOrCreate(
                    [
                        'district_id' => $districtId,
                        'name' => $name,
                    ],
                    [
                        'kemendagri_code' => $code,
                        'slug' => $slug,
                        'type' => in_array(strtolower($type), ['kelurahan', 'kel']) ? 'Kelurahan' : 'Desa',
                        'postal_code' => $row['postal_code'] ?? $row['kode_pos'] ?? null,
                        'total_rw' => (int) ($row['total_rw'] ?? $row['rw'] ?? 0),
                        'total_rt' => (int) ($row['total_rt'] ?? $row['rt'] ?? 0),
                        'latitude_center' => ! empty($row['latitude']) ? (float) $row['latitude'] : null,
                        'longitude_center' => ! empty($row['longitude']) ? (float) $row['longitude'] : null,
                    ]
                );
                $count++;
            }
        });

        return $count;
    }

    /**
     * Import Master Unit Karang Taruna
     */
    public static function importUnits(string $filePath): int
    {
        $rows = static::parseCsvFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $unitName = $row['unit_name'] ?? $row['nama_unit'] ?? null;
                $level = strtolower($row['unit_level'] ?? $row['level'] ?? 'desa');
                $code = $row['unit_code'] ?? $row['kode_unit'] ?? null;

                if (! $unitName) {
                    continue;
                }

                if (! in_array($level, ['kabupaten', 'kecamatan', 'desa', 'rw'])) {
                    $level = 'desa';
                }

                $districtId = $row['district_id'] ?? null;
                $districtName = $row['district_name'] ?? $row['nama_kecamatan'] ?? null;
                if (! $districtId && $districtName) {
                    $districtId = RefDistrict::where('name', 'like', "%{$districtName}%")->value('id');
                }

                $villageId = $row['village_id'] ?? null;
                $villageName = $row['village_name'] ?? $row['nama_desa'] ?? null;
                if (! $villageId && $villageName && $districtId) {
                    $villageId = RefVillage::where('district_id', $districtId)->where('name', 'like', "%{$villageName}%")->value('id');
                }

                if (! $code) {
                    $code = 'KT-'.strtoupper(substr($level, 0, 3)).'-'.rand(1000, 9999);
                }

                $slug = $row['slug'] ?? null;
                if (! $slug) {
                    if ($level === 'kecamatan' && $districtId) {
                        $dist = RefDistrict::find($districtId);
                        $slug = $dist ? $dist->slug : Str::slug($unitName);
                    } elseif ($level === 'desa' && $districtId && $villageId) {
                        $dist = RefDistrict::find($districtId);
                        $vill = RefVillage::find($villageId);
                        $slug = ($dist ? $dist->slug : 'kec').'-'.($vill ? $vill->slug : Str::slug($unitName));
                    } else {
                        $slug = Str::slug($unitName);
                    }
                }

                KarangTarunaUnit::updateOrCreate(
                    ['unit_code' => $code],
                    [
                        'unit_level' => $level,
                        'district_id' => $districtId,
                        'village_id' => $villageId,
                        'rw_number' => $row['rw_number'] ?? null,
                        'unit_name' => $unitName,
                        'slug' => $slug,
                        'chairman_name' => $row['chairman_name'] ?? $row['ketua'] ?? 'Ketua Unit',
                        'secretary_name' => $row['secretary_name'] ?? $row['sekretaris'] ?? null,
                        'treasurer_name' => $row['treasurer_name'] ?? $row['bendahara'] ?? null,
                        'contact_phone' => $row['contact_phone'] ?? $row['telepon'] ?? null,
                        'contact_email' => $row['contact_email'] ?? $row['email'] ?? null,
                        'office_address' => $row['office_address'] ?? $row['alamat'] ?? null,
                        'latitude' => ! empty($row['latitude']) ? (float) $row['latitude'] : null,
                        'longitude' => ! empty($row['longitude']) ? (float) $row['longitude'] : null,
                        'sk_number' => $row['sk_number'] ?? null,
                        'period_start_year' => (int) ($row['period_start_year'] ?? $row['tahun_mulai'] ?? date('Y')),
                        'period_end_year' => (int) ($row['period_end_year'] ?? $row['tahun_selesai'] ?? (date('Y') + 5)),
                        'total_members' => (int) ($row['total_members'] ?? $row['jumlah_anggota'] ?? 0),
                        'status_aktif' => in_array($row['status_aktif'] ?? '', ['Aktif', 'Demisioner', 'Restrukturisasi', 'PJS', 'Nonaktif']) ? $row['status_aktif'] : 'Aktif',
                        'is_verified' => true,
                    ]
                );
                $count++;
            }
        });

        return $count;
    }

    /**
     * Import Pengurus Unit
     */
    public static function importUnitMembers(string $filePath): int
    {
        $rows = static::parseCsvFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $fullName = $row['full_name'] ?? $row['nama_lengkap'] ?? null;
                $role = $row['position_role'] ?? $row['jabatan'] ?? null;
                $unitCode = $row['unit_code'] ?? $row['kode_unit'] ?? null;
                $unitId = $row['unit_id'] ?? null;

                if (! $fullName || ! $role) {
                    continue;
                }

                if (! $unitId && $unitCode) {
                    $unitId = KarangTarunaUnit::where('unit_code', $unitCode)->value('id');
                }

                if (! $unitId) {
                    continue;
                }

                UnitMember::updateOrCreate(
                    [
                        'unit_id' => $unitId,
                        'full_name' => $fullName,
                    ],
                    [
                        'position_role' => $role,
                        'division_section' => $row['division_section'] ?? $row['bidang'] ?? null,
                        'phone' => $row['phone'] ?? $row['telepon'] ?? null,
                        'email' => $row['email'] ?? null,
                        'order_index' => (int) ($row['order_index'] ?? 0),
                        'is_active' => true,
                    ]
                );
                $count++;
            }
        });

        return $count;
    }
}
