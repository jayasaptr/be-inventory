<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetBarangKeluar;
use App\Models\AssetBarangModel;
use Illuminate\Http\Request;

class AssetBarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $category = $request->category ?? '';
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $kondisi = $request->kondisi ?? '';

        $query = AssetBarangKeluar::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->where('category', 'like', '%' . $category . '%');
        }

        if ($startDate) {
            $query->where('tanggal_keluar', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('tanggal_keluar', '<=', $endDate);
        }

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        $data = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List asset barang keluar',
            'data' => $data
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
        $data = $request->all();

        $assetBarangKeluar = AssetBarangKeluar::create($data);

        $assetBarang = AssetBarangModel::where('kode', $assetBarangKeluar->kode)->first();
        if ($assetBarang) {
            $assetBarang->jumlah -= $assetBarangKeluar->jumlah;
            $assetBarang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset barang keluar berhasil ditambahkan',
            'data' => $assetBarangKeluar
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = AssetBarangKeluar::find($id);
        

        return response()->json([
            'success' => true,
            'message' => 'Detail asset barang keluar',
            'data' => $data
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
        $assetBarangKeluar = AssetBarangKeluar::findOrFail($id);
        $data = $request->all();

        $oldJumlah = $assetBarangKeluar->jumlah;
        $assetBarangKeluar->update($data);

        $assetBarang = AssetBarangModel::where('kode', $assetBarangKeluar->kode)->first();
        if ($assetBarang) {
            $assetBarang->jumlah += $oldJumlah;
            $assetBarang->jumlah -= $assetBarangKeluar->jumlah;
            $assetBarang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset barang keluar berhasil diupdate',
            'data' => $assetBarangKeluar
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assetBarangKeluar = AssetBarangKeluar::findOrFail($id);
        $jumlahKeluar = $assetBarangKeluar->jumlah;

        $assetBarang = AssetBarangModel::where('kode', $assetBarangKeluar->kode)->first();
        if ($assetBarang) {
            $assetBarang->jumlah += $jumlahKeluar;
            $assetBarang->save();
        }

        $assetBarangKeluar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asset barang keluar berhasil dihapus',
        ], 200);
    }
}
