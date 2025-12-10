<?php

namespace App\Http\Controllers;

use App\Http\Requests\AspirasiUpdateRequest;
use App\Http\Requests\AspirasiStoreRequest;
use App\Http\Resources\AspirasiResource;
use App\Models\AspirasiModel;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    /**
     * RIWAYAT KIRIMAN SAYA (API)
     */
    public function myHistory(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $data = AspirasiModel::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        return AspirasiResource::collection($data);
    }
    /**
     * LIST ASPIRASI (API)
     */
    public function index()
    {
        $data = AspirasiModel::with('user')->latest()->paginate(10);
        return AspirasiResource::collection($data);
    }

    /**
     * LIST ASPIRASI UNTUK BLADE (WEB)
     */
    public function indexView()
    {
        $aspirasi = AspirasiModel::orderBy('created_at', 'DESC')->get();
        return view('aspirasi.index', compact('aspirasi'));
    }

    /**
     * STORE
     */
    public function store(AspirasiStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id; // otomatis ambil dari token

        // Jika created_at tidak dikirim, gunakan waktu sekarang
        if (!isset($data['created_at'])) {
            $data['created_at'] = now();
        }

        $aspirasi = AspirasiModel::create($data);

        return response()->json([
            'message' => 'Berhasil membuat aspirasi',
            'data' => $aspirasi
        ], 201);
    }


    /**
     * SHOW DETAIL
     */
    public function show($id)
    {
        $aspiration = AspirasiModel::with('user')->findOrFail($id);
        return new AspirasiResource($aspiration);
    }

    /**
     * UPDATE
     */
    public function update(AspirasiUpdateRequest $request, $id)
    {
        $aspiration = AspirasiModel::findOrFail($id);
        $aspiration->update($request->validated());
        $aspiration->load('user');

        return new AspirasiResource($aspiration);
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $aspiration = AspirasiModel::findOrFail($id);
        $aspiration->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Aspirasi deleted successfully'
        ]);
    }

    /**
     * LIST ASPIRASI BULAN INI
     */
    public function thisMonth()
    {
        $data = AspirasiModel::with('user')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();

        return AspirasiResource::collection($data);
    }
}
