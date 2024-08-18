<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\BarangRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangRuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //  get all barang ruangan from db with pagination and search by id user jika tidak ada search query tampilkan semua
        $pagination = $request->pagination ?? 100;
        $barangRuangan = BarangRuangan::when($request->search, function($query) use ($request) {
            $query->whereHas('idRuangan', function($query) use ($request) {
                $query->where('id_user', $request->search);
            });
        })->with('idBarangMasuk', 'idRuang', 'idUser:id,name', 'idRuangan')->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List barang ruangan',
            'data' => $barangRuangan,
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
        // validate request
        $validation = Validator::make($request->all(), [
            'id_barang_masuk' => 'required|integer',
            'id_ruangan' => 'required|integer',
            'jumlah' => 'required|integer',
            'keterangan' => 'required|string',
            'id_user' => 'required|integer',
            'tanggal' => 'required|date',
            'status' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors(),
            ], 422);
        }

        // cek stock di model barangmasuk
        $barangMasuk = BarangMasuk::find($request->id_barang_masuk);
        if ($barangMasuk->stock < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stock barang tidak mencukupi',
            ], 400);
        }

        
        // create barang ruangan
        $barangRuangan = BarangRuangan::create([
            'id_barang_masuk' => $request->id_barang_masuk,
            'id_ruangan' => $request->id_ruangan,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'id_user' => $request->user()->id,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
        ]);

        // update stock barang masuk
        $barangMasuk->update([
            'stock' => $barangMasuk->stock - $request->jumlah,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang ruangan created',
            'data' => $barangRuangan,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get barang ruangan by id
        $barangRuangan = BarangRuangan::with('idBarangMasuk', 'idRuang', 'idUser:id,name')->find($id);

        if (!$barangRuangan) {
            return response()->json([
                'success' => false,
                'message' => 'Barang ruangan not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang ruangan found',
            'data' => $barangRuangan,
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
        // update barang ruangan
        $barangRuangan = BarangRuangan::find($id);

        if (!$barangRuangan) {
            return response()->json([
                'success' => false,
                'message' => 'Barang ruangan not found',
            ], 404);
        }

        $barangRuangan->update([
            'id_barang_masuk' => $request->id_barang_masuk ?? $barangRuangan->id_barang_masuk,
            'id_ruangan' => $request->id_ruangan ?? $barangRuangan->id_ruangan,
            'jumlah' => $request->jumlah ?? $barangRuangan->jumlah,
            'keterangan' => $request->keterangan ?? $barangRuangan->keterangan,
            'id_user' => $request->id_user ?? $barangRuangan->id_user,
            'tanggal' => $request->tanggal ?? $barangRuangan->tanggal,
            'status' => $request->status ?? $barangRuangan->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang ruangan updated',
            'data' => $barangRuangan,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete barang ruangan
        $barangRuangan = BarangRuangan::find($id);

        if (!$barangRuangan) {
            return response()->json([
                'success' => false,
                'message' => 'Barang ruangan not found',
            ], 404);
        }

        $barangRuangan->delete();

        // update stock barang masuk
        $barangMasuk = BarangMasuk::find($barangRuangan->id_barang_masuk);

        $barangMasuk->update([
            'stock' => $barangMasuk->stock + $barangRuangan->jumlah,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang ruangan deleted',
        ], 200);
    }

    public function reportBarangRuangan(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $status = $request->status ?? '';
        $idUser = $request->id_user ?? '';

        $query = BarangRuangan::query()->with(['idBarangMasuk' => function($query) {
            $query->select('id', 'nama', 'merk', 'id_category', 'jumlah', 'satuan', 'harga', 'keterangan', 'id_kondisi', 'tanggal_masuk')
                  ->with('idCategory', 'idKondisi');
        }, 'idUser:id,name', 'idRuangan']);

        if ($search) {
            $query->whereHas('idUser', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($idUser) {
            $query->where('id_user', $idUser)
                  ->where('jumlah', '!=', 0);
        }

        $barangRuangan = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'data' => $barangRuangan
        ], 200);
    }
}
