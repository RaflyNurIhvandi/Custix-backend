<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProgramerController extends Controller
{
    public function index()
    {
        try {
            $users = DB::table('users')
                ->select('users.*', DB::raw('(SELECT COUNT(*) FROM project WHERE project.id_programer = users.id) as project_count'))
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'akses' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();
            if ($user && $user->akses === 'master admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus Master Admin'
                ]);
            }

            DB::table('users')->where('id', $id)->delete();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function unassignedProjects()
    {
        try {
            $projects = DB::table('project')->whereNull('id_programer')->get();

            return response()->json([
                'status' => 'success',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function assignProjects(Request $request)
    {
        try {
            foreach ($request->project_ids as $id) {
                DB::table('project')->where('id_project', $id)->update([
                    'id_programer' => $request->user_id,
                    'status' => 'dikerjakan'
                ]);
            }

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getUserProjects($id)
    {
        try {
            $projects = DB::table('project')
                ->where('id_programer', $id)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function unassignProject(Request $request)
    {
        try {
            DB::table('project')->where('id_project', $request->project_id)->update([
                'id_programer' => null,
                'status' => 'baru masuk'
            ]);

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
