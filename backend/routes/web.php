<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TelaController;
use App\Http\Controllers\ComboController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/users', fn () => Inertia::render('Users/Index'))->middleware(['auth', 'verified'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->middleware(['auth', 'verified'])->name('users.create');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware(['auth', 'verified'])->name('users.edit');
Route::get('/categories', fn () => Inertia::render('Categories/Index'))->middleware(['auth', 'verified'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->middleware(['auth', 'verified'])->name('categories.create');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->middleware(['auth', 'verified'])->name('categories.edit');
Route::get('/products', fn () => Inertia::render('Products/Index'))->middleware(['auth', 'verified'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->middleware(['auth', 'verified'])->name('products.create');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware(['auth', 'verified'])->name('products.edit');
Route::get('/telas', fn () => Inertia::render('Telas/Index'))->middleware(['auth', 'verified'])->name('telas.index');
Route::get('/telas/create', [TelaController::class, 'create'])->middleware(['auth', 'verified'])->name('telas.create');
Route::get('/telas/{tela}/edit', [TelaController::class, 'edit'])->middleware(['auth', 'verified'])->name('telas.edit');

Route::get('/combos', [ComboController::class, 'index'])->middleware(['auth', 'verified'])->name('combos.index');
Route::get('/designs', fn () => Inertia::render('Designs/Index'))->middleware(['auth', 'verified'])->name('designs.index');
Route::get('/clients', fn () => Inertia::render('Clients/Index'))->middleware(['auth', 'verified'])->name('clients.index');
Route::get('/orders', fn () => Inertia::render('Orders/Index'))->middleware(['auth', 'verified'])->name('orders.index');
Route::get('/combos/create', [ComboController::class, 'create'])->middleware(['auth', 'verified'])->name('combos.create');
Route::post('/combos', [ComboController::class, 'store'])->middleware(['auth', 'verified'])->name('combos.store');
Route::get('/combos/{combo}/edit', [ComboController::class, 'edit'])->middleware(['auth', 'verified'])->name('combos.edit');
Route::put('/combos/{combo}', [ComboController::class, 'update'])->middleware(['auth', 'verified'])->name('combos.update');
Route::delete('/combos/{combo}', [ComboController::class, 'destroy'])->middleware(['auth', 'verified'])->name('combos.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
