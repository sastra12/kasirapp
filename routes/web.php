<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::view('/masterone', 'layouts.masterone');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Kategori
    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data')->middleware('CheckRole');
    Route::resource('/kategori', KategoriController::class)->middleware('CheckRole');

    // Produk
    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data')->middleware('CheckRole');
    Route::delete('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::resource('/produk', ProdukController::class)->middleware('CheckRole');

    // Member
    Route::get('/member/data', [MemberController::class, 'data'])->name('member.data')->middleware('CheckRole');
    Route::resource('/member', MemberController::class)->middleware('CheckRole');

    // Supplier
    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data')->middleware('CheckRole');
    Route::resource('/supplier', SupplierController::class)->middleware('CheckRole');

    // Pengeluaran
    Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data')->middleware('CheckRole');
    Route::resource('/pengeluaran', PengeluaranController::class)->middleware('CheckRole');

    // Pembelian
    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data')->middleware('CheckRole');
    Route::get('/pembelian/supplier', [PembelianController::class, 'getSupplier'])->name('pembelian.supplier');
    Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian', PembelianController::class)
        ->except('create')->middleware('CheckRole');

    // Pembelian Detail
    Route::get('/pembelian-detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian-detail.data')->middleware('CheckRole');
    Route::resource('/pembelian-detail', PembelianDetailController::class)
        ->except('create', 'show', 'edit')->middleware('CheckRole');

    // Penjualan
    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/cart', [PenjualanController::class, 'cart'])->name('cart');
    Route::get('/add-to-cart/{id}', [PenjualanController::class, 'addToCart'])->name('add.to.cart');
    Route::post('/cart/increment', [PenjualanController::class, 'incrementCart'])->name('cart.increment');
    Route::post('/cart/decrement', [PenjualanController::class, 'decrementCart'])->name('cart.decrement');
    Route::post('/cart/delete', [PenjualanController::class, 'deleteCart'])->name('cart.delete');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

    // Data Transaksi
    Route::get('/data-transaksi', [TransaksiController::class, 'data'])->name('data.transaksi');
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
    Route::delete('transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // User
    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::resource('/users', UserController::class)
        ->except('show', 'edit', 'update', 'create');
});
