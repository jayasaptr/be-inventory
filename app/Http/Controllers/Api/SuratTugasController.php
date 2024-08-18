<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\SuratTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $status = $request->status ?? '';

        $query = SuratTugas::query()->with('userId');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $suratTugas = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List surat tugas',
            'data' => $suratTugas,
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
            'nomor_surat' => 'required',
            'menimbang' => 'required',
            'dasar' => 'required',
            'user_id' => 'required',
            'keterangan' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $noSurat = 'w.o/6/PP.03.2/' . $request->nomor_surat . '/' . date('Y');

        $suratTugas = SuratTugas::create([
            'nomor_surat' => $request->nomor_surat,
            'menimbang' => $request->menimbang,
            'dasar' => $request->dasar,
            'user_id' => $request->user_id,
            'keterangan' => $request->keterangan,
            'status' => "pending",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surat tugas berhasil dibuat',
            'data' => $suratTugas,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $suratTugas = SuratTugas::find($id);

        if (!$suratTugas) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tugas not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail surat tugas',
            'data' => $suratTugas,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $suratTugas = SuratTugas::find($id);

        if (!$suratTugas) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tugas not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail surat tugas',
            'data' => $suratTugas,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $suratTugas = SuratTugas::find($id);

        if (!$suratTugas) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tugas not found',
                'data' => null,
            ], 404);
        }

        $noSurat = 'w.o/6/PP.03.2/' . $request->nomor_surat . '/' . date('Y');

        $suratTugas->update([
            'nomor_surat' => $request->nomor_surat ?? $suratTugas->nomor_surat,
            'menimbang' => $request->menimbang ?? $suratTugas->menimbang,
            'dasar' => $request->dasar ?? $suratTugas->dasar,
            'user_id' => $request->user_id ?? $suratTugas->user_id,
            'keterangan' => $request->keterangan ?? $suratTugas->keterangan,
            'status' => $request->status ?? $suratTugas->status,
        ]);

        if ($request->status == 'approved') {
            Surat::create([
                'nomor_surat' => $suratTugas->nomor_surat,
                'instansi' => 'Pondok Pesantren Darul Istiqomah',
                'keterangan' =>  'Surat tugas ' . $suratTugas->userId->name,
                'jenis_surat' => 'surat keluar',
                'tanggal_surat' => date('Y-m-d'),
            ]);
        } 

        return response()->json([
            'success' => true,
            'message' => 'Surat tugas updated',
            'data' => $suratTugas,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $suratTugas = SuratTugas::find($id);

        if (!$suratTugas) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tugas not found',
                'data' => null,
            ], 404);
        }

        $suratTugas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surat tugas deleted',
            'data' => $suratTugas,
        ], 200);
    }
}
