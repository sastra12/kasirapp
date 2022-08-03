<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Product;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pembelian.index');
    }

    public function getSupplier()
    {
        $supplier = Supplier::all();
        return datatables($supplier)
            ->addIndexColumn()
            ->addColumn('action', function ($supplier) {
                return '
                <a href="/pembelian/' . $supplier->id_supplier . '/create" class="btn btn-primary btn-xs">Pilih</a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function cart(Request $request)
    {
        $produk = Produk::find($request->id);
        // return response()->json($produk, 200);
        $keranjangPembelian = session('cartpembelian', []);
        // apakah $keranjang sudah diset
        if (isset($keranjangPembelian[$request->id])) {
            $keranjangPembelian[$request->id]['quantity']++;
        } else {
            $keranjangPembelian[$request->id] = [
                "name" => $produk->nama_produk,
                "kode_produk" => $produk->kode_produk,
                "quantity" => 1,
                "price" => $produk->harga_beli,
            ];
        }
        // Untuk menyimpan data dalam session
        session()->put('cartpembelian', $keranjangPembelian);
        // return session('cartpembelian');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // return $id;
        $pembelian = new Pembelian();
        $pembelian->id_supplier = $id;
        $pembelian->total_item = 0;
        $pembelian->total_harga = 0;
        $pembelian->diskon = 0;
        $pembelian->bayar = 0;
        $pembelian->save();

        session(['id_pembelian' => $pembelian->id_pembelian]);
        // id => 1
        session(['id_supplier' => $id]);

        return redirect()->route('pembelian-detail.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
