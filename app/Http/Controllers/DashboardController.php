<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $tanggalSekarang = date('Y-m-d');
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d', strtotime("+1 day", strtotime($tanggalSekarang)));
        $level = auth()->user()->level;
        if ($level != 0) {
            $dataPenjualan = Penjualan::selectRaw('SUM(total_item) total_item_penjualan')
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->where('id_penjualan', auth()->user()->id)->get();
        } else {
            $dataPenjualan = Penjualan::selectRaw('SUM(total_item) total_item_penjualan')->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get();
        }

        $dataPembelian = Pembelian::selectRaw('SUM(total_item) total_item_pembelian')->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get();
        $produk = Produk::all();
        $jumlahProduk = $produk->count();
        $kategori = Kategori::all();
        $jumlahKategori = $kategori->count();

        return view('dashboard.index', compact('dataPenjualan', 'dataPembelian', 'jumlahProduk', 'jumlahKategori'));
    }
}
