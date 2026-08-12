<?php

namespace App\Http\Controllers;

use App\Models\Persetujuan;
use Illuminate\Http\Request;

class PersetujuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $persetujuan = Persetujuan::where('organization_id', auth()->user()->organization_id)->first();
        return view('pages.persetujuan.index', compact('persetujuan'));
    }

    /**
     * Store a newly created or update resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
        ]);

        Persetujuan::updateOrCreate(
            ['organization_id' => auth()->user()->organization_id],
            [
                'judul' => $request->judul,
                'konten' => $request->konten,
                'is_active' => $request->has('is_active') ? true : false,
            ]
        );

        return redirect()->route('persetujuan.index')->with('success', 'Konten Persetujuan berhasil diperbarui.');
    }
}

