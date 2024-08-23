<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';

        $assets = Asset::where('name', 'like', "%$search%")->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List asset',
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
            'code' => 'required',
            'name' => 'required', 
            'description' => 'required', 
            'type' => 'required',
            'purchase_date' => 'required', 
            'price' => 'required', 
            'quantity' => 'required',
            'kondisi' => 'required',
            'satuan' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 442);
        }

        $asset = Asset::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Asset created',
            'data' => $asset
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset detail',
            'data' => $asset
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
        $asset = Asset::find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found',
            ], 404);
        }

        $asset->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Asset updated',
            'data' => $asset
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found',
            ], 404);
        }

        $asset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asset deleted',
        ], 200);
    }
}
