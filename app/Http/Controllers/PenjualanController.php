<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Yajra\DataTables\DataTables;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
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
                <a href="' . route('add.to.cart', $listdata->id_produk) . '" class="btn btn-xs btn-success">Pilih</a>
            ';
            })
            // buat menampilkan
            ->rawColumns(['action', 'kode_produk'])
            ->make(true);
    }

    public function addToCart($id)
    {
        $produk = Produk::find($id);
        $cart = session('cart', []);
        // []

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $produk->nama_produk,
                "kode_produk" => $produk->kode_produk,
                "quantity" => 1,
                "price" => $produk->harga_jual,
            ];
        }

        session()->put('cart', $cart);
        // return $cart;
        // "2": {
        //     "name": "Casablanca",
        //     "quantity": 1,
        //     "price": 7000
        //     },
        // foreach (session('cart') as $key => $item) {
        //     var_dump($key);
        // }
        // return (session('cart'));
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function cart()
    {
        return view('penjualan.cart');
    }

    public function incrementCart(Request $request)
    {
        if ($request->ajax()) {
            // mengambil session dengan fungsi get
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] += 1;
            // update session cart
            session()->put('cart', $cart);
        }
    }
}
