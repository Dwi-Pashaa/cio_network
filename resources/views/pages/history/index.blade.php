@extends('layouts.app')

@section('title')
    History Pemasangan
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Pilih User</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($user as $usr)
                                    <option value="{{ $usr->id }}" {{ request('user_id') == $usr->id ? 'selected' : '' }}>{{ $usr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tanggal Mulai</label>
                            <input type="date" name="start" id="start" class="form-control datepicker" placeholder="Pilih Tanggal Mulai" value="{{ request('start') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tanggal Selesai</label>
                            <input type="date" name="end" id="end" class="form-control datepicker" placeholder="Pilih Tanggal Selesai" value="{{ request('end') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary w-100 mt-4">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <b>Jumlah Pemasangan {{ count($history) ?? 0 }}</b>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th><button class="table-sort d-flex justify-content-between desc">No</button></th>
                        <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-input">Di Input Oleh</button></th>
                        <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-id">ID Pelanggan</button></th>
                        <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-nama">Nama Pelanggan</button></th>
                        <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-kampung">Kampung</button></th>
                        <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-desa">Desa</button></th>
                        <th><button class="table-sort d-flex justify-content-between desc" data-sort="sort-created">Created</button></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="sort-input">{{ optional($item)->user->name ?? '-' }}</td>
                            <td class="sort-id">{{ $item->uuid ?? '-' }}</td>
                            <td class="sort-nama">{{ $item->name }}</td>
                            <td class="sort-kampung">{{ $item->hometown->name }}</td>
                            <td class="sort-desa">{{ $item->village->name }}</td>
                            <td class="sort-created">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    
@endpush
