<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\ProsedurChatTemplate;
use Illuminate\Http\Request;

class ProsedurChatTemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */
    public function index()
    {
        $templates = ProsedurChatTemplate::all();
        return view('pages.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        $allCodes = [
            'validator_level_1' => 'validator_level_1 (Level 1 - Admin)',
            'validator_level_2' => 'validator_level_2 (Level 2 - OLT)',
            'validator_level_3' => 'validator_level_3 (Level 3 - ONC)',
            'validator_level_4' => 'validator_level_4 (Level 4 - Mix Radius)',
            'technician_approved' => 'technician_approved (Disetujui - Teknisi)',
            'technician_rejected' => 'technician_rejected (Ditolak - Teknisi)',
        ];

        // Ambil kode yang sudah digunakan di database
        $existingCodes = ProsedurChatTemplate::pluck('code')->toArray();

        // Filter kode yang belum digunakan
        $availableCodes = array_diff_key($allCodes, array_flip($existingCodes));

        return view('pages.templates.create', compact('availableCodes'));
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|alpha_dash|unique:prosedur_chat_templates,code|in:validator_level_1,validator_level_2,validator_level_3,validator_level_4,technician_approved,technician_rejected',
            'template'    => 'required|string',
            'description' => 'nullable|string',
        ]);

        $template = ProsedurChatTemplate::create([
            'name'        => $request->input('name'),
            'code'        => $request->input('code'),
            'template'    => $request->input('template'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('prosedur.templates.index')
            ->with('success', 'Template chat "' . $template->name . '" berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit($id)
    {
        $template = ProsedurChatTemplate::findOrFail($id);
        return view('pages.templates.edit', compact('template'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'template'    => 'required|string',
            'description' => 'nullable|string',
        ]);

        $template = ProsedurChatTemplate::findOrFail($id);
        $template->update([
            'name'        => $request->input('name'),
            'template'    => $request->input('template'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('prosedur.templates.index')
            ->with('success', 'Template chat "' . $template->name . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy($id)
    {
        $template = ProsedurChatTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('prosedur.templates.index')
            ->with('success', 'Template chat "' . $template->name . '" berhasil dihapus.');
    }
}
