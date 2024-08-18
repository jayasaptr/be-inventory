<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\BarangRuangan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
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

        if ($print) {
            $pdf = new TCPDF(); // Use TCPDF class directly

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
            $html = '<table border="1" cellpadding="4">
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
                                <th>Total Barang Tersedia</th>
                                <th>Total Barang Ruangan</th>
                                <th>Total Barang Keluar</th>
                            </tr>
                        </thead>
                        <tbody>';

            // Fill data
            foreach ($barang as $item) {
                $html .= '<tr>
                            <td>' . $item->id . '</td>
                            <td>' . $item->nama . '</td>
                            <td>' . $item->merk . '</td>
                            <td>' . $item->idCategory->name . '</td>
                            <td>' . $item->idKondisi->nama . '</td>
                            <td>' . $item->jumlah . '</td>
                            <td>' . $item->harga * $item->jumlah . '</td>
                            <td>' . $item->tanggal_masuk . '</td>
                            <td>' . $item->total_barang_tersedia . '</td>
                            <td>' . $item->total_barang_ruangan . '</td>
                            <td>' . $item->total_barang_keluar . '</td>
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
            $filename = 'report_barang.pdf';
            $pdf->Output(public_path($filename), 'F');

            // Return the URL as a response
            return response()->json([
                'success' => true,
                'url' => url($filename)
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'List data barang',
            'data' => $barang,
        ], 200);
    }
}
