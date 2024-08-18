<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\SuratAktif as ModelsSuratAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratAktif extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get all surat aktif from db with pagination and filter by start date, end date, and status
        $pagination = $request->pagination ?? 100;
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $status = $request->status ?? '';

        $query = ModelsSuratAktif::query();

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

        $suratAktif = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List surat aktif',
            'data' => $suratAktif,
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
            'no_surat' => 'required',
            'nama_siswa' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'kelas' => 'required',
            'tahun_ajaran' => 'required',
            'alamat' => 'required',
            'nisn' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
                'data' => null,
            ], 400);
        }

        // no surat buat otomatis dengan format w.o/6/PP.03.2/447/2022 untuk 447 itu menunjukan no diambil dari inputan dan 2022 menunjukan tahun
        $noSurat = 'w.o/6/PP.03.2/' . $request->no_surat . '/' . date('Y');
        
        // create surat aktif
        $suratAktif = ModelsSuratAktif::create([
            'no_surat' => $request->no_surat,
            'nama_siswa' => $request->nama_siswa,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'kelas' => $request->kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
            'alamat' => $request->alamat,
            'nisn' => $request->nisn,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surat aktif created',
            'data' => $suratAktif,
        ], 201);  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get surat aktif by id
        $suratAktif = ModelsSuratAktif::find($id);

        if ($suratAktif) {
            return response()->json([
                'success' => true,
                'message' => 'Detail surat aktif',
                'data' => $suratAktif,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Surat aktif not found',
            'data' => null,
        ], 404);
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
        // update surat aktif by id
        $suratAktif = ModelsSuratAktif::find($id);

        if (!$suratAktif) {
            return response()->json([
                'success' => false,
                'message' => 'Surat aktif not found',
                'data' => null,
            ], 404);
        }

        $noSurat = 'w.o/6/PP.03.2/' . $request->no_surat . '/' . date('Y');

        $suratAktif->update([
            'no_surat' => $request->no_surat ?? $suratAktif->no_surat,
            'nama_siswa' => $request->nama_siswa ?? $suratAktif->nama_siswa,
            'tempat_lahir' => $request->tempat_lahir ?? $suratAktif->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir ?? $suratAktif->tanggal_lahir,
            'kelas' => $request->kelas ?? $suratAktif->kelas,
            'tahun_ajaran' => $request->tahun_ajaran ?? $suratAktif->tahun_ajaran,
            'alamat' => $request->alamat ?? $suratAktif->alamat,
            'status' => $request->status ?? $suratAktif->status,
            'nisn' => $request->nisn ?? $suratAktif->nisn,
        ]);

        // jika status di update menjadi approved maka akan membuat surat aktif menjadi surat aktif
        if ($request->status == 'approved') {
            Surat::create([
                'nomor_surat' => $suratAktif->no_surat,
                'instansi' => 'Pondok Pesantren Darul Istiqomah',
                'keterangan' =>  $suratAktif->nama_siswa . ' ' . $suratAktif->kelas . ' ' . $suratAktif->tahun_ajaran,
                'jenis_surat' => 'surat keluar',
                'tanggal_surat' => date('Y-m-d'),
            ]);
        } 

        return response()->json([
            'success' => true,
            'message' => 'Surat aktif updated',
            'data' => $suratAktif,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete surat aktif by id
        $suratAktif = ModelsSuratAktif::find($id);

        if ($suratAktif) {
            $suratAktif->delete();

            return response()->json([
                'success' => true,
                'message' => 'Surat aktif deleted',
                'data' => $suratAktif,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Surat aktif not found',
            'data' => null,
        ], 404);
    }
}
