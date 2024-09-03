<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarangMasuk = \App\Models\BarangMasukModel::sum('quantity');
        $totalBarangKeluar = \App\Models\AssetBarangKeluar::where('category', 'like', '%barang%')->sum('jumlah');
        $totalSuratMasuk = \App\Models\Surat::where('jenis_surat', 'surat masuk')->count();
        $totalSuratKeluar = \App\Models\Surat::where('jenis_surat', 'surat keluar')->count();
        $totalBiayaPerbaikan = \App\Models\NewPerbaikanBarang::where('status', 'disetuji')->sum('biaya');
        return response()->json([
            'success' => true,
            'message' => 'Dashboard data',
            'data' => [
                'total_barang_masuk' => $totalBarangMasuk,
                'total_barang_keluar' => $totalBarangKeluar,
                'total_surat_masuk' => $totalSuratMasuk,
                'total_surat_keluar' => $totalSuratKeluar,
                'total_biaya_perbaikan' => $totalBiayaPerbaikan,
            ],
        ], 200);
    }
}
