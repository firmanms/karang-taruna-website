<?php

namespace App\Services;

use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Domain\Units\Models\UnitMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelImportExportService
{
    /**
     * Download Template XLSX dengan Header dan Contoh Baris Data
     */
    public static function downloadTemplateXlsx(string $filename, array $headers, array $sampleRows = []): StreamedResponse
    {
        $headersResponse = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($headers, $sampleRows) {
            $writer = new XlsxWriter;
            $writer->openToFile('php://output');

            // Header baris
            $writer->addRow(Row::fromValues($headers));

            // Contoh baris
            foreach ($sampleRows as $row) {
                $writer->addRow(Row::fromValues($row));
            }

            $writer->close();
        }, 200, $headersResponse);
    }

    /**
     * Parse File (Mendukung .xlsx dan .csv)
     */
    public static function parseUploadedFile(string $filePath): array
    {
        $fullPath = Storage::disk('public')->path($filePath);
        if (! file_exists($fullPath)) {
            $fullPath = storage_path('app/'.$filePath);
        }

        if (! file_exists($fullPath)) {
            return [];
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        if ($extension === 'xlsx') {
            return static::parseXlsx($fullPath);
        }

        return static::parseCsv($fullPath);
    }

    /**
     * Parse file XLSX menggunakan OpenSpout
     */
    protected static function parseXlsx(string $fullPath): array
    {
        $reader = new XlsxReader;
        $reader->open($fullPath);

        $headers = [];
        $rows = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $cells = $row->toArray();

                if ($rowIndex === 1) {
                    // Normalisasi nama kolom header
                    $headers = array_map(function ($h) {
                        return strtolower(trim((string) $h));
                    }, $cells);

                    continue;
                }

                // Abaikan jika seluruh kolom baris kosong
                if (empty(array_filter($cells, fn ($c) => $c !== null && $c !== ''))) {
                    continue;
                }

                $rowData = [];
                foreach ($headers as $idx => $headerName) {
                    if (empty($headerName)) {
                        continue;
                    }
                    $val = $cells[$idx] ?? null;
                    $rowData[$headerName] = is_string($val) ? trim($val) : $val;
                }

                $rows[] = $rowData;
            }
            break; // Hanya baca sheet pertama
        }

        $reader->close();

        return $rows;
    }

    /**
     * Parse file CSV fallback
     */
    protected static function parseCsv(string $fullPath): array
    {
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
     * Import / Update Master Kecamatan
     * Hanya memperbarui data yang diubah / menambah jika belum ada (tanpa duplikasi).
     */
    public static function importDistricts(string $filePath): int
    {
        $rows = static::parseUploadedFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $name = $row['name'] ?? $row['nama_kecamatan'] ?? null;
                if (! $name) {
                    continue;
                }

                $code = $row['kemendagri_code'] ?? $row['kode_kemendagri'] ?? null;
                if ($code !== null) {
                    $code = str_replace('.', '', trim((string) $code));
                }
                $slug = Str::slug($name);

                // Update jika sudah ada (berdasarkan nama atau kode), atau buat baru
                $district = null;
                if ($code) {
                    $district = RefDistrict::whereRaw("REPLACE(kemendagri_code, '.', '') = ?", [$code])->first();
                }
                if (! $district) {
                    $district = RefDistrict::where('name', $name)->first();
                }

                $payload = [
                    'name' => $name,
                    'slug' => $slug,
                ];

                if ($code) {
                    $payload['kemendagri_code'] = $code;
                }
                if (isset($row['latitude_center']) || isset($row['latitude'])) {
                    $val = $row['latitude_center'] ?? $row['latitude'];
                    $payload['latitude_center'] = $val !== '' && $val !== null ? (float) $val : null;
                }
                if (isset($row['longitude_center']) || isset($row['longitude'])) {
                    $val = $row['longitude_center'] ?? $row['longitude'];
                    $payload['longitude_center'] = $val !== '' && $val !== null ? (float) $val : null;
                }

                if ($district) {
                    $district->update($payload);
                } else {
                    RefDistrict::create($payload);
                }

                $count++;
            }
        });

        return $count;
    }

    /**
     * Import / Update Master Desa / Kelurahan
     * Hanya memperbarui data yang diubah / menambah jika belum ada (tanpa duplikasi).
     */
    public static function importVillages(string $filePath): int
    {
        $rows = static::parseUploadedFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $name = $row['name'] ?? $row['nama_desa'] ?? null;
                $districtName = $row['district_name'] ?? $row['nama_kecamatan'] ?? null;
                $districtId = $row['district_id'] ?? null;

                if (! $name) {
                    continue;
                }

                if (! $districtId && $districtName) {
                    $districtId = RefDistrict::where('name', 'like', "%{$districtName}%")->value('id');
                }

                if (! $districtId) {
                    continue;
                }

                $code = $row['kemendagri_code'] ?? $row['kode_kemendagri'] ?? null;
                if ($code !== null) {
                    $code = str_replace('.', '', trim((string) $code));
                }
                $type = $row['type'] ?? $row['tipe'] ?? 'Desa';
                $slug = Str::slug($name);

                // Cari desa yang ada di kecamatan ini
                $village = null;
                if ($code) {
                    $village = RefVillage::whereRaw("REPLACE(kemendagri_code, '.', '') = ?", [$code])->first();
                }
                if (! $village) {
                    $village = RefVillage::where('district_id', $districtId)->where('name', $name)->first();
                }

                $payload = [
                    'district_id' => $districtId,
                    'name' => $name,
                    'slug' => $slug,
                    'type' => in_array(strtolower((string) $type), ['kelurahan', 'kel']) ? 'Kelurahan' : 'Desa',
                ];

                if ($code) {
                    $payload['kemendagri_code'] = $code;
                }
                if (isset($row['postal_code']) || isset($row['kode_pos'])) {
                    $payload['postal_code'] = (string) ($row['postal_code'] ?? $row['kode_pos']);
                }
                if (isset($row['total_rw']) || isset($row['rw'])) {
                    $payload['total_rw'] = (int) ($row['total_rw'] ?? $row['rw']);
                }
                if (isset($row['total_rt']) || isset($row['rt'])) {
                    $payload['total_rt'] = (int) ($row['total_rt'] ?? $row['rt']);
                }
                if (isset($row['latitude_center']) || isset($row['latitude'])) {
                    $val = $row['latitude_center'] ?? $row['latitude'];
                    $payload['latitude_center'] = $val !== '' && $val !== null ? (float) $val : null;
                }
                if (isset($row['longitude_center']) || isset($row['longitude'])) {
                    $val = $row['longitude_center'] ?? $row['longitude'];
                    $payload['longitude_center'] = $val !== '' && $val !== null ? (float) $val : null;
                }

                if ($village) {
                    $village->update($payload);
                } else {
                    RefVillage::create($payload);
                }

                $count++;
            }
        });

        return $count;
    }

    /**
     * Import / Update Master Unit Karang Taruna
     * Hanya memperbarui data yang diubah / menambah jika belum ada (tanpa duplikasi).
     */
    public static function importUnits(string $filePath): int
    {
        $rows = static::parseUploadedFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $unitName = $row['unit_name'] ?? $row['nama_unit'] ?? null;
                $level = strtolower((string) ($row['unit_level'] ?? $row['level'] ?? 'desa'));
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

                // Cari unit yang sudah ada berdasarkan kode unit, atau perpaduan level + wilayah + nama
                $unit = null;
                if ($code) {
                    $unit = KarangTarunaUnit::where('unit_code', $code)->first();
                }
                if (! $unit) {
                    if ($level === 'kabupaten') {
                        $unit = KarangTarunaUnit::where('unit_level', 'kabupaten')->first();
                    } elseif ($level === 'kecamatan' && $districtId) {
                        $unit = KarangTarunaUnit::where('unit_level', 'kecamatan')->where('district_id', $districtId)->first();
                    } elseif ($level === 'desa' && $villageId) {
                        $unit = KarangTarunaUnit::where('unit_level', 'desa')->where('village_id', $villageId)->first();
                    }
                }

                if (! $code) {
                    $code = $unit ? $unit->unit_code : ('KT-'.strtoupper(substr($level, 0, 3)).'-'.rand(1000, 9999));
                }

                $slug = $row['slug'] ?? ($unit ? $unit->slug : null);
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

                $payload = [
                    'unit_level' => $level,
                    'district_id' => $districtId,
                    'village_id' => $villageId,
                    'unit_name' => $unitName,
                    'unit_code' => $code,
                    'slug' => $slug,
                    'is_verified' => true,
                ];

                if (isset($row['chairman_name']) || isset($row['ketua'])) {
                    $payload['chairman_name'] = $row['chairman_name'] ?? $row['ketua'];
                }
                if (isset($row['secretary_name']) || isset($row['sekretaris'])) {
                    $payload['secretary_name'] = $row['secretary_name'] ?? $row['sekretaris'];
                }
                if (isset($row['treasurer_name']) || isset($row['bendahara'])) {
                    $payload['treasurer_name'] = $row['treasurer_name'] ?? $row['bendahara'];
                }
                if (isset($row['contact_phone']) || isset($row['telepon'])) {
                    $payload['contact_phone'] = (string) ($row['contact_phone'] ?? $row['telepon']);
                }
                if (isset($row['contact_email']) || isset($row['email'])) {
                    $payload['contact_email'] = $row['contact_email'] ?? $row['email'];
                }
                if (isset($row['office_address']) || isset($row['alamat'])) {
                    $payload['office_address'] = $row['office_address'] ?? $row['alamat'];
                }
                if (isset($row['period_start_year']) || isset($row['tahun_mulai'])) {
                    $payload['period_start_year'] = (int) ($row['period_start_year'] ?? $row['tahun_mulai']);
                }
                if (isset($row['period_end_year']) || isset($row['tahun_selesai'])) {
                    $payload['period_end_year'] = (int) ($row['period_end_year'] ?? $row['tahun_selesai']);
                }
                if (isset($row['total_members']) || isset($row['jumlah_anggota'])) {
                    $payload['total_members'] = (int) ($row['total_members'] ?? $row['jumlah_anggota']);
                }
                if (isset($row['status_aktif'])) {
                    $status = $row['status_aktif'];
                    $payload['status_aktif'] = in_array($status, ['Aktif', 'Demisioner', 'Restrukturisasi', 'PJS', 'Nonaktif']) ? $status : 'Aktif';
                }
                if (isset($row['latitude'])) {
                    $val = $row['latitude'];
                    $payload['latitude'] = $val !== '' && $val !== null ? (float) $val : null;
                }
                if (isset($row['longitude'])) {
                    $val = $row['longitude'];
                    $payload['longitude'] = $val !== '' && $val !== null ? (float) $val : null;
                }

                if ($unit) {
                    $unit->update($payload);
                } else {
                    if (empty($payload['chairman_name'])) {
                        $payload['chairman_name'] = 'Ketua Unit';
                    }
                    if (empty($payload['period_start_year'])) {
                        $payload['period_start_year'] = (int) date('Y');
                    }
                    if (empty($payload['period_end_year'])) {
                        $payload['period_end_year'] = (int) date('Y') + 5;
                    }
                    KarangTarunaUnit::create($payload);
                }

                $count++;
            }
        });

        return $count;
    }

    /**
     * Import / Update Pengurus Unit
     * Hanya memperbarui data yang diubah / menambah jika belum ada (tanpa duplikasi).
     */
    public static function importUnitMembers(string $filePath): int
    {
        $rows = static::parseUploadedFile($filePath);
        $count = 0;

        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $fullName = $row['full_name'] ?? $row['nama_lengkap'] ?? null;
                $role = $row['position_role'] ?? $row['jabatan'] ?? null;
                $unitCode = $row['unit_code'] ?? $row['kode_unit'] ?? null;
                $unitName = $row['unit_name'] ?? $row['nama_unit'] ?? null;
                $unitId = $row['unit_id'] ?? null;

                if (! $fullName || ! $role) {
                    continue;
                }

                if (! $unitId && $unitCode) {
                    $unitId = KarangTarunaUnit::where('unit_code', $unitCode)->value('id');
                }
                if (! $unitId && $unitName) {
                    $unitId = KarangTarunaUnit::where('unit_name', 'like', "%{$unitName}%")->value('id');
                }

                if (! $unitId) {
                    continue;
                }

                $member = UnitMember::where('unit_id', $unitId)
                    ->where('full_name', $fullName)
                    ->first();

                $payload = [
                    'unit_id' => $unitId,
                    'full_name' => $fullName,
                    'position_role' => $role,
                    'is_active' => true,
                ];

                if (isset($row['division_section']) || isset($row['bidang'])) {
                    $payload['division_section'] = $row['division_section'] ?? $row['bidang'];
                }
                if (isset($row['phone']) || isset($row['telepon'])) {
                    $payload['phone'] = (string) ($row['phone'] ?? $row['telepon']);
                }
                if (isset($row['email'])) {
                    $payload['email'] = $row['email'];
                }
                if (isset($row['order_index'])) {
                    $payload['order_index'] = (int) $row['order_index'];
                }

                if ($member) {
                    $member->update($payload);
                } else {
                    UnitMember::create($payload);
                }

                $count++;
            }
        });

        return $count;
    }
}
