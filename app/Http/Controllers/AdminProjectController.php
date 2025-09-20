<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProjectController extends Controller
{
    public function checkFile($id){
        try {
            $project = DB::table('project')->where('id_project', $id)->first();
            if (!$project) {
                return response()->json(['status' => 'error', 'message' => 'Project tidak ditemukan']);
            }
            if (!$project->file_project || trim($project->file_project) == '') {
                return response()->json(['status' => 'empty', 'message' => 'File belum tersedia']);
            }
            return response()->json([
                'status' => 'success',
                'filename' => $project->file_project
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function getProjectsByUser(Request $request){
        $userId = $request->user()->id;
        try {
            $projects = DB::table('project')
                ->where('id_programer', $userId)
                ->get();
            return response()->json($projects);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data project'], 500);
        }
    }
    public function selesaikanProject(Request $request){
        try {
            DB::table('project')
                ->where('id_project', $request->id_project)
                ->update([
                    'status' => 'selesai',
                    'link_project' => $request->link_project
                ]);
            return response()->json(['message' => 'Project berhasil diselesaikan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyelesaikan project'], 500);
        }
    }
}
