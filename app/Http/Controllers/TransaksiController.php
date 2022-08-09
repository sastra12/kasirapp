<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    //

    public function index()
    {
        return view('data_transaksi.index');
    }

    public function data()
    {
        // level 
        $level = auth()->user()->level;
        if ($level != 0) {
            $data = DB::table('penjualan')
                ->leftJoin('users', 'users.id', '=', 'penjualan.id_user')
                ->select('penjualan.*', 'users.name')
                ->where('penjualan.id_user', auth()->user()->id)
                ->get();
        } else {
            $data = DB::table('penjualan')
                ->leftJoin('users', 'users.id', '=', 'penjualan.id_user')
                ->select('penjualan.*', 'users.name')
                ->get();
        }

        return Datatables::of($data)
            // for number
            ->addIndexColumn()
            // buat yang didalam kolom
            ->addColumn('created_at', function ($data) {
                return format_tanggal($data->created_at);
            })
            ->addColumn('total_item', function ($data) {
                return $data->total_item;
            })
            ->addColumn('total_harga', function ($data) {
                return format_uang($data->total_harga);
            })
            ->addColumn('diskon', function ($data) {
                return $data->diskon;
            })
            ->addColumn('total_bayar', function ($data) {
                return $data->bayar;
            })
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            // buat yang didalam kolom
            ->addColumn('action', function ($data) {
                return (auth()->user()->level == 0) ? '
                <button onclick="deleteData(`' . route('transaksi.destroy', $data->id_penjualan) . '`)" class="btn btn-xs btn-danger">Delete</button>
                <button onclick="detailData(`' . route('transaksi.show', $data->id_penjualan) . '`)" class="btn btn-xs btn-info">Detail</button>
            ' : '<button onclick="detailData(`' . route('transaksi.show', $data->id_penjualan) . '`)" class="btn btn-xs btn-info">Detail</button>';
            })
            // buat menampilkan
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $data = DB::table('penjualan_detail as pd')
            ->leftJoin('produk as p', 'p.id_produk', '=', 'pd.id_produk')
            ->where('pd.id_penjualan', '=', $id)
            ->select('p.nama_produk', 'pd.harga_jual', 'pd.jumlah', 'pd.diskon')
            ->get();
        return Datatables::of($data)
            // for number
            ->addIndexColumn()
            // buat yang didalam kolom
            ->addColumn('harga_jual', function ($data) {
                return format_uang($data->harga_jual);
            })
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $penjualan->delete();
        // id => 1
        $penjualan_detail = PenjualanDetail::where('id_penjualan', $id)->get();
        foreach ($penjualan_detail as $pd) {
            $pd->delete();
        }
    }
}
