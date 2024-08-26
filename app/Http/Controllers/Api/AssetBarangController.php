<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetBarangModel;
use Illuminate\Http\Request;

class AssetBarangController extends Controller
{
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $category = $request->category ?? '';
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;
        $kondisi = $request->kondisi ?? '';

        $query = AssetBarangModel::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->where('category', 'like', '%' . $category . '%');
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        $data = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List asset barang',
            'data' => $data
        ], 200);
    }
}
