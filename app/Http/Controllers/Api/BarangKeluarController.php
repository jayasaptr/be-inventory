<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\BarangRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data barang keluar dengan relasi barang masuk dan kondisi dan tampilkan nama category dari barang masuk
        $pagination = $request->pagination ?? 100;  
        $search = $request->search ?? '';

        $barang_keluar = BarangKeluar::with(['idBarangMasuk' => function($query) {
            $query->select('id', 'nama', 'merk', 'id_category', 'jumlah', 'satuan', 'harga', 'keterangan', 'id_kondisi', 'tanggal_masuk')
                  ->with('idCategory');
        }, 'idKondisi'])
        ->whereHas('idBarangMasuk', function($query) use ($search) {
            $query->where('nama', 'like', "%$search%");
        })->paginate($pagination);

    
        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'List barang keluar',
            'data' => $barang_keluar
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

        // jika terdapat id_barang_ruang maka kurangi stock barang di barang permintaan sesuai id_barang_ruang
        if ($request->id_barang_ruang) {
            $barangRuang = BarangRuangan::find($request->id_barang_ruang);
            $barangRuang->jumlah = $barangRuang->jumlah - $request->jumlah;
            $barangRuang->save();
        }else {
            // kurangi stock barang di barang masuk sesuai id_barang_masuk
            $barangMasuk = BarangMasuk::find($request->id_barang_masuk);
            $barangMasuk->stock = $barangMasuk->stock - $request->jumlah;
            $barangMasuk->save();
        }

      

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

        $barang = BarangKeluar::with('idBarangMasuk:id,nama,merk,id_category,jumlah,satuan,harga,keterangan,id_kondisi,tanggal_masuk', 'idKondisi')->find($id);

        // Jika barang keluar tidak ditemukan

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang keluar tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Mengembalikan response sukses

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
        $barangMasuk->stock = $barangMasuk->stock + $barang->jumlah;
        $barangMasuk->save();

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang keluar berhasil dihapus',
            'data' => $barang
        ], 200);
    }
}
