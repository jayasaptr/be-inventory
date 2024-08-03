<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all data from ruangan table with paginate and search 
        $paginate = $request->input('paginate') ?? 100;
        $search = $request->search ?? '';

        $ruangan = Ruangan::where('nama', 'like', "%$search%")->with('idUser')->paginate($paginate);

        return response()->json([
            'success' => true,
            'message' => 'List Ruangan',
            'data' => $ruangan
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
        // Set validation
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'id_user' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // Store to db
        $ruangan = Ruangan::create([
            'nama' => $request->nama,
            'id_user' => $request->id_user,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan created',
            'data' => $ruangan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Get ruangan by id
        $ruangan = Ruangan::find($id)->with('idUser')->first();

        if (!$ruangan) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ruangan found',
            'data' => $ruangan
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
        // find ruangan by id
        $ruangan = Ruangan::find($id);

        if (!$ruangan) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan not found'
            ], 404);
        }

        // update ruangan
        $ruangan->update([
            'nama' => $request->nama ?? $ruangan->nama,
            'id_user' => $request->id_user ?? $ruangan->id_user,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan updated',
            'data' => $ruangan
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // find ruangan by id
        $ruangan = Ruangan::find($id);

        if (!$ruangan) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan not found'
            ], 404);
        }

        // delete ruangan
        $ruangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ruangan deleted',
            'data' => $ruangan
        ], 200);
    }

    // get ruangan by user id
    public function getRuanganByUser(Request $request)
    {
        $id_user = $request->id_user;

        $ruangan = Ruangan::where('id_user', $id_user)->get();

        return response()->json([
            'success' => true,
            'message' => 'List Ruangan by User',
            'data' => $ruangan
        ], 200);
    }
}
