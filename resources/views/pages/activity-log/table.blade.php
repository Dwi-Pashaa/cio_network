@php
    $translateEvent = function($event) {
        switch($event) {
            case 'created':
                return 'menambahkan';
            case 'updated':
                return 'mengubah';
            case 'deleted':
                return 'menghapus';
            default:
                return $event;
        }
    };

    $getFriendlySubjectName = function($log) use ($modelNames) {
        $basename = class_basename($log->subject_type);
        $friendlyModel = $modelNames[$basename] ?? $basename;

        // Coba cari nama entitas dari subject langsung
        $entityName = '';
        if ($log->subject) {
            $entityName = $log->subject->name ?? $log->subject->username ?? $log->subject->code ?? '';
        }

        // Jika subject tidak ada (karena sudah dihapus) atau tidak punya field nama
        if (empty($entityName)) {
            $attributes = $log->properties['attributes'] ?? [];
            $old = $log->properties['old'] ?? [];
            
            // Prioritaskan attributes untuk created/updated, dan old untuk deleted
            $source = ($log->description === 'deleted') ? $old : $attributes;
            if (empty($source)) {
                $source = array_merge($old, $attributes);
            }

            $entityName = $source['name'] ?? $source['username'] ?? $source['code'] ?? '';
        }

        if (!empty($entityName)) {
            return "{$friendlyModel} (<strong>{$entityName}</strong>)";
        }

        return $friendlyModel;
    };
@endphp

