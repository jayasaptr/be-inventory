<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';

        $assets = AssetKeluar::whereHas('idAsset', function($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })->with('idAsset')->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List asset keluar',
            'data' => $assets
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
        $validate = Validator::make($request->all(), [
            'id_asset' => 'required',
            'penerima' => 'required',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required',
            'keterangan' => 'required',
            'kondisi' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 442);
        }

        $asset = Asset::find($request->id_asset);
        if ($asset->quantity < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah asset tidak mencukupi',
            ], 400);
        }

        $assetKeluar = AssetKeluar::create($request->all());

        // kurang jumlah di aset
        $asset->quantity = $asset->quantity - $request->jumlah;
        $asset->save();

        return response()->json([
            'success' => true,
            'message' => 'Asset keluar created',
            'data' => $assetKeluar
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assetKeluar = AssetKeluar::find($id);

        if (!$assetKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'Asset keluar not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset keluar found',
            'data' => $assetKeluar
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
        $assetKeluar = AssetKeluar::find($id);

        if (!$assetKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'Asset keluar not found',
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            'id_asset' => 'required',
            'penerima' => 'required',
            'jumlah' => 'required',
            'tanggal_keluar' => 'required',
            'keterangan' => 'required',
            'kondisi' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 442);
        }

        // balikin jumlah di aset
        $asset = Asset::find($assetKeluar->id_asset);
        $asset->quantity = $asset->quantity + $assetKeluar->jumlah;

        $assetKeluar->update($request->all());

        // kurang jumlah di aset
        $asset = Asset::find($request->id_asset);
        $asset->quantity = $asset->quantity - $request->jumlah;
        $asset->save();

        return response()->json([
            'success' => true,
            'message' => 'Asset keluar updated',
            'data' => $assetKeluar
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assetKeluar = AssetKeluar::find($id);

        if (!$assetKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'Asset keluar not found',
            ], 404);
        }

        $assetKeluar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asset keluar deleted',
        ], 200);
    }
}
