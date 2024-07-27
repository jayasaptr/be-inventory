<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $barangs = BarangKeluar::when($request->search, function($query) use ($request) {
            $query->whereHas('idBarang', function($query) use ($request) {
                $query->where('nama', 'like', "%{$request->search}%");
            });
        })->with('idBarangMasuk', 'idKondisi:id,nama')->paginate(100);

        // get nama from idBarangMasuk
        foreach ($barangs as $barang) {
            $barang->nama_barang = $barang->idBarangMasuk->idBarang->nama;
        }

        // Mengembalikan data barang dalam bentuk json
        return response()->json([
            'success' => true,
            'message' => 'List barang keluar',
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
            'id_barang_masuk' => 'required',
            'penerima' => 'required',
            'jumlah' => 'required',
            'tanggal_keluar' => 'required',
            'keterangan' => 'required',
            'id_kondisi' => 'required',
        ]);

        // Jika validasi gagal, kirim response error

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        // Buat data barang masuk baru

        $barang = BarangKeluar::create([
            'id_barang_masuk' => $request->id_barang_masuk,
            'penerima' => $request->penerima,
            'jumlah' => $request->jumlah,
            'tanggal_keluar' => $request->tanggal_keluar,
            'keterangan' => $request->keterangan,
            'id_kondisi' => $request->id_kondisi,
        ]);

        // ketika berhasil membuat data barang keluar kurangi jumlah barang di barang masuk
        $barangMasuk = BarangMasuk::find($request->id_barang_masuk);
        $barangMasuk->jumlah = $barangMasuk->jumlah - $request->jumlah;
        $barangMasuk->save();

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang keluar berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cari data barang keluar berdasarkan id

        $barang = BarangKeluar::with('idBarang', 'idKondisi:id,nama')->find($id);

        // get nama from idBarangMasuk
        $barang->nama_barang = $barang->idBarangMasuk->idBarang->nama;

        // Jika barang keluar tidak ditemukan

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang keluar tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Mengembalikan data barang keluar dalam bentuk json

        return response()->json([
            'success' => true,
            'message' => 'Detail barang keluar',
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
        // Cari data barang keluar berdasarkan id

        $barang = BarangKeluar::find($id);

        // Jika barang keluar tidak ditemukan

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang keluar tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Validasi data yang diterima

        $validation = Validator::make($request->all(), [
            'id_barang' => 'required',
            'id_kondisi' => 'required',
            'jumlah' => 'required',
            'tanggal_keluar' => 'required',
        ]);

        // Jika validasi gagal, kirim response error

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        // Update data barang keluar

        $barang->id_barang = $request->id_barang;

        $barang->id_kondisi = $request->id_kondisi;

        $barang->jumlah = $request->jumlah;

        $barang->tanggal_keluar = $request->tanggal_keluar;

        $barang->penerima = $request->penerima;

        $barang->keterangan = $request->keterangan;

        $barang->save();

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang keluar berhasil diupdate',
            'data' => $barang
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari data barang keluar berdasarkan id

        $barang = BarangKeluar::find($id);

        // Jika barang keluar tidak ditemukan

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang keluar tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Hapus data barang keluar

        $barang->delete();

        // ketika berhasil menghapus data barang keluar tambahkan jumlah barang di barang masuk
        $barangMasuk = BarangMasuk::find($barang->id_barang_masuk);
        $barangMasuk->jumlah = $barangMasuk->jumlah + $barang->jumlah;
        $barangMasuk->save();

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang keluar berhasil dihapus',
            'data' => $barang
        ], 200);
    }
}
