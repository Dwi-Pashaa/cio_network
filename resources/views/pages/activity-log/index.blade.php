@extends('layouts.app')

@section('title')
    Histori Log Aktivitas
@endsection

@section('content')

<div class="container-xl" style="padding-top: 1rem; padding-bottom: 2rem;">

    <!-- Filter Card -->
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); overflow: hidden; margin-bottom: 1.5rem;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1.5px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="color: #0f172a; font-weight: 750; font-size: 1rem; margin-bottom: 0; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                Filter Log
            </h3>
        </div>
        <div style="padding: 1.5rem;">
            <form action="{{ route('activity.log.index') }}" method="GET" class="row g-3" id="filter-form">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <div class="col-md-3">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem; color: #475569;">PENGGUNA (USER)</label>
                    <select name="user_id" class="form-select" style="border-radius: 8px;">
                        <option value="">Semua Pengguna</option>
                        @foreach($filterUsers as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->username }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem; color: #475569;">TIPE AKSI (EVENT)</label>
                    <select name="event" class="form-select" style="border-radius: 8px;">
                        <option value="">Semua Aksi</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Tambah (Created)</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Ubah (Updated)</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Hapus (Deleted)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem; color: #475569;">DARI MENU</label>
                    <select name="model_type" class="form-select" style="border-radius: 8px;">
                        <option value="">Semua Menu</option>
                        @foreach($filterModelTypes as $type)
                            @php 
                                $basename = class_basename($type); 
                                $friendlyName = $modelNames[$basename] ?? $basename;
                            @endphp
                            <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                                {{ $friendlyName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if (auth()->user()->hasPermissionTo('filter organization'))
                    <div class="col-md-3">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8rem; color: #475569;">ORGANISASI</label>
                        <select name="organization_id" class="form-select" style="border-radius: 8px;">
                            <option value="">Semua Organisasi</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); font-weight: 700;">
                        Cari
                    </button>
                    <a href="{{ route('activity.log.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 8px; font-weight: 700;">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table Card -->
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1.5px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="color: #0f172a; font-weight: 750; font-size: 1.05rem; margin-bottom: 0; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Histori Aktivitas Aplikasi
            </h3>
            <span style="font-size: 0.74rem; font-weight: 700; background: rgba(37,99,235,0.08); color: #2563eb; padding: 4px 10px; border-radius: 20px;">
                Total: {{ $logs->total() }} Log
            </span>
        </div>

        {{-- Toolbar: Entries and Search --}}
        <div style="padding: 1rem 1.5rem; border-bottom: 1.5px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; background: #fafbfc;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 0.76rem; font-weight: 800; color: #475569; letter-spacing: 0.05em;">TAMPILKAN</span>
                <select name="sort" id="entries-sort" class="form-select" style="width: 80px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; padding: 4px 8px; border: 1.5px solid #cbd5e1;">
                    @foreach ([10, 25, 50, 100] as $opt)
                        <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span style="font-size: 0.76rem; font-weight: 800; color: #475569; letter-spacing: 0.05em;">DATA</span>
            </div>
            <div>
                <form action="{{ route('activity.log.index') }}" method="GET" style="margin-bottom: 0;" id="search-form">
                    @if(request('user_id'))
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    @endif
                    @if(request('event'))
                        <input type="hidden" name="event" value="{{ request('event') }}">
                    @endif
                    @if(request('model_type'))
                        <input type="hidden" name="model_type" value="{{ request('model_type') }}">
                    @endif
                    @if(request('organization_id'))
                        <input type="hidden" name="organization_id" value="{{ request('organization_id') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="input-group" style="width: 280px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <input type="text" class="form-control" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari log..." style="border-radius: 8px 0 0 8px; font-size: 0.85rem; border: 1.5px solid #cbd5e1; border-right: none;">
                        <button class="btn btn-outline-secondary" type="submit" style="border-radius: 0 8px 8px 0; border: 1.5px solid #cbd5e1; border-left: none; background: #ffffff; color: #64748b;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="log-table-wrapper">
            @include('pages.activity-log.table')
        </div>
    </div>

</div>
@endsection

@push('js')
    <script>
        $(function() {
            let searchTimeout = null;

            // Main function to fetch data via AJAX without page reload
            function fetchLogs(url = null) {
                if (!url) {
                    const params = {};
                    
                    // Gather filter-form values
                    $('#filter-form').serializeArray().forEach(item => {
                        if (item.value) {
                            params[item.name] = item.value;
                        }
                    });

                    // Gather search and sort inputs
                    params['sort'] = $('#entries-sort').val();
                    params['search'] = $('#search-input').val();

                    url = "{{ route('activity.log.index') }}?" + $.param(params);
                }

                // Show opacity transition as loading state
                $('#log-table-wrapper').css('opacity', 0.5);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#log-table-wrapper').html(response);
                        $('#log-table-wrapper').css('opacity', 1);

                        // Push state to browser URL so back-button and copy-paste works
                        window.history.pushState({ path: url }, '', url);
                    },
                    error: function() {
                        $('#log-table-wrapper').css('opacity', 1);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data histori log aktivitas.'
                        });
                    }
                });
            }

            // Intercept pagination link clicks
            $(document).on('click', '.ajax-pagination a, .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url && url !== '#') {
                    fetchLogs(url);
                }
            });

            // Intercept entries dropdown change
            $(document).on('change', '#entries-sort', function() {
                $('input[name="sort"]').val($(this).val());
                fetchLogs();
            });

            // Debounced Search Input handler
            $(document).on('keyup input', '#search-input', function() {
                clearTimeout(searchTimeout);
                $('input[name="search"]').val($(this).val());
                
                searchTimeout = setTimeout(function() {
                    fetchLogs();
                }, 500);
            });

            // Intercept forms submission
            $(document).on('submit', '#search-form, #filter-form', function(e) {
                e.preventDefault();
                fetchLogs();
            });

            // Auto-submit filter form dropdowns changes
            $(document).on('change', '#filter-form select', function() {
                fetchLogs();
            });

            // Handle browser popstate
            window.onpopstate = function(event) {
                if (event.state && event.state.path) {
                    location.reload();
                }
            };
        });
    </script>
@endpush
