<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get all perbaikan from db with pagination and search by id user jika tidak ada search query tampilkan semua
        $pagination = $request->pagination ?? 100;
        $perbaikan = Perbaikan::when($request->search, function($query) use ($request) {
            $query->where('id_user', $request->search);
        })->with('idBarangMasuk', 'idUser:id,name')->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List perbaikan',
            'data' => $perbaikan,
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
            'tanggal_perbaikan' => 'required|date',
            'biaya' => 'required|integer',
            'keterangan' => 'required|string',
            'status' => 'required|string',
            'jumlah' => 'required|integer',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors(),
            ], 422);
        }

        // upload file
        $kwitansi = $request->file('kwitansi');
        $kwitansi->storeAs('public/kwitansi', $kwitansi->hashName());

        // create perbaikan
        $perbaikan = Perbaikan::create([
            'id_barang_masuk' => $request->id_barang_masuk,
            'tanggal_perbaikan' => $request->tanggal_perbaikan,
            'tanggal_selesai' => $request->tanggal_selesai,
            'biaya' => $request->biaya,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'kwitansi' => $kwitansi->hashName(),
            'jumlah' => $request->jumlah,
            'id_user' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perbaikan created',
            'data' => $perbaikan,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get perbaikan by id
        $perbaikan = Perbaikan::with('idBarangMasuk:id,nama_barang', 'idUser:id,name')
            ->where('id', $id)
            ->first();

        if (!$perbaikan) {
            return response()->json([
                'success' => false,
                'message' => 'Perbaikan not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail perbaikan',
            'data' => $perbaikan,
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
        // cerate perbaikan
        $perbaikan = Perbaikan::find($id);

        if (!$perbaikan) {
            return response()->json([
                'success' => false,
                'message' => 'Perbaikan not found',
            ], 404);
        }

        // cek apakah perbaikan yang di edit mengupload kwitansi baru
        if ($request->hasFile('kwitansi')) {
            // upload file
            $kwitansi = $request->file('kwitansi');
            $kwitansi->storeAs('public/kwitansi', $kwitansi->hashName());

            // update perbaikan
            $perbaikan->update([
                'id_barang_masuk' => $request->id_barang_masuk ?? $perbaikan->id_barang_masuk,
                'tanggal_perbaikan' => $request->tanggal_perbaikan ?? $perbaikan->tanggal_perbaikan,
                'tanggal_selesai' => $request->tanggal_selesai ?? $perbaikan->tanggal_selesai,
                'biaya' => $request->biaya ?? $perbaikan->biaya,
                'keterangan' => $request->keterangan ?? $perbaikan->keterangan,
                'status' => $request->status ?? $perbaikan->status,
                'kwitansi' => $kwitansi->hashName(),
                'jumlah' => $request->jumlah ?? $perbaikan->jumlah,
            ]);
        } else {
            // update perbaikan
            $perbaikan->update([
                'id_barang_masuk' => $request->id_barang_masuk ?? $perbaikan->id_barang_masuk,
                'tanggal_perbaikan' => $request->tanggal_perbaikan ?? $perbaikan->tanggal_perbaikan,
                'tanggal_selesai' => $request->tanggal_selesai ?? $perbaikan->tanggal_selesai,
                'biaya' => $request->biaya ?? $perbaikan->biaya,
                'keterangan' => $request->keterangan ?? $perbaikan->keterangan,
                'status' => $request->status ?? $perbaikan->status,
                'jumlah' => $request->jumlah ?? $perbaikan->jumlah,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Perbaikan updated',
            'data' => $perbaikan,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete perbaikan
        $perbaikan = Perbaikan::find($id);
        
        if (!$perbaikan) {
            return response()->json([
                'success' => false,
                'message' => 'Perbaikan not found',
            ], 404);
        }

        $perbaikan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perbaikan deleted',
        ], 200);
    }

    public function reportPengajuanPerbaikan(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $status = $request->status ?? '';
        $idUser = $request->id_user ?? '';

        $query = Perbaikan::query()->with(['idBarangMasuk' => function($query) {
            $query->select('id', 'nama', 'merk', 'id_category', 'jumlah', 'satuan', 'harga', 'keterangan', 'id_kondisi', 'tanggal_masuk')
                  ->with('idCategory', 'idKondisi');
        }, 'idUser:id,name']);

        if ($search) {
            $query->whereHas('idUser', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_perbaikan', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('tanggal_perbaikan', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('tanggal_perbaikan', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($idUser) {
            $query->where('id_user', $idUser);
        }

        $perbaikan = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'data' => $perbaikan
        ], 200);
    }
}
