<?php

use App\Http\Controllers\AdminStoryController;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/berita/{post}/story-instagram', [AdminStoryController::class, 'post'])->name('posts.story');
});

Route::get('/clear-cache-hosting', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('filament:clear-cached-components');
    return 'Cache cleared successfully!';
});

Route::get('/', [PortalController::class, 'home'])->name('home');
Route::get('/cari', [PortalController::class, 'search'])->name('search');
Route::get('/berita/{post:slug}', [PortalController::class, 'post'])->name('posts.show');
Route::post('/berita/{post:slug}/suka', [PortalController::class, 'like'])->middleware('throttle:5,1')->name('posts.like');
Route::post('/berita/{post:slug}/bagikan', [PortalController::class, 'share'])->middleware('throttle:5,1')->name('posts.share');
Route::get('/berita/{post:slug}/bagikan/whatsapp', [PortalController::class, 'shareToWhatsApp'])->middleware('throttle:5,1')->name('posts.share.whatsapp');
Route::get('/berita/{post:slug}/bagikan/facebook', [PortalController::class, 'shareToFacebook'])->middleware('throttle:5,1')->name('posts.share.facebook');
Route::get('/kategori/{category:slug}', [PortalController::class, 'category'])->name('categories.show');
Route::get('/unit/{unit:slug}', [PortalController::class, 'unit'])->name('units.show');
Route::get('/halaman/{page:slug}', [PortalController::class, 'page'])->name('pages.show');
Route::get('/sitemap.xml', [PortalController::class, 'sitemap'])->name('sitemap');
Route::get('/feed', [PortalController::class, 'feed'])->name('feed');
