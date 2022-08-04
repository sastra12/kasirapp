<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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

    public function data()
    {
        $pembelian = DB::table('pembelian as p')
            ->leftJoin('supplier as s', 'p.id_supplier', '=', 's.id_supplier')
            ->select('s.nama', 'p.*')
            ->get();
        return datatables($pembelian)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($pembelian) {
                return format_tanggal($pembelian->created_at);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return format_uang($pembelian->total_harga);
            })
            ->addColumn('bayar', function ($pembelian) {
                return format_uang($pembelian->bayar);
            })
            ->addColumn('action', function ($pembelian) {
                return '
                <button onclick="deleteData(`' . route('pembelian.destroy', $pembelian->id_pembelian) . '`)" class="btn btn-xs btn-danger">Delete</button>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
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
        $pembelian = Pembelian::find($id);
        $pembelian->delete();

        $pembelian_detail = PembelianDetail::where('id_pembelian', $id)->get();
        foreach ($pembelian_detail as $pd) {
            $pd->delete();
        }
    }
}
