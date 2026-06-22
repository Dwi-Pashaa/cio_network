<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])->latest();

        // Limit / pagination size
        $sort = $request->integer('sort', 10);
        if (!in_array($sort, [10, 25, 50, 100])) {
            $sort = 10;
        }

        // Advanced Search filtering
        if ($request->filled('search')) {
            $search = $request->input('search');
            
            // Map Indonesian / friendly keywords to model classnames
            $friendlyModelMappings = [
                'user' => 'User',
                'pengguna' => 'User',
                'pelanggan' => 'Customer',
                'customer' => 'Customer',
                'vlan' => 'Vlan',
                'router' => 'Router',
                'server' => 'Server',
                'olt' => 'OLT',
                'odc' => 'ODC',
                'odp' => 'ODP',
                'paket' => 'Paket',
                'patch core' => 'PatchCore',
                'pembayaran' => 'Price',
                'harga' => 'Price',
                'template' => 'ProsedurChatTemplate',
                'spam' => 'ProsedurSpam',
                'level' => 'Role',
                'role' => 'Role',
                'organisasi' => 'Organization',
                'mitra' => 'Organization',
                'pengaturan' => 'Setting',
                'setting' => 'Setting',
                'switch' => 'SwitchDevice',
                'layanan' => 'Type',
                'tipe' => 'Type',
                'kabupaten' => 'Regency',
                'kota' => 'Regency',
                'kecamatan' => 'District',
                'desa' => 'Village',
                'kampung' => 'HomeTown',
                'rt' => 'RT',
                'rw' => 'RW',
                'halaman' => 'Pages',
            ];
            
            $matchedClasses = [];
            foreach ($friendlyModelMappings as $keyword => $classBasename) {
                if (stripos($keyword, $search) !== false || stripos($search, $keyword) !== false) {
                    $matchedClasses[] = "App\\Models\\" . $classBasename;
                }
            }

            // Map Indonesian events to original DB values
            $eventMappings = [
                'tambah' => 'created',
                'menambahkan' => 'created',
                'ubah' => 'updated',
                'mengubah' => 'updated',
                'hapus' => 'deleted',
                'menghapus' => 'deleted'
            ];
            
            $matchedEvents = [];
            foreach ($eventMappings as $keyword => $event) {
                if (stripos($keyword, $search) !== false || stripos($search, $keyword) !== false) {
                    $matchedEvents[] = $event;
                }
            }

            $query->where(function ($q) use ($search, $matchedClasses, $matchedEvents) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%")
                  ->orWhereHasMorph('causer', [User::class], function ($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('username', 'like', "%{$search}%");
                  });
                  
                if (!empty($matchedClasses)) {
                    $q->orWhereIn('subject_type', $matchedClasses);
                }
                
                if (!empty($matchedEvents)) {
                    $q->orWhereIn('description', $matchedEvents);
                }
            });
        }

        // Filter by event (created, updated, deleted)
        if ($request->filled('event')) {
            $query->where('description', $request->input('event'));
        }

        // Filter by causer user ID
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->input('user_id'))
                  ->where('causer_type', User::class);
        }

        // Filter by subject model type
        if ($request->filled('model_type')) {
            $query->where('subject_type', $request->input('model_type'));
        }

        $logs = $query->paginate($sort)->withQueryString();

        // Fetch list of unique users who have caused activities
        $causerUserIds = Activity::where('causer_type', User::class)
            ->distinct()
            ->pluck('causer_id')
            ->toArray();
        $filterUsers = User::whereIn('id', $causerUserIds)->orderBy('name')->get();

        // Fetch list of unique model types logged
        $filterModelTypes = Activity::distinct()
            ->pluck('subject_type')
            ->filter()
            ->toArray();

        $modelNames = [
            'User' => 'Data Users',
            'Customer' => 'Data Pelanggan',
            'Vlan' => 'Data Vlan',
            'Router' => 'Data Router',
            'Server' => 'Data Server',
            'OLT' => 'Data OLT',
            'ODC' => 'Data ODC',
            'ODP' => 'Data ODP',
            'Paket' => 'Data Tipe Paket',
            'PatchCore' => 'Data Patch Core',
            'Price' => 'Data Tipe Pembayaran',
            'ProsedurChatTemplate' => 'Template Chat Prosedur',
            'ProsedurSpam' => 'Data Spam & Validasi',
            'Role' => 'Data Level',
            'Organization' => 'Data Organisasi/Mitra',
            'Setting' => 'Pengaturan',
            'SwitchDevice' => 'Switch Perangkat',
            'Type' => 'Data Tipe Layanan',
            'Regency' => 'Data Kabupaten/Kota',
            'District' => 'Data Kecamatan',
            'Village' => 'Data Desa',
            'HomeTown' => 'Data Kampung',
            'RT' => 'Data RT',
            'RW' => 'Data RW',
            'Pages' => 'Data Halaman'
        ];

        if ($request->ajax()) {
            return view('pages.activity-log.table', compact('logs', 'modelNames'));
        }

        return view('pages.activity-log.index', compact('logs', 'filterUsers', 'filterModelTypes', 'modelNames'));
    }
}
