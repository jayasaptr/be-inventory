<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use TCPDF;

class CustomTCPDF extends TCPDF {
    protected $logo;
    protected $title;
    protected $office;
    protected $email;
    protected $web;
    protected $phone;

    public function setHeaderData($ln = '', $lw = 0, $ht = '', $hs = '', $tc = array(0,0,0), $lc = array(0,0,0)) {
        $this->logo = $ln;
        $this->title = $ht;
        $this->office = $hs;
        $this->email = $tc[0];
        $this->web = $tc[1];
        $this->phone = $tc[2];
    }

    public function Header() {
        $this->SetFont('times', '');
        $this->writeHTMLCell(50, 0, 7, 5, $this->logo, 0, 0, false, true, 'C', false);
        $this->writeHTMLCell(143, 0, 57, 5, $this->title, 0, 1);
        $this->writeHTMLCell(143, 0, 57, 13, $this->office, 0, 1);
        $this->writeHTMLCell(143, 0, 57, 20, $this->email, 0, 0);
        $this->writeHTMLCell(72, 0, 57, 29, $this->web, 0, 1);
        $this->writeHTMLCell(143, 0, 57, 27.5, $this->phone, 0, 1);
    }
}

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data barang masuk dengan relasi kondisi dan pagination

        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';

        $barangMasuk = BarangMasuk::with('idKondisi', 'idCategory')
            ->where('nama', 'like', "%$search%")
            ->paginate($pagination);
        
        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'List barang masuk',
            'data' => $barangMasuk
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
        // Validasi data yang diterima

        $validation = Validator::make($request->all(), [
            'nama' => 'required',
            'merk' => 'required',
            'id_category' => 'required',
            'jumlah' => 'required',
            'satuan' => 'required',
            'harga' => 'required',
            'keterangan' => 'required',
            'id_kondisi' => 'required',
            'tanggal_masuk' => 'required',
        ]);

        // Jika validasi gagal, kirim response error

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        // Membuat data barang masuk baru

        $barangMasuk = BarangMasuk::create([
            'nama' => $request->nama,
            'merk' => $request->merk,
            'id_category' => $request->id_category,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'keterangan' => $request->keterangan,
            'id_kondisi' => $request->id_kondisi,
            'tanggal_masuk' => $request->tanggal_masuk,
            'stock' => $request->jumlah,
        ]);

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil ditambahkan',
            'data' => $barangMasuk
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Mencari data barang masuk berdasarkan id

        $barangMasuk = BarangMasuk::with('idKondisi')->find($id);

        // Jika data barang masuk tidak ditemukan

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan'
            ], 404);
        }

        // Mengembalikan data barang masuk dalam bentuk json

        return response()->json([
            'success' => true,
            'message' => 'Detail barang masuk',
            'data' => $barangMasuk
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
        // Mencari data barang masuk berdasarkan id

        $barangMasuk = BarangMasuk::find($id);

        // Jika data barang masuk tidak ditemukan

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan'
            ], 404);
        }

        // Mengupdate data barang masuk

        $barangMasuk->update([
            'nama' => $request->nama ?? $barangMasuk->nama,
            'merk' => $request->merk ?? $barangMasuk->merk,
            'id_category' => $request->id_category ?? $barangMasuk->id_category,
            'jumlah' => $request->jumlah ?? $barangMasuk->jumlah,
            'satuan' => $request->satuan ?? $barangMasuk->satuan,
            'harga' => $request->harga ?? $barangMasuk->harga,
            'keterangan' => $request->keterangan ?? $barangMasuk->keterangan,
            'id_kondisi' => $request->id_kondisi ?? $barangMasuk->id_kondisi,
            'tanggal_masuk' => $request->tanggal_masuk ?? $barangMasuk->tanggal_masuk,
            'stock' => $request->jumlah ?? $barangMasuk->stock,
        ]);

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil diupdate',
            'data' => $barangMasuk
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Mencari data barang masuk berdasarkan id

        $barangMasuk = BarangMasuk::find($id);

        // Jika data barang masuk tidak ditemukan

        if (!$barangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Barang masuk tidak ditemukan'
            ], 404);
        }

        // Menghapus data barang masuk

        $barangMasuk->delete();

        // Mengembalikan response sukses

        return response()->json([
            'success' => true,
            'message' => 'Barang masuk berhasil dihapus',
        ], 200);
    }

    public function reportBarangMasuk(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $search = $request->search ?? '';
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $print = $request->print ?? false;
        $idKondisi = $request->id_kondisi ?? '';

        $query = BarangMasuk::query()->with('idCategory', 'idKondisi');

        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('merk', 'like', '%' . $search . '%');
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_masuk', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('tanggal_masuk', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('tanggal_masuk', '<=', $endDate);
        }

        if ($idKondisi) {
            $query->where('id_kondisi', $idKondisi);
        }

        $barangMasuk = $query->paginate($pagination);

        return response()->json([
            'success' => true,
            'data' => $barangMasuk
        ], 200);
    }
}