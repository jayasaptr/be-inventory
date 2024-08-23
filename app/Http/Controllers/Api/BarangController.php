<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\BarangMasuk;
use App\Models\BarangRuangan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use TCPDF; // Add this line to import the TCPDF class

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all barang masuk, barang ruangan, and barang keluar from db with pagination and search
        $pagination = $request->pagination ?? 100;
        $print = $request->print ?? false;

        // Get barang masuk
        $barangMasuk = BarangMasuk::when($request->search, function($query) use ($request) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        })->when($request->id_category, function($query) use ($request) {
            $query->where('id_category', $request->id_category);
        })->with('idKondisi', 'idCategory')->paginate($pagination);

        // Get barang ruangan
        $barangRuangan = BarangRuangan::when($request->search, function($query) use ($request) {
            $query->whereHas('idRuangan', function($query) use ($request) {
                $query->where('id_user', $request->search);
            });
        })->when($request->id_category, function($query) use ($request) {
            $query->whereHas('idBarangMasuk', function($query) use ($request) {
                $query->where('id_category', $request->id_category);
            });
        })->with('idBarangMasuk', 'idRuang', 'idUser:id,name', 'idRuangan')->paginate($pagination);

        // Get barang keluar
        $barangKeluar = BarangKeluar::when($request->search, function($query) use ($request) {
            $query->whereHas('idBarangMasuk', function($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            });
        })->when($request->id_category, function($query) use ($request) {
            $query->whereHas('idBarangMasuk', function($query) use ($request) {
                $query->where('id_category', $request->id_category);
            });
        })->with('idBarangMasuk', 'idKondisi')->paginate($pagination);

        // Merge the collections
        $barang = $barangMasuk->merge($barangRuangan);

        // Ensure all BarangMasuk items are included even if not in BarangRuangan
        $barang = $barangMasuk->concat($barang->diff($barangMasuk))->map(function($item) use ($barangMasuk, $barangRuangan, $barangKeluar) {
            $item->total_barang_tersedia = ($barangMasuk->where('id', $item->id)->first()->stock ?? 0);
            $item->total_barang_ruangan = $barangRuangan->where('id_barang_masuk', $item->id)->sum('jumlah');
            $item->total_barang_keluar = $barangKeluar->where('id_barang_masuk', $item->id)->sum('jumlah');
            return $item;
        });

        // Filter out items that are not in BarangMasuk
        $barang = $barang->filter(function($item) {
            return $item instanceof BarangMasuk;
        });

        return response()->json([
            'success' => true,
            'message' => 'List data barang',
            'data' => $barang,
        ], 200);
    }

    public function barangDanAsseet(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $category = $request->category ?? '';

        // Get barang data
        $barang = BarangMasuk::when($search, function($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        })->when($startDate, function($query) use ($startDate) {
            $query->where('tanggal_masuk', '>=', $startDate);
        })->when($endDate, function($query) use ($endDate) {
            $query->where('tanggal_masuk', '<=', $endDate);
        })->when($category, function($query) use ($category) {
            $query->whereHas('idCategory', function($query) use ($category) {
                $query->where('name', 'like', '%' . $category . '%');
            });
        })->with('idKondisi:id,nama', 'idCategory:id,name')->get();
          

        // Get asset data
        $assets = Asset::when($search, function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->when($startDate, function($query) use ($startDate) {
            $query->where('purchase_date', '>=', $startDate);
        })->when($endDate, function($query) use ($endDate) {
            $query->where('purchase_date', '<=', $endDate);
        })->when($category, function($query) use ($category) {
            $query->where('type', 'like', '%' . $category . '%');
        })->get();

        // Merge the collections
        $data = $barang->concat($assets);

        // Map the data to include only the required fields
        $data = $data->map(function($item) {
            return [
                'nama' => $item->nama ?? $item->name,
                'tanggal_pembelian' => $item->tanggal_masuk ?? $item->purchase_date,
                'jumlah' => $item->stock ?? $item->quantity,
                'category' => $item->idCategory->name ?? $item->type,
                'kondisi' => $item->idKondisi->nama ?? $item->kondisi,
                'kode' => $item->code_barang ?? $item->code,
            ];
        });

        // Paginate the merged data
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $pagination;
        $currentItems = $data->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedData = new LengthAwarePaginator($currentItems, $data->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'List data asset dan barang',
            'data' => $paginatedData,
        ], 200);
    }
}
