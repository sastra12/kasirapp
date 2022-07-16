<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kategori.index');
    }

    public function data(Request $request)
    {
        $listdata = Kategori::all();
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '
                <button onclick="editForm(`' . route('kategori.update', $data->id_kategori) . '`)" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('kategori.destroy', $data->id_kategori) . '`)" class="btn btn-xs btn-danger">Delete</button>
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
            'nama_kategori' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = new Kategori();
            $data->nama_kategori = $request->nama_kategori;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id_kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_kategori)
    {
        $data = Kategori::find($id_kategori);
        $data->delete();
    }
}
