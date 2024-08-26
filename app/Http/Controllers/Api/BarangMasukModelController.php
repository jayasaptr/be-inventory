<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetBarangModel;
use App\Models\BarangMasukModel;
use Illuminate\Http\Request;

class BarangMasukModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $category = $request->category ?? '';
        $kondisi = $request->kondisi ?? '';

        $data = BarangMasukModel::when($search, function($query) use ($search) {
            $query->whereHas('barangId', function($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%');
            });
        })->when($startDate, function($query) use ($startDate) {
            $query->where('tanggal_masuk', '>=', $startDate);
        })->when($endDate, function($query) use ($endDate) {
            $query->where('tanggal_masuk', '<=', $endDate);
        })->when($category, function($query) use ($category) {
            $query->where('id_category', $category);
        })->when($kondisi, function($query) use ($kondisi) {
            $query->where('kondisi', $kondisi);
        })->with('barangId')->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List barang masuk',
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

        $barangMasuk = BarangMasukModel::create($data);

        $assetBarang = AssetBarangModel::where('kode', $barangMasuk->barangId->code_barang)->first();

        if ($assetBarang) {
            $assetBarang->jumlah += $barangMasuk->quantity;
            $assetBarang->save();
        } else {
            $assetBarang = AssetBarangModel::create([
                'kode' => $barangMasuk->barangId->code_barang,
                'nama' => $barangMasuk->barangId->nama,
                'category' => $barangMasuk->category,
                'kondisi' => $barangMasuk->kondisi,
                'jumlah' => $barangMasuk->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil ditambahkan',
            'data' => $barangMasuk
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = BarangMasukModel::with('barangId')->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan',
                'data' => ''
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail barang masuk',
            'data' => $data
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
        $data = $request->all();

        $barangMasuk = BarangMasukModel::find($id);

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan',
                'data' => ''
            ], 404);
        }

        $barangMasuk->update($data);

        $totalQuantity = BarangMasukModel::where('barang_id', $barangMasuk->barang_id)->sum('quantity');

        $assetBarang = AssetBarangModel::where('kode', $barangMasuk->barangId->code_barang)->first();
        if ($assetBarang) {
            $assetBarang->update(['jumlah' => $totalQuantity]);
        }

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
        $barangMasuk = BarangMasukModel::find($id);

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan',
                'data' => ''
            ], 404);
        }

        $barangId = $barangMasuk->barang_id;
        $barangMasuk->delete();

        $totalQuantity = BarangMasukModel::where('barang_id', $barangId)->sum('quantity');
        $assetBarang = AssetBarangModel::where('kode', $barangMasuk->barangId->code_barang)->first();

        if ($assetBarang) {
            if ($totalQuantity == 0) {
                $assetBarang->delete();
            } else {
                $assetBarang->update(['jumlah' => $totalQuantity]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil dihapus',
            'data' => $barangMasuk
        ], 200);
    }
}
