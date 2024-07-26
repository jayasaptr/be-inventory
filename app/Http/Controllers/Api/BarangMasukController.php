<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $barangs = BarangMasuk::when($request->search, function($query) use ($request) {
            $query->whereHas('idBarang', function($query) use ($request) {
                $query->where('nama', 'like', "%{$request->search}%");
            });
            // with idBarang dan idKondisi
        })->with('idBarang', 'idKondisi:id,nama')->paginate(100);

        // Mengembalikan data barang dalam bentuk json
        return response()->json([
            'success' => true,
            'message' => 'List barang masuk',
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
            'id_barang' => 'required',
            'id_kondisi' => 'required',
            'jumlah' => 'required',
            'tanggal_masuk' => 'required',
        ]);

        // Jika validasi gagal, kirim response error

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        // Membuat data barang masuk baru

        $barangMasuk = BarangMasuk::create([
            'id_barang' => $request->id_barang,
            'id_kondisi' => $request->id_kondisi,
            'jumlah' => $request->jumlah,
            'tanggal_masuk' => $request->tanggal_masuk,
        ]);

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil ditambahkan',
            'data' => $barangMasuk
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Mencari data barang masuk berdasarkan id

        $barangMasuk = BarangMasuk::with('idBarang', 'idKondisi')->find($id);

        // Jika data barang masuk tidak ditemukan

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan'
            ], 404);
        }

        // Mengembalikan data barang masuk dalam bentuk json

        return response()->json([
            'success' => true,
            'message' => 'Detail barang masuk',
            'data' => $barangMasuk
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
        // Mencari data barang masuk berdasarkan id

        $barangMasuk = BarangMasuk::find($id);

        // Jika data barang masuk tidak ditemukan

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan'
            ], 404);
        }

        // Mengupdate data barang masuk

        $barangMasuk->update([
            'id_barang' => $request->id_barang ?? $barangMasuk->id_barang,
            'id_kondisi' => $request->id_kondisi ?? $barangMasuk->id_kondisi,
            'jumlah' => $request->jumlah ?? $barangMasuk->jumlah,
            'tanggal_masuk' => $request->tanggal_masuk ?? $barangMasuk->tanggal_masuk,
        ]);

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil diupdate',
            'data' => $barangMasuk
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Mencari data barang masuk berdasarkan id

        $barangMasuk = BarangMasuk::find($id);

        // Jika data barang masuk tidak ditemukan

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan'
            ], 404);
        }

        // Menghapus data barang masuk

        $barangMasuk->delete();

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil dihapus',
        ], 200);
    }
}
