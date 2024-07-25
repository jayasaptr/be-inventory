<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get all user from db with pagination and search by name
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';

        $users = User::where('name', 'like', "%$search%")->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List user',
            'data' => $users
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // set validation
         $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'nip' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'no_telp' => 'required|string',
            'alamat' => 'required|string',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // upload foto
        $foto = $request->file('foto');
        $foto->storeAs('public/user', $foto->hashName());

        // create user
        $user = User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'role' => $request->role,
            'foto' => $foto->hashName(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail user',
            'data' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // cek apakah user yang di edit mengupload foto baru
        if ($request->hasFile('foto')) {

            // hapus foto lama

            $url = $user->foto;
            $parsedUrl = parse_url($url, PHP_URL_PATH);
            $basePath = basename($parsedUrl);

            $path = 'public/user/' . $basePath;
            
            Storage::disk('local')->delete($path);

            // upload foto
            $foto = $request->file('foto');
            $foto->storeAs('public/user', $foto->hashName());

            // update user
            $user->update([
                'name' => $request->name ?? $user->name,
                'nip' => $request->nip ?? $user->nip,
                'email' => $request->email ?? $user->email,
                'password' => Hash::make($request->password) ?? $user->password,
                'no_telp' => $request->no_telp ?? $user->no_telp,
                'alamat' => $request->alamat ?? $user->alamat,
                'role' => $request->role ?? $user->role,
                'foto' => $foto->hashName(),
            ]);
        } else {
            // update user tanpa mengubah foto
            $user->update([
                'name' => $request->name ?? $user->name,
                'nip' => $request->nip ?? $user->nip,
                'email' => $request->email ?? $user->email,
                'password' => Hash::make($request->password) ?? $user->password,
                'no_telp' => $request->no_telp ?? $user->no_telp,
                'alamat' => $request->alamat ?? $user->alamat,
                'role' => $request->role ?? $user->role,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }
}
