<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    function delete($id) {
        try {
            $data = DB::table('pelanggan')->where('id_pelanggan', $id)->delete();
            return response()->json([
                "status"=>"success",
                "data"=>$data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status"=>"error",
                "data"=>$e
            ]);
        }
    }
    function update(Request $request, $id) {
        try {
            $data = DB::table('pelanggan')->where('id_pelanggan', $id)->update([
                "nama_pelanggan"=>$request->nama_pelanggan,
                "no_telp"=>$request->no_telp,
                "gender"=>$request->gender,
                "alamat"=>$request->alamat,
                "kategori"=>$request->kategori,
            ]);
            return response()->json([
                "status"=>"success",
                "data"=>$data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status"=>"error",
                "data"=>$e
            ]);
        }
    }
    function load() {
        $data = DB::table('pelanggan')->get();
        return response()->json([
            "status"=>"success",
            "data"=>$data
        ]);
    }
    function save(Request $request) {
        $idPel = "PEL".date('YmdHis');
        try {
            $data = DB::table('pelanggan')->insert([
                'id_pelanggan'=>$idPel,
                'nama_pelanggan'=>$request->nama_pelanggan,
                'no_telp'=>$request->no_telp,
                'gender'=>$request->gender,
                'alamat'=>$request->alamat,
                'kategori'=>$request->kategori,
            ]);
            return response()->json([
                "status"=>"success",
                "data"=>$data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status"=>"error",
                "data"=>$e
            ]);
        }
    }
}
