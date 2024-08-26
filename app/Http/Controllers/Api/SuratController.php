<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 100;
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $jenisSurat = $request->jenis_surat ?? '';

        $surats = Surat::when($startDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('tanggal_surat', [$startDate, $endDate]);
        })->when($jenisSurat, function ($query) use ($jenisSurat) {
            return $query->where('jenis_surat', $jenisSurat);
        })->paginate($pagination);

        return response()->json([
            'success' => true,
            'message' => 'List surat',
            'data' => $surats,
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
            'instansi' => 'required',
            'keterangan' => 'required',
            'jenis_surat' => 'required',
            'tanggal_surat' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
                'data' => null,
            ], 400);
        }

        $data = $request->all();

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());
            $data['image'] = $image->hashName();
        }

        $surat = Surat::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Surat created',
            'data' => $surat,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get surat by id
        $surat = Surat::find($id);

        if (!$surat) {
            return response()->json([
                'success' => false,
                'message' => 'Surat not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail surat',
            'data' => $surat,
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
        // update surat by id
        $surat = Surat::find($id);

        if (!$surat) {
            return response()->json([
                'success' => false,
                'message' => 'Surat not found',
                'data' => null,
            ], 404);
        }

        $data = $request->all();

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());
            $data['image'] = $image->hashName();
        }

        $surat->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Surat updated',
            'data' => $surat,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete surat by id
        $surat = Surat::find($id);

        if (!$surat) {
            return response()->json([
                'success' => false,
                'message' => 'Surat not found',
                'data' => null,
            ], 404);
        }

        $surat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surat deleted',
            'data' => $surat,
        ], 200);
    }
}
