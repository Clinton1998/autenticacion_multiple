<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Web/Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
   /* $value = Auth::guard('administrator');
   dd(Auth::guard('customer')->user());*/

    return Inertia::render('Customer/Dashboard');
})->middleware(['auth:customer', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
     return Inertia::render('Administrator/Dashboard');
})->middleware(['auth:administrator', 'verified'])->name('admin.dashboard');

require __DIR__.'/customer-auth.php';
require __DIR__.'/administrator-auth.php';