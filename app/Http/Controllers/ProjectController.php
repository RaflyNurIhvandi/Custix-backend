<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProjectController extends Controller
{
    function update(Request $request, $id) {
        if ($request->file_project === null){
            try {
                $data = DB::table('project')->where('id_project', $id)->update([
                    "nama_project"=>$request->nama_project,
                    "deskripsi_project"=>$request->deskripsi_project,
                    "tanggal_pesanan"=>$request->tanggal_pesanan,
                    "estimasi_selesai"=>$request->estimasi_selesai,
                    "status"=>$request->status,
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
        } else {
            try {
                $dfile = DB::table('project')->where('id_project', $id)->value('file_project');
                $path = public_path('file_projek/uploads/' . $dfile);
                File::delete($path);
                $data = DB::table('project')->where('id_project', $id)->update([
                    "nama_project"=>$request->nama_project,
                    "deskripsi_project"=>$request->deskripsi_project,
                    "tanggal_pesanan"=>$request->tanggal_pesanan,
                    "file_project"=>$request->file_project,
                    "estimasi_selesai"=>$request->estimasi_selesai,
                    "status"=>$request->status,
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
    function load() {
        $data = DB::table('project')
                ->join('pelanggan', 'project.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->orderBy('id_project', 'desc')
                ->get();
        return response()->json([
            "status"=>"success",
            "data"=>$data
        ]);
    }
    function save(Request $request)
    {
        if ($request->file_project === null) {
            try {
                $data = DB::table('project')->insert([
                    "id_pelanggan" => $request->id_pelanggan,
                    "nama_project" => $request->nama_project,
                    "deskripsi_project" => $request->deskripsi_project,
                    "tanggal_pesanan" => $request->tanggal_pesanan,
                    "estimasi_selesai" => $request->estimasi_selesai,
                    "status" => $request->status,
                ]);
                return response()->json([
                    "status" => "success",
                    "data" => $data
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "status" => "success",
                    "data" => $e
                ]);
            }
        } else {
            try {
                $filename = $request->input('file_project');
                $data = DB::table('project')->insert([
                    "id_pelanggan" => $request->id_pelanggan,
                    "nama_project" => $request->nama_project,
                    "deskripsi_project" => $request->deskripsi_project,
                    "file_project" => $filename,
                    "tanggal_pesanan" => $request->tanggal_pesanan,
                    "estimasi_selesai" => $request->estimasi_selesai,
                    "status" => $request->status,
                ]);
                return response()->json([
                    "status" => "success",
                    "data" => $data
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "status" => "success",
                    "data" => $e
                ]);
            }
        }
    }
    function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $filename = "PRO" . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('file_projek/uploads'), $filename);

            return response()->json(['filename' => $filename]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
