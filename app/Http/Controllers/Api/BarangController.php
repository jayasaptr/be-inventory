<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data barang dari database dengan pagination dan search query
        $barangs = Barang::when($request->search, function($query) use ($request) {
            $query->where('nama_barang', 'like', "%{$request->search}%");
        })->paginate(100);

        // Mengembalikan data barang dalam bentuk json
        return response()->json([
            'success' => true,
            'message' => 'List category',
            'data' => $barangs
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
        // Validasi data yang diterima

        $validation = Validator::make($request->all(), [
            'nama' => 'required',
            'merk' => 'required',
            'id_category' => 'required',
            'jumlah' => 'required',
            'satuan' => 'required',
            'harga' => 'required',
            'keterangan' => 'required',
        ]);

        // Jika validasi gagal, kirim response error
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        // Menyimpan data barang ke database
        $barang = Barang::create([
            'nama' => $request->nama,
            'merk' => $request->merk,
            'id_category' => $request->id_category,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'keterangan' => $request->keterangan,
        ]);

        // Mengembalikan response sukses
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Mengambil data barang berdasarkan id
        $barang = Barang::find($id);

        // Jika barang tidak ditemukan

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        // Mengembalikan data barang dalam bentuk json
        return response()->json([
            'success' => true,
            'message' => 'Detail barang',
            'data' => $barang
        ], 200);
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
        // Mengambil data barang berdasarkan id
        $barang = Barang::find($id);

        // Jika barang tidak ditemukan

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        // Update data barang
        $barang->update([
            'nama' => $request->nama ?? $barang->nama,
            'merk' => $request->merk ?? $barang->merk,
            'id_category' => $request->id_category ?? $barang->id_category,
            'jumlah' => $request->jumlah ?? $barang->jumlah,
            'satuan' => $request->satuan ?? $barang->satuan,
            'harga' => $request->harga ?? $barang->harga,
            'keterangan' => $request->keterangan ?? $barang->keterangan,
        ]);

        // Mengembalikan response sukses
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate',
            'data' => $barang
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Mengambil data barang berdasarkan id
        $barang = Barang::find($id);

        // Jika barang tidak ditemukan

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        // Menghapus data barang dari database
        $barang->delete();

        // Mengembalikan response sukses
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus',
            'data' => $barang
        ], 200);
    }
}
