<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintanceAssetController extends Controller
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

        $maintanceAssets = MaintenanceAsset::where('status', 'like', "%$search%")
            ->where(function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->where('maintenance_date', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('maintenance_date', '<=', $endDate);
                }
            })
            ->with('assetId')
            ->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List maintance asset',
            'data' => $maintanceAssets
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
            'asset_id' => 'required',
            'maintenance_date' => 'required',
            'description' => 'required',
            'cost' => 'required',
            'qty' => 'required',
            'status' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 442);
        }

        $maintanceAsset = MaintenanceAsset::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Maintance asset created',
            'data' => $maintanceAsset
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $maintanceAsset = MaintenanceAsset::with('assetId')->find($id);

        if (!$maintanceAsset) {
            return response()->json([
                'success' => false,
                'message' => 'Maintance asset not found',
                'data' => ''
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Maintance asset detail',
            'data' => $maintanceAsset
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
        $maintanceAsset = MaintenanceAsset::find($id);

        if (!$maintanceAsset) {
            return response()->json([
                'success' => false,
                'message' => 'Maintance asset not found',
                'data' => ''
            ], 404);
        }

        $maintanceAsset->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Maintance asset updated',
            'data' => $maintanceAsset
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $maintanceAsset = MaintenanceAsset::find($id);

        if (!$maintanceAsset) {
            return response()->json([
                'success' => false,
                'message' => 'Maintance asset not found',
                'data' => ''
            ], 404);
        }

        $maintanceAsset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Maintance asset deleted',
            'data' => $maintanceAsset
        ], 200);
    }
}
