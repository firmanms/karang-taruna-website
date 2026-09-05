<x-filament-panels::page>
    <div class="space-y-6">
        <div class="p-4 bg-orange-50 dark:bg-orange-950/40 border border-orange-200 dark:border-orange-800 rounded-xl text-orange-800 dark:text-orange-200 text-sm">
            <p class="font-semibold">Perhatian Pembuat Konten:</p>
            <p class="mt-1">Daftar di bawah ini adalah konten yang dikembalikan oleh Verifikator Kabupaten untuk disempurnakan. Silakan perbaiki isi data Anda sesuai catatan, kemudian tekan tombol <strong>Ajukan Ulang (Resubmit)</strong>.</p>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
