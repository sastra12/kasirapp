<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $produk = Produk::orderBy('nama_produk')->get();
        $supplier = Supplier::find(session('id_supplier'));
        if (!$supplier) {
            abort(404);
        }
        // return session('id_supplier');
        return view('pembelian_detail.index', compact('id_pembelian', 'produk', 'supplier'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function data()
    // {
    //     $produk = Produk::orderBy('nama_produk')->get();
    //     return datatables($produk)
    //         ->addIndexColumn()
    //         // ->addColumn('action', function ($supplier) {
    //         //     return '
    //         //     <a href="/pembelian/' . $supplier->id_supplier . '/create" class="btn btn-primary btn-xs">Pilih</a>
    //         //     ';
    //         // })
    //         // ->rawColumns(['action'])
    //         ->make(true);
    // }

    public function create()
    {
        //
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
