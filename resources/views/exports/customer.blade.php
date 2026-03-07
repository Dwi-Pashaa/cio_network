<table class="table card-table table-vcenter text-nowrap datatable">
    <thead>
        <tr>
            <th colspan="26">DATA PELANGGAN {{ strtoupper($orgName) }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>ID Pelanggan</th>
            <th>Type Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Email</th>
            <th>No Telephone</th>
            <th>Mac Addres</th>
            <th>Jenis Router</th>
            <th>Kampung</th>
            <th>Desa</th>
            <th>RT</th>
            <th>RW</th>
            <th>Kecamatan</th>
            <th>Kabupaten/Kota</th>
            <th>Vlan</th>
            <th>Alamat ODC</th>
            <th>Alamat ODP</th>
            <th>Alamat OLT</th>
            <th>Nama Wifi</th>
            <th>Password Wifi</th>
            <th>PPOE Username</th>
            <th>PPOE Password</th>
            <th>Tipe Paket</th>
            <th>Tipe Pembayaran</th>
            <th>Lokasi</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($customers as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->uuid ?? '-' }}</td>
                <td>{{ optional($item->type)->name ?? '-' }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->telp }}</td>
                <td>{{ $item->mac_address }}</td>
                <td>{{ optional($item->router)->name ?? '-' }}</td>
                <td>{{ optional($item->hometown)->name ?? '-' }}</td>
                <td>{{ optional($item->village)->name ?? '-' }}</td>
                <td>{{ optional($item->rt)->name ?? '-' }}</td>
                <td>{{ optional($item->rw)->name ?? '-' }}</td>
                <td>{{ optional($item->district)->name ?? '-' }}</td>
                <td>{{ optional($item->regencie)->name ?? '-' }}</td>
                <td>{{ optional($item->vlan)->name ?? '-' }}</td>
                <td>
                    @if ($item->odc)
                        {{ $item->odc->code }} | {{ optional($item->odc->hometown)->name ?? '-' }}
                        | {{ optional($item->odc->rt)->name ?? '-' }} | {{ optional($item->odc->rw)->name ?? '-' }} |
                        {{ $item->odc->home_odc }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($item->odp)
                        {{ $item->odp->code }} | {{ optional($item->odp->hometown)->name ?? '-' }}
                        | {{ optional($item->odp->rt)->name ?? '-' }} | {{ optional($item->odp->rw)->name ?? '-' }} |
                        {{ $item->odp->home_odc }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($item->olt)
                        {{ optional($item->olt->hometown)->name ?? '-' }} | {{ $item->olt->name }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->name_wifi ?? '-' }}</td>
                <td>{{ $item->password_wifi ?? '-' }}</td>
                <td>{{ $item->pppoe_username ?? '-' }}</td>
                <td>{{ $item->pppoe_password ?? '-' }}</td>
                <td>{{ optional($item->paket)->name ?? '-' }}</td>
                <td>{{ optional($item->price)->name ?? '-' }}</td>
                <td>{{ $item->latitude && $item->longitude ? 'https://www.google.com/maps?q=' . $item->latitude . ',' . $item->longitude : '-' }}
                </td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="17" class="text-center">Tidak Ada Data</td>
            </tr>
        @endforelse
    </tbody>
</table>
