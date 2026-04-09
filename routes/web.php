<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PickupPointController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Boutique
Route::get('/boutique', [ShopController::class, 'index'])->name('shop.index');
Route::get('/boutique/{slug}', [ShopController::class, 'show'])->name('shop.show');

// Panier
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/panier/modifier/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/panier/supprimer/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/panier/vider', [CartController::class, 'clear'])->name('cart.clear');

// Commande
Route::get('/commander', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commander', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/commander/confirmation/{reference}', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
Route::get('/commander/creneaux', [CheckoutController::class, 'slotsForPoint'])->name('checkout.slots');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::get('/connexion', [LoginController::class, 'showLogin'])->name('login');
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Produits
        Route::resource('produits', AdminProductController::class)->parameters(['produits' => 'product']);

        // Catégories
        Route::resource('categories', CategoryController::class)->parameters(['categories' => 'category']);

        // Points de retrait
        Route::resource('pickup-points', PickupPointController::class)
            ->parameters(['pickup-points' => 'pickupPoint']);

        // Commandes
        Route::get('commandes', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('commandes/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('commandes/{order}/statut', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
