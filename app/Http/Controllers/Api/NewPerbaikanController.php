<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewPerbaikanBarang;
use Illuminate\Http\Request;

class NewPerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $status = $request->status ?? '';
        $category = $request->category ?? '';

        $query = NewPerbaikanBarang::with('idAssetBarang', 'idUser');

        if ($category) {
            $query->whereHas('idAssetBarang', function($query) use ($category) {
                $query->where('category', 'like', '%' . $category . '%');
            });
        }

        if ($search) {
            $query->where('id_user', 'like', '%' . $search . '%');
        }

        if ($status) {
            $query->where('status', 'like', '%' . $status . '%');
        }

        $data = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List perbaikan barang',
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

        // upload file
        if ($request->hasFile('kwitansi')) {
            $kwitansi = $request->file('kwitansi');
            $kwitansi->storeAs('public/kwitansi', $kwitansi->hashName());
            $data['kwitansi'] = $kwitansi->hashName();
        }

        $perbaikanBarang = NewPerbaikanBarang::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Perbaikan barang berhasil ditambahkan',
            'data' => $perbaikanBarang
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perbaikanBarang = NewPerbaikanBarang::with('idAssetBarang')->find($id);

        if (!$perbaikanBarang) {
            return response()->json([
                'success' => false,
                'message' => 'Perbaikan barang tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail perbaikan barang',
            'data' => $perbaikanBarang
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $perbaikanBarang = NewPerbaikanBarang::find($id);

        if (!$perbaikanBarang) {
            return response()->json([
                'success' => false,
                'message' => 'Perbaikan barang tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Edit perbaikan barang',
            'data' => $perbaikanBarang
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $perbaikanBarang = NewPerbaikanBarang::find($id);

        if (!$perbaikanBarang) {
            return response()->json([
                'success' => false,
                'message' => 'Perbaikan barang tidak ditemukan',
                'data' => null
            ], 404);
        }

        $perbaikanBarang->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Perbaikan barang berhasil diupdate',
            'data' => $perbaikanBarang
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $perbaikanBarang = NewPerbaikanBarang::find($id);

        if (!$perbaikanBarang) {
            return response()->json([
                'success' => false,
                'message' => 'Perbaikan barang tidak ditemukan',
                'data' => null
            ], 404);
        }

        $perbaikanBarang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perbaikan barang berhasil dihapus',
            'data' => $perbaikanBarang
        ], 200);
    }
}
