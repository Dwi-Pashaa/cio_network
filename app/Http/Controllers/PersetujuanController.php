<?php

namespace App\Http\Controllers;

use App\Models\Persetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersetujuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orgId = Auth::user()->organization_id;

        $persetujuans = Persetujuan::withCount('pages')
            ->where('organization_id', $orgId)
            ->orderBy('is_active', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeDoc = $persetujuans->firstWhere('is_active', true);

        if ($request->ajax() && $request->has('get_data')) {
            return response()->json([
                'status' => 'success',
                'data' => $persetujuans,
            ]);
        }

        return view('pages.persetujuan.index', compact('persetujuans', 'activeDoc'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
        ], [
            'judul.required' => 'Judul dokumen wajib diisi.',
            'konten.required' => 'Konten / isi persetujuan wajib diisi.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $orgId = Auth::user()->organization_id;
        $isActive = $request->boolean('is_active');

        // Jika belum ada dokumen sama sekali di organisasi ini, jadikan otomatis aktif
        $totalExisting = Persetujuan::where('organization_id', $orgId)->count();
        if ($totalExisting === 0) {
            $isActive = true;
        }

        DB::beginTransaction();
        try {
            // Jika dokumen baru ini diaktifkan, nonaktifkan dokumen lain di organisasi yang sama
            if ($isActive) {
                Persetujuan::where('organization_id', $orgId)->update(['is_active' => false]);
            }

            $persetujuan = Persetujuan::create([
                'judul' => $request->judul,
                'konten' => $request->konten,
                'is_active' => $isActive,
                'organization_id' => $orgId,
            ]);

            Cache::forget("persetujuan_org_{$orgId}");
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Dokumen persetujuan berhasil ditambahkan.',
                    'data' => $persetujuan,
                ]);
            }

            return redirect()->route('persetujuan.index')->with('success', 'Dokumen persetujuan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan dokumen: ' . $e->getMessage(),
                ], 500);
            }
            return back()->withInput()->with('error', 'Gagal menyimpan dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $orgId = Auth::user()->organization_id;
        $persetujuan = Persetujuan::where('organization_id', $orgId)->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $persetujuan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
        ], [
            'judul.required' => 'Judul dokumen wajib diisi.',
            'konten.required' => 'Konten / isi persetujuan wajib diisi.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $orgId = Auth::user()->organization_id;
        $persetujuan = Persetujuan::where('organization_id', $orgId)->findOrFail($id);
        $isActive = $request->boolean('is_active');

        // Jika ini adalah satu-satunya dokumen, harus tetap aktif
        $totalExisting = Persetujuan::where('organization_id', $orgId)->count();
        if ($totalExisting === 1 && !$isActive) {
            $isActive = true;
        }

        DB::beginTransaction();
        try {
            // Jika dokumen ini dijadikan aktif, nonaktifkan dokumen lain milik organisasi yang sama
            if ($isActive) {
                Persetujuan::where('organization_id', $orgId)
                    ->where('id', '!=', $id)
                    ->update(['is_active' => false]);
            }

            $persetujuan->update([
                'judul' => $request->judul,
                'konten' => $request->konten,
                'is_active' => $isActive,
            ]);

            Cache::forget("persetujuan_org_{$orgId}");
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Dokumen persetujuan berhasil diperbarui.',
                    'data' => $persetujuan,
                ]);
            }

            return redirect()->route('persetujuan.index')->with('success', 'Dokumen persetujuan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui dokumen: ' . $e->getMessage(),
                ], 500);
            }
            return back()->withInput()->with('error', 'Gagal memperbarui dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $orgId = Auth::user()->organization_id;
        $persetujuan = Persetujuan::where('organization_id', $orgId)->findOrFail($id);

        $totalDocs = Persetujuan::where('organization_id', $orgId)->count();
        if ($totalDocs <= 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat menghapus satu-satunya dokumen persetujuan yang dimiliki organisasi Anda.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $wasActive = $persetujuan->is_active;

            $persetujuan->delete();

            // Jika dokumen yang dihapus sebelumnya adalah dokumen aktif, aktifkan dokumen terbaru lainnya
            if ($wasActive) {
                $nextActive = Persetujuan::where('organization_id', $orgId)->latest()->first();
                if ($nextActive) {
                    $nextActive->update(['is_active' => true]);
                }
            }

            Cache::forget("persetujuan_org_{$orgId}");
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Dokumen persetujuan berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle a document to become the single active publication document.
     */
    public function toggleActive(Request $request, $id)
    {
        $orgId = Auth::user()->organization_id;
        $persetujuan = Persetujuan::where('organization_id', $orgId)->findOrFail($id);

        DB::beginTransaction();
        try {
            // Nonaktifkan semua dokumen lain di organisasi ini
            Persetujuan::where('organization_id', $orgId)
                ->where('id', '!=', $id)
                ->update(['is_active' => false]);

            // Jadikan dokumen ini aktif
            $persetujuan->update(['is_active' => true]);

            Cache::forget("persetujuan_org_{$orgId}");
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Dokumen '{$persetujuan->judul}' berhasil diaktifkan sebagai dokumen pendaftaran online.",
                'data' => $persetujuan,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengaktifkan dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }
}