<div style="overflow-x: auto;">
    <table class="table" style="margin-bottom: 0; width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc;">
                <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9; width: 80px;">No</th>
                <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9; width: 150px;">Aksi</th>
                <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9; width: 180px;">Menu</th>
                <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9;">Pengguna</th>
                @if (auth()->user()->hasPermissionTo('filter organization'))
                    <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9;">Organisasi/Mitra</th>
                @endif
                <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9;">Detail Aksi & Waktu</th>
                <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1.5px solid #f1f5f9; text-align: right; width: 150px;">Detail Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                @php
                    $badgeStyle = '';
                    $badgeLabel = '';
                    switch($log->description) {
                        case 'created':
                            $badgeStyle = 'background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a;';
                            $badgeLabel = 'TAMBAH';
                            break;
                        case 'updated':
                            $badgeStyle = 'background: #fef9c3; border: 1px solid #fef08a; color: #ca8a04;';
                            $badgeLabel = 'UBAH';
                            break;
                        case 'deleted':
                            $badgeStyle = 'background: #fee2e2; border: 1px solid #fecaca; color: #ef4444;';
                            $badgeLabel = 'HAPUS';
                            break;
                        default:
                            $badgeStyle = 'background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569;';
                            $badgeLabel = strtoupper($log->description);
                    }
                @endphp
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                    <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                        <span style="font-weight: 600; color: #64748b; font-size: 0.85rem;">{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                        <span style="display: inline-block; font-size: 0.75rem; font-weight: 750; padding: 4px 10px; border-radius: 20px; {{ $badgeStyle }}">
                            {{ $badgeLabel }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                        <code style="white-space: nowrap; font-family: monospace; font-size: 0.8rem; font-weight: 700; color: #0f172a; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            {{ $modelNames[class_basename($log->subject_type)] ?? (class_basename($log->subject_type) ?: 'Sistem') }}
                        </code>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                        @if($log->causer)
                            <div style="font-weight: 750; color: #0f172a; font-size: 0.875rem;">{{ $log->causer->name }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">@<span>{{ $log->causer->username }}</span></div>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">Sistem / Guest</span>
                        @endif
                    </td>
                    @if (auth()->user()->hasPermissionTo('filter organization'))
                        <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                            @if($log->causer && $log->causer instanceof \App\Models\User && $log->causer->organization)
                                <span style="font-size: 0.85rem; font-weight: 600; color: #0f172a;">{{ $log->causer->organization->name }}</span>
                            @else
                                <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">-</span>
                            @endif
                        </td>
                    @endif
                    <td style="padding: 1.25rem 1.5rem; border-bottom: none; vertical-align: middle;">
                        <div style="color: #334155; font-size: 0.85rem; margin-bottom: 2px;">
                            Melakukan aksi <strong>{{ $translateEvent($log->description) }}</strong> pada data {!! $getFriendlySubjectName($log) !!}
                        </div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: middle;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ $log->created_at->format('d M Y H:i:s') }} 
                            <span style="color: #cbd5e1; margin: 0 4px;">|</span> 
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: none; text-align: right; vertical-align: middle;">
                        @if(!empty($log->properties['attributes']) || !empty($log->properties['old']))
                            <button type="button" class="btn btn-outline-info btn-sm" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#details-{{ $log->id }}" 
                                    aria-expanded="false" 
                                    style="border-radius: 8px; font-weight: 700; font-size: 0.75rem; padding: 5px 12px;">
                                Lihat Detail
                            </button>
                        @else
                            <span style="color: #cbd5e1; font-size: 0.75rem; font-style: italic;">Tidak ada detail</span>
                        @endif
                    </td>
                </tr>

                {{-- Collapsible details row --}}
                @if(!empty($log->properties['attributes']) || !empty($log->properties['old']))
                    @php
                        $attributes = $log->properties['attributes'] ?? [];
                        $old = $log->properties['old'] ?? [];
                        $allKeys = array_unique(array_merge(array_keys($attributes), array_keys($old)));
                    @endphp
                    <tr class="collapse" id="details-{{ $log->id }}" style="background: #f8fafc;">
                        <td colspan="{{ auth()->user()->hasPermissionTo('filter organization') ? 7 : 6 }}" style="padding: 1rem 1.5rem; border-top: none; border-bottom: 1px solid #e2e8f0;">
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                                <h5 style="font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 0.75rem;">
                                    Perbandingan Perubahan Data
                                </h5>
                                <div style="overflow-x: auto;">
                                    <table class="table table-sm table-bordered" style="margin-bottom: 0; font-size: 0.8rem;">
                                        <thead>
                                            <tr style="background: #f8fafc;">
                                                <th style="width: 250px; font-weight: 700;">Kolom/Properti</th>
                                                @if($log->description === 'updated')
                                                    <th style="font-weight: 700; color: #dc2626; background: #fff5f5;">Nilai Lama</th>
                                                @endif
                                                <th style="font-weight: 700; color: #16a34a; background: #f0fdf4;">
                                                    {{ $log->description === 'updated' ? 'Nilai Baru' : 'Data Direkam' }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allKeys as $key)
                                                @php
                                                    $newValue = $attributes[$key] ?? null;
                                                    $oldValue = $old[$key] ?? null;
                                                    
                                                    // Filter passwords or sensitive fields from showing value
                                                    if (in_array($key, ['password', 'remember_token'])) {
                                                        $newValue = '********';
                                                        $oldValue = '********';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td style="font-weight: 650; color: #475569;">
                                                        {{ ucwords(str_replace('_', ' ', $key)) }} 
                                                        <code style="font-size: 0.7rem; color: #94a3b8; font-weight: normal;">({{ $key }})</code>
                                                    </td>
                                                    @if($log->description === 'updated')
                                                        <td style="color: #ef4444; background: #fff5f5; font-family: monospace;">
                                                            {{ is_array($oldValue) ? json_encode($oldValue) : ($oldValue ?? '-') }}
                                                        </td>
                                                    @endif
                                                    <td style="color: #16a34a; background: #f0fdf4; font-family: monospace;">
                                                        {{ is_array($newValue) ? json_encode($newValue) : ($newValue ?? '-') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->hasPermissionTo('filter organization') ? 7 : 6 }}" style="padding: 3rem 1.5rem; text-align: center; color: #94a3b8; border-bottom: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem; color: #cbd5e1;">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem;">Tidak ada log aktivitas ditemukan.</div>
                        <div style="font-size: 0.85rem;">Coba ubah kriteria filter pencarian Anda.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="padding: 1.25rem 1.5rem; border-top: 1.5px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; flex-wrap: wrap; gap: 1rem;">
    <p style="margin: 0; font-size: 0.85rem; font-weight: 600; color: #64748b;">
        Menampilkan <span>{{ $logs->firstItem() ?: 0 }}</span> 
        sampai <span>{{ $logs->lastItem() ?: 0 }}</span> dari
        <span>{{ $logs->total() }}</span> data
    </p>
    <div class="ajax-pagination">
        {{ $logs->links() }}
    </div>
</div>
