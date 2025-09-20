<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AdminProfileController extends Controller
{
    public function show(){
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()
        ]);
    }
    public function update(Request $request){
        try {
            $user = User::find(Auth::id());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'no_telp' => 'nullable|string|max:20',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }
            if ($request->filled('password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Password lama salah'
                    ]);
                }
                $user->password = Hash::make($request->password);
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telp = $request->no_telp;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }
    public function updatePhoto(Request $request){
        try {
            $user = User::find(Auth::id());
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = time() . '_' . $foto->getClientOriginalName();
                if ($user->foto_profile) {
                    $oldPath = public_path('foto_profiles/' . $user->foto_profile);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $foto->move(public_path('foto_profiles'), $filename);
                $user->foto_profile = $filename;
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'filename' => $filename
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada file yang dikirim'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat upload'
            ], 500);
        }
    }
}
