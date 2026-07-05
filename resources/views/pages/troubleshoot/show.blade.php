@extends('layouts.app')

@section('title', 'Detail Troubleshoot')

@section('content')
<div class="org-container">
    @include('components.alert.success')
    <div class="org-card">
        <div class="org-header">
            <div class="org-title-wrap">
                <div>
                    <h5 class="org-title">Detail Ticket Troubleshoot #{{ $troubleshoot->id }}</h5>
                    <div class="org-subtitle">{{ $troubleshoot->customer->name ?? '-' }}</div>
                </div>
            </div>
            <div>
                <a href="{{ route('troubleshoot.index') }}" class="btn btn-outline-secondary">Kembali</a>

                @if($troubleshoot->customer->latitude && $troubleshoot->customer->longitude)
                    <a href="{{ route('troubleshoot.tracking', $troubleshoot->id) }}" class="btn btn-outline-info">Tracking</a>
                @endif
            </div>
        </div>

        <table class="table table-bordered mt-3">
            <tr>
                <th style="width:200px;">Pelanggan</th>
                <td>{{ $troubleshoot->customer->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Teknisi</th>
                <td>{{ $troubleshoot->technician->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @php
                        $badgeClass = match($troubleshoot->status) {
                            'open' => 'badge bg-warning',
                            'menuju_lokasi' => 'badge bg-info',
                            'tiba_lokasi' => 'badge bg-primary',
                            'perbaikan' => 'badge bg-indigo',
                            'done' => 'badge bg-success',
                            'cancelled' => 'badge bg-danger',
                            default => 'badge bg-secondary',
                        };
                    @endphp
                    <span class="{{ $badgeClass }}">{{ str_replace('_', ' ', ucfirst($troubleshoot->status)) }}</span>
                </td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $troubleshoot->description }}</td>
            </tr>
            <tr>
                <th>Catatan Teknisi</th>
                <td>{{ $troubleshoot->notes ?? '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat Oleh</th>
                <td>{{ $troubleshoot->creator->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat Tanggal</th>
                <td>{{ $troubleshoot->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Diupdate Tanggal</th>
                <td>{{ $troubleshoot->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>

        @can('kelola troubleshoot')
        <hr>
        <h6>Update Status</h6>
        <form action="{{ route('troubleshoot.update-status', $troubleshoot->id) }}" method="POST" class="row g-2">
            @csrf
            @method('PUT')
            <div class="col-md-4">
                <select name="status" class="form-control" required>
                    <option value="open" {{ $troubleshoot->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="on_progress" {{ $troubleshoot->status == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                    <option value="done" {{ $troubleshoot->status == 'done' ? 'selected' : '' }}>Done</option>
                    <option value="cancelled" {{ $troubleshoot->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="notes" class="form-control" placeholder="Catatan (opsional)" value="{{ $troubleshoot->notes }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Update</button>
            </div>
        </form>
        @endcan
    </div>
</div>
@endsection
