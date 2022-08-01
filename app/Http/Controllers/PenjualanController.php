<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Auth;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

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
            if ($produk->stock <= $cart[$id]['quantity']) {
                return redirect()->back()->with('failed', 'Stok tidak mencukupi');
            } else {
                $cart[$id]['quantity']++;
            }
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
        $produk = Produk::find($request->id);
        // mengambil session dengan fungsi get
        $cart = session()->get('cart');
        // ddd($produk);
        if ($request->ajax()) {
            if ($produk->stock <= $cart[$request->id]['quantity']) {
                return response()->json([
                    'message' => 'Failed'
                ]);
            } else {
                $cart[$request->id]['quantity'] += 1;
                // update session cart
                session()->put('cart', $cart);
                return response()->json(['message' => 'Success']);
            }
        }
    }

    public function decrementCart(Request $request)
    {
        if ($request->ajax()) {
            // mengambil session dengan fungsi get
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] -= 1;
            // update session cart
            session()->put('cart', $cart);
            if ($cart[$request->id]['quantity'] == 0) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
        }
    }

    public function deleteCart(Request $request)
    {
        if ($request->ajax()) {
            $cart = session()->get('cart');
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // tambah ke table penjualan
            DB::table('penjualan')->insert([
                'total_item' => $request->total_item,
                'total_harga' => $request->total,
                'diskon' => $request->diskon,
                'bayar' => $request->bayar,
                'diterima' => $request->diterima,
                'id_user' => Auth::id(),
                'created_at' => Carbon::now()
            ]);

            $penjualan = DB::table('penjualan')->latest()->first();
            foreach (session('cart') as $key => $item) {
                $index = 0;
                // tambah ke tabel penjualan detail
                DB::table('penjualan_detail')->insert([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_produk' => $key,
                    'harga_jual' => $item['price'],
                    'jumlah' => $item['quantity'],
                    'diskon' => 0,
                    'subtotal' => $item['price'] * $item['quantity'],
                    'created_at' => Carbon::now()
                ]);

                $stockproduk = DB::table('produk')->where('id_produk', $key)->get();
                $quantity = $stockproduk[$index]->stock - $item['quantity'];
                // return $stockproduk[$index]->stock;
                DB::table('produk')->where('id_produk', $key)->update([
                    'stock' => $quantity
                ]);
                $index++;
            }
            $request->session()->forget('cart');
            DB::commit();
            return redirect()->route('penjualan.index');
        } catch (Exception $e) {
            DB::rollback();
        }
    }
}
