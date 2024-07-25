<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kondisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KondisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all data from kondisi table with paginate and search
        $paginate = $request->input('paginate') ?? 100;
        $search = $request->search ?? '';

        $kondisi = Kondisi::where('nama', 'like', "%$search%")->paginate($paginate);

        return response()->json([
            'success' => true,
            'message' => 'List Kondisi',
            'data' => $kondisi
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
        //  Set validation
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // Store to db
        $kondisi = Kondisi::create([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kondisi Created',
            'data' => $kondisi
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find kondisi by id
        $kondisi = Kondisi::find($id);

        if (!$kondisi) {
            return response()->json([
                'success' => false,
                'message' => 'Kondisi not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kondisi found',
            'data' => $kondisi
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
        // Update kondisi

        $kondisi = Kondisi::find($id);

        if (!$kondisi) {
            return response()->json([
                'success' => false,
                'message' => 'Kondisi not found'
            ], 404);
        }

        $kondisi->nama = $request->nama;

        $kondisi->save();

        return response()->json([
            'success' => true,
            'message' => 'Kondisi updated',
            'data' => $kondisi
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find kondisi by id

        $kondisi = Kondisi::find($id);

        if (!$kondisi) {
            return response()->json([
                'success' => false,
                'message' => 'Kondisi not found'
            ], 404);
        }

        $kondisi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kondisi deleted'
        ], 200);
    }
}
