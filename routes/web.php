<?php

use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'home'])->name('home');
Route::get('/cari', [PortalController::class, 'search'])->name('search');
Route::get('/berita/{post:slug}', [PortalController::class, 'post'])->name('posts.show');
Route::post('/berita/{post:slug}/suka', [PortalController::class, 'like'])->name('posts.like');
Route::post('/berita/{post:slug}/bagikan', [PortalController::class, 'share'])->name('posts.share');
Route::get('/kategori/{category:slug}', [PortalController::class, 'category'])->name('categories.show');
Route::get('/unit/{unit:slug}', [PortalController::class, 'unit'])->name('units.show');
Route::get('/halaman/{page:slug}', [PortalController::class, 'page'])->name('pages.show');
Route::get('/sitemap.xml', [PortalController::class, 'sitemap'])->name('sitemap');
