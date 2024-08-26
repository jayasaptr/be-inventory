<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetBarangModel;
use App\Models\AssetMasukModel;
use Illuminate\Http\Request;

class AssetMasukController extends Controller
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

        $query = AssetMasukModel::with('assetId');

        if ($search) {
            $query->whereHas('assetId', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($startDate) {
            $query->whereDate('tanggal_masuk', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_masuk', '<=', $endDate);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $data = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List asset masuk',
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

        $assetMasuk = AssetMasukModel::create($data);

        $assetBarang = AssetBarangModel::where('kode', $assetMasuk->assetId->code)->first();

        if ($assetBarang) {
            $assetBarang->jumlah += $assetMasuk->quantity;
            $assetBarang->save();
        } else {
            $assetBarang = AssetBarangModel::create([
                'kode' => $assetMasuk->assetId->code,
                'nama' => $assetMasuk->assetId->name,
                'category' => $assetMasuk->category,
                'kondisi' => $assetMasuk->kondisi,
                'jumlah' => $assetMasuk->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset masuk berhasil ditambahkan',
            'data' => $assetMasuk
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = AssetMasukModel::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Asset masuk tidak ditemukan',
                'data' => ''
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail asset masuk',
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
        $data = $request->all();

        $assetMasuk = AssetMasukModel::find($id);
        if (!$assetMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Asset masuk tidak ditemukan',
                'data' => ''
            ], 404);
        }
        $assetMasuk->update($data);

        $totalQuantity = AssetMasukModel::where('asset_id', $assetMasuk->asset_id)->sum('quantity');

        $assetBarang = AssetBarangModel::where('kode', $assetMasuk->assetId->code)->first();
        if ($assetBarang) {
            $assetBarang->update(['jumlah' => $totalQuantity]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset masuk berhasil diupdate',
            'data' => $assetMasuk
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assetMasuk = AssetMasukModel::find($id);
        if (!$assetMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Asset masuk tidak ditemukan',
                'data' => ''
            ], 404);
        }

        $assetId = $assetMasuk->asset_id;
        $assetMasuk->delete();

        $totalQuantity = AssetMasukModel::where('asset_id', $assetId)->sum('quantity');
        $assetBarang = AssetBarangModel::where('kode', $assetMasuk->assetId->code)->first();

        if ($assetBarang) {
            if ($totalQuantity == 0) {
                $assetBarang->delete();
            } else {
                $assetBarang->update(['jumlah' => $totalQuantity]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset masuk berhasil dihapus',
            'data' => $assetMasuk
        ], 200);
    }
}
