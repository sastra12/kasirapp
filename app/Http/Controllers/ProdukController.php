<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Produk;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');
        // foreach ($kategori as $key => $name) {
        //     echo $key . '-' . $name;
        // }
        return view('produk.index', compact('kategori'));
    }

    public function data(Request $request)
    {
        $listdata = Produk::leftJoin('kategori', 'kategori.id_kategori', '=', 'produk.id_kategori')
            ->select('produk.*', 'kategori.nama_kategori')
            ->orderBy('id_produk', 'desc')
            ->get();
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            // buat yang didalam kolom
            ->addColumn('kode_produk', function ($listdata) {
                return '<span class="badge badge-success">' . $listdata->kode_produk . '</span>';
            })
            ->addColumn('harga_beli', function ($listdata) {
                return format_uang($listdata->harga_beli);
            })
            ->addColumn('harga_jual', function ($listdata) {
                return format_uang($listdata->harga_jual);
            })
            // buat yang didalam kolom
            ->addColumn('action', function ($listdata) {
                return '
                <button onclick="editForm(`' . route('produk.update', $listdata->id_produk) . '`)" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('produk.destroy', $listdata->id_produk) . '`)" class="btn btn-xs btn-danger">Delete</button>
            ';
            })
            // buat menampilkan
            ->rawColumns(['action', 'kode_produk'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $validated = Validator::make($request->all(), [
            'nama' => 'required|unique:produk,nama_produk',
            'kategori' => 'required',
            'merk' => 'nullable',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'diskon' => 'required',
            'stok' => 'required'

        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
                'errors' => $validated->messages()
            ]);
        } else {
            $produk = Produk::latest()->first();
            if ($produk == null) {
                $request['kode_produk'] = 'P' . kode_produk(1, 4);
            } else {
                $request['kode_produk'] = 'P' . kode_produk((int) $produk->id_produk + 1, 4);
            }
            $data = new Produk();
            $data->id_kategori = $request->kategori;
            $data->kode_produk = $request['kode_produk'];
            $data->nama_produk = $request->nama;
            $data->merk = $request->merk;
            $data->harga_beli = $request->harga_beli;
            $data->harga_jual = $request->harga_jual;
            $data->diskon = $request->diskon;
            $data->stock = $request->stok;
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Added Data'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id_kategori
     * @return \Illuminate\Http\Response
     */
    public function show($id_kategori)
    {
        $kategori = Kategori::find($id_kategori);
        return response()->json($kategori);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id_kategori
     * @return \Illuminate\Http\Response
     */
    public function edit($id_kategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_kategori)
    {
        $validated = Validator::make($request->all(), [
            'nama_kategori' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed updated',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = Kategori::find($id_kategori);
            $data->nama_kategori = $request->nama_kategori;
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Updated Data'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id_kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_produk)
    {
        $data = Produk::find($id_produk);
        $data->delete();
    }
}
