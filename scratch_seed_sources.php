<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Content\Models\BudgetSource;
use App\Domain\Content\Models\WorkProgram;

$sources = [
    ['name' => 'APBD Kabupaten Bandung', 'code' => 'APBD', 'order_index' => 1],
    ['name' => 'APBDes (Dana Desa / Alokasi Dana Desa)', 'code' => 'APBDES', 'order_index' => 2],
    ['name' => 'Swadaya & Iuran Anggota', 'code' => 'SWADAYA', 'order_index' => 3],
    ['name' => 'BAZNAS / Bantuan Keagamaan', 'code' => 'BAZNAS', 'order_index' => 4],
    ['name' => 'CSR / Kemitraan Dunia Usaha', 'code' => 'CSR', 'order_index' => 5],
    ['name' => 'Sponsor & Donatur Tidak Mengikat', 'code' => 'SPONSOR', 'order_index' => 6],
];

foreach ($sources as $s) {
    BudgetSource::firstOrCreate(['name' => $s['name']], $s);
}

$apbd = BudgetSource::where('code', 'APBD')->first();
$swadaya = BudgetSource::where('code', 'SWADAYA')->first();
$csr = BudgetSource::where('code', 'CSR')->first();

WorkProgram::whereNull('budget_source_id')->each(function ($wp) use ($apbd, $swadaya, $csr) {
    $src = strtolower((string) $wp->budget_source);
    if (str_contains($src, 'csr')) {
        $wp->update(['budget_source_id' => $csr?->id]);
    } elseif (str_contains($src, 'apbd') || str_contains($src, 'dinas')) {
        $wp->update(['budget_source_id' => $apbd?->id]);
    } else {
        $wp->update(['budget_source_id' => $swadaya?->id]);
    }
});

echo "Total Master Sumber Anggaran: " . BudgetSource::count() . "\n";
echo "Total Program Kerja Berelasi: " . WorkProgram::whereNotNull('budget_source_id')->count() . "\n";
