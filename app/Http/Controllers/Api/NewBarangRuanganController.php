<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewBarangRuangan;
use Illuminate\Http\Request;

class NewBarangRuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $status = $request->status ?? '';
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;

        $query = NewBarangRuangan::with('idAssetBarang', 'idRuangan');

        if ($search) {
            $query->where('id_user', 'like', '%' . $search . '%');
        }

        if ($status) {
            $query->where('status', 'like', '%' . $status . '%');
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $data,
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

        $barangRuangan = NewBarangRuangan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Barang ruangan berhasil ditambahkan',
            'data' => $barangRuangan,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = NewBarangRuangan::with('idAssetBarang')->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Barang ruangan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail barang ruangan',
            'data' => $data,
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

        $barangRuangan = NewBarangRuangan::find($id);

        if (!$barangRuangan) {
            return response()->json([
                'success' => false,
                'message' => 'Barang ruangan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $barangRuangan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Barang ruangan berhasil diupdate',
            'data' => $barangRuangan,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barangRuangan = NewBarangRuangan::find($id);

        if (!$barangRuangan) {
            return response()->json([
                'success' => false,
                'message' => 'Barang ruangan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $barangRuangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang ruangan berhasil dihapus',
            'data' => $barangRuangan,
        ], 200);
    }
}
