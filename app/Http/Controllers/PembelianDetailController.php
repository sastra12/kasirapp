<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;


class PembelianDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        $supplier = Supplier::find(session('id_supplier'));
        if (!$supplier) {
            abort(404);
        }
        // return session('id_supplier');
        // return view('pembelian_detail.index', compact('id_pembelian', 'produk', 'supplier'));
        return view('pembelian_detail.index', compact('produk', 'supplier'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data($id)
    {
        $detail = PembelianDetail::with('produk')
            ->where('id_pembelian', $id)
            ->get();

        return datatables($detail)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('kode_produk', function ($detail) {
                return
                    '<span class="badge badge-success">' .  $detail->produk->kode_produk . '</span>';
            })
            ->addColumn('action', function ($detail) {
                return '
                <button onclick="deleteData(`' . route('pembelian-detail.destroy', $detail->id_pembelian_detail) . '`)" class="btn btn-xs btn-danger">Delete</button>
                ';
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_beli);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp ' . format_uang($detail->subtotal);
            })
            ->rawColumns(['action', 'kode_produk'])
            ->make(true);
    }

    public function create()
    {
        //
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

    public function deleteCart(Request $request)
    {
        if ($request->ajax()) {
            $keranjangPembelian = session()->get('cartpembelian');
            unset($keranjangPembelian[$request->id]);
            session()->put('cartpembelian', $keranjangPembelian);
        }
    }

    public function incrementCart(Request $request)
    {
        $value = $request->value;
        // return $value;
        $produk = Produk::find($request->id);
        $keranjangPembelian = session()->get('cartpembelian');
        // return $keranjangPembelian;
        // return $request->id;
        if ($request->ajax()) {
            if ($produk->stock <= $value) {
                return response()->json([
                    'message' => 'Failed'
                ]);
            } else {
                $keranjangPembelian[$request->id]['quantity'] = $value;
                // update session cart
                session()->put('cartpembelian', $keranjangPembelian);
                return response()->json(['message' => 'Success']);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = Validator::make($request->all(), [
                'bayar' => 'required|numeric',
            ]);

            if ($validated->fails()) {
                return redirect('/pembelian-detail')->withErrors($validated);
            } else {
                // update pada tabel pembelian
                $pembelian = Pembelian::where('id_pembelian', $request->id_pembelian)->first();
                $pembelian->total_item = $request->total_item;
                $pembelian->total_harga = $request->totalrp;
                $pembelian->bayar = $request->bayar;
                $pembelian->save();

                foreach (session('cartpembelian') as $key => $item) {
                    $index = 0;
                    //  tambahkan ke tabel pembelian detail
                    DB::table('pembelian_detail')->insert([
                        'id_pembelian' => $request->id_pembelian,
                        'id_produk' => $key,
                        'harga_beli' => $item['price'],
                        'jumlah' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'created_at' => Carbon::now()
                    ]);

                    $stockproduk = DB::table('produk')->where('id_produk', $key)->get();
                    $quantity = $stockproduk[$index]->stock + $item['quantity'];
                    // return $stockproduk[$index]->stock;
                    DB::table('produk')->where('id_produk', $key)->update([
                        'stock' => $quantity
                    ]);
                    $index++;
                }
            }
            $request->session()->forget('cartpembelian');
            $request->session()->forget('id_supplier');
            DB::commit();
            return redirect('/pembelian');
        } catch (Exception $e) {
            DB::rollback();
        }
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
        $data = PembelianDetail::find($id);
        $data->delete();
        return response()->json(null, 204);
    }
}
