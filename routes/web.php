<?php

use App\Http\Controllers\Public\PublicPortalController;
use Illuminate\Support\Facades\Route;

Route::name('public.')->group(function () {
    Route::get('/', [PublicPortalController::class, 'index'])->name('home');
    Route::get('/tentang-kami', [PublicPortalController::class, 'profile'])->name('profile');

    // Berita
    Route::get('/berita', [PublicPortalController::class, 'news'])->name('news');
    Route::get('/berita/{slug}', [PublicPortalController::class, 'newsDetail'])->name('news.detail');

    // Agenda & Program
    Route::get('/agenda', [PublicPortalController::class, 'events'])->name('events');
    Route::get('/program-kerja', [PublicPortalController::class, 'programs'])->name('programs');
    Route::get('/pengumuman', [PublicPortalController::class, 'announcements'])->name('announcements');
    Route::get('/prestasi', [PublicPortalController::class, 'achievements'])->name('achievements');

    // Media & Unduhan
    Route::get('/galeri-foto', [PublicPortalController::class, 'photoGallery'])->name('photos');
    Route::get('/galeri-video', [PublicPortalController::class, 'videoGallery'])->name('videos');
    Route::get('/unduhan', [PublicPortalController::class, 'downloads'])->name('downloads');
    Route::get('/unduhan/{id}/download', [PublicPortalController::class, 'downloadFile'])->name('downloads.file');

    // Direktori & Peta
    Route::get('/direktori', [PublicPortalController::class, 'directory'])->name('directory');
    Route::get('/peta-sebaran', [PublicPortalController::class, 'territoryMap'])->name('map');

    // Cek PPKS
    Route::match(['get', 'post'], '/cek-ppks', [PublicPortalController::class, 'checkPpks'])->name('ppks');
});
