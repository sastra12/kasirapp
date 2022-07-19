<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{

    public function index()
    {
        return view('member.index');
    }

    public function data()
    {
        $member = Member::all();
        return datatables($member)
            ->addIndexColumn()
            ->addColumn('action', function ($member) {
                return '
                <button onclick="editForm(`' . route('member.update', $member->id_member) . '`)" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('member.destroy', $member->id_member) . '`)" class="btn btn-xs btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'telepon' => 'numeric',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
                'errors' => $validated->messages()
            ]);
        } else {
            $member = Member::latest()->first();
            if ($member == null) {
                // M001
                $request['kode_member'] = 'M' . kode_member(1, 3);
            } else {
                $request['kode_member'] = 'M' . kode_member((int) substr($member->kode_member, 3) + 1, 3);
            }

            $data = new Member();
            $data->kode_member = $request['kode_member'];
            $data->nama = $request->nama;
            $data->alamat = $request->alamat;
            $data->telepon = $request->telepon;
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Added Data'
            ]);
        }
    }


    public function show(Member $member)
    {
        return response()->json($member);
    }

    public function edit(Member $member)
    { }


    public function update(Request $request, Member $member)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required',
            'telepon' => 'numeric',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
                'errors' => $validated->messages()
            ]);
        } else {
            // ddd($member);
            $member->nama = $request->nama;
            $member->alamat = $request->alamat;
            $member->telepon = $request->telepon;
            $member->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Updated Data'
            ]);
        }
    }


    public function destroy(Member $member)
    {
        $member->delete();
    }
}
