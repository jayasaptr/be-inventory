<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $date = $request->date ?? '';
        $print = $request->print ?? false;
        $idKondisi = $request->id_kondisi ?? '';

        $query = BarangMasuk::query()->with('idCategory', 'idKondisi');

        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('merk', 'like', '%' . $search . '%');
        }

        if ($date) {
            $query->whereDate('tanggal_masuk', $date);
        }

        if ($idKondisi) {
            $query->where('id_kondisi', $idKondisi);
        }

        $barangMasuk = $query->paginate($pagination);

        if ($print) {
            // Logic for generating the report to PDF with a nice letterhead
            $filename = 'report_barang_masuk.pdf';
            $pdf = new \TCPDF();

            // Set document information
            $pdf->SetCreator('My Application');
            $pdf->SetAuthor('My Application');
            $pdf->SetTitle('Report Barang Masuk');
            $pdf->SetSubject('Report Barang Masuk');
            $pdf->SetKeywords('TCPDF, PDF, report, barang masuk');

            // Set default header data
            $pdf->SetHeaderData('', 0, '', '', array(255,255,255), array(255,255,255));

            // Set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // Set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // Set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // Set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // Set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // Add a page
            $pdf->AddPage();

            // Set font
            $pdf->SetFont('helvetica', '', 12);

            // Set the header
            $html = '<h3>Laporan Barang Masuk</h3>';
            $html .= '<table border="1" cellpadding="4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Merk</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>';

            // Fill data
            foreach ($barangMasuk as $item) {
                $html .= '<tr>
                            <td>' . $item->id . '</td>
                            <td>' . $item->nama . '</td>
                            <td>' . $item->merk . '</td>
                            <td>' . $item->idCategory->name . '</td>
                            <td>' . $item->idKondisi->nama . '</td>
                            <td>' . $item->jumlah . '</td>
                            <td>' . $item->harga * $item->jumlah . '</td>
                            <td>' . $item->tanggal_masuk . '</td>
                          </tr>';
            }

            $html .= '</tbody></table>';

            // Print text using writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            // Add signature at the bottom of the page
            $pdf->SetY(-80); // Position at 50 mm from bottom
            $signatureHtml = '<table border="0" cellpadding="4">
                                <tr>
                                    <td></td>
                                    <td style="text-align: center;">
                                        Kepala Sekolah<br><br><br><br>
                                        <u>Nama Kepala Sekolah</u><br>
                                        NIP. 123456789
                                    </td>
                                </tr>
                              </table>';
            $pdf->writeHTML($signatureHtml, true, false, true, false, '');

            // Close and output PDF document
            $pdf->Output(public_path($filename), 'F');

            // Return the URL as a response
            return response()->json([
                'success' => true,
                'url' => url($filename)
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $barangMasuk
        ], 200);
    }
}