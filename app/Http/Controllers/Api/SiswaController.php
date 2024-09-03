<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::all();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar data siswa',
            'data' => $siswa
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
        $siswa = Siswa::create([
            'npm' => $request->npm,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'umur' => $request->umur,
        ]);

        if($siswa)
        {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $siswa
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Siswa gagal ditambahkan',
                'data' => $siswa
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::find($id);

        if($siswa)
        {
            $siswa->update([
                'npm' => $request->npm ?? $siswa->npm,
                'nama' => $request->nama ?? $siswa->nama,
                'kelas' => $request->kelas ?? $siswa->kelas,
                'umur' => $request->umur ?? $siswa->umur,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil diupdate',
                'data' => $siswa
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Siswa gagal diupdate',
                'data' => $siswa
            ], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::find($id);

        if($siswa)
        {
            $siswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus',
                'data' => $siswa
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Siswa gagal dihapus',
                'data' => $siswa
            ], 409);
        }
    }
}
