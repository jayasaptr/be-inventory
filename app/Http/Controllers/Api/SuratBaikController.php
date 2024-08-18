<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\SuratBaik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratBaikController extends Controller
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

        $query = SuratBaik::query();

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

        $suratBaik = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List surat baik',
            'data' => $suratBaik,
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
            'nama_siswa' => 'required',
            'nama_orang_tua' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
        ]);

        

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 422);
        }

        $noSurat = 'w.o/6/PP.03.2/' . $request->nomor_surat . '/' . date('Y');

        $suratBaik = SuratBaik::create([
            'nomor_surat' => $request->nomor_surat,
            'nama_siswa' => $request->nama_siswa,
            'nama_orang_tua' => $request->nama_orang_tua,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'status' => "pending",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surat baik berhasil dibuat',
            'data' => $suratBaik,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $suratBaik = SuratBaik::find($id);

        if (!$suratBaik) {
            return response()->json([
                'success' => false,
                'message' => 'Surat baik tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail surat baik',
            'data' => $suratBaik,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $suratBaik = SuratBaik::find($id);

        if (!$suratBaik) {
            return response()->json([
                'success' => false,
                'message' => 'Surat baik tidak ditemukan',
            ], 404);
        }

        $noSurat = 'w.o/6/PP.03.2/' . $request->nomor_surat . '/' . date('Y');

        $suratBaik->update([
            'nomor_surat' => $request->nomor_surat ?? $suratBaik->nomor_surat,
            'nama_siswa' => $request->nama_siswa ?? $suratBaik->nama_siswa,
            'nama_orang_tua' => $request->nama_orang_tua ?? $suratBaik->nama_orang_tua,
            'tempat_lahir' => $request->tempat_lahir ?? $suratBaik->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir ?? $suratBaik->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin ?? $suratBaik->jenis_kelamin,
            'alamat' => $request->alamat ?? $suratBaik->alamat,
            'status' => $request->status ?? $suratBaik->status,
        ]);

        if ($request->status == 'approved') {
            Surat::create([
                'nomor_surat' => $suratBaik->nomor_surat,
                'instansi' => 'Pondok Pesantren Darul Istiqomah',
                'keterangan' => 'Surat berkelakuan baik ' . $suratBaik->nama_siswa,
                'jenis_surat' => 'surat keluar',
                'tanggal_surat' => date('Y-m-d'),
            ]);
        } 

        return response()->json([
            'success' => true,
            'message' => 'Surat baik berhasil diupdate',
            'data' => $suratBaik,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $suratBaik = SuratBaik::find($id);

        if (!$suratBaik) {
            return response()->json([
                'success' => false,
                'message' => 'Surat baik tidak ditemukan',
            ], 404);
        }

        $suratBaik->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surat baik berhasil dihapus',
        ], 200);
    }
}
