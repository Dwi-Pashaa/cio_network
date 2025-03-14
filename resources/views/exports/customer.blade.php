<table class="table card-table table-vcenter text-nowrap datatable">
    <thead>
        <tr>
            <th>No</th>
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
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($customers as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->type->name }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->telp }}</td>
                <td>{{ $item->mac_address }}</td>
                <td>{{ $item->router->name }}</td>
                <td>{{ $item->hometown->name }}</td>
                <td>{{ $item->village->name }}</td>
                <td>{{ $item->rt->name }}</td>
                <td>{{ $item->rw->name }}</td>
                <td>{{ $item->district->name }}</td>
                <td>{{ $item->regencie->name }}</td>
                <td>{{ $item->vlan->name }}</td>
                <td>
                    {{ $item->odc->code }} | {{ $item->odc->hometown->name }} 
                    | {{ $item->odc->rt->name }} | {{ $item->odc->rw->name }} |
                    {{ $item->odc->home_odc }}
                </td>
                <td>
                    {{ $item->odp->code }} | {{ $item->odp->hometown->name }} 
                    | {{ $item->odp->rt->name }} | {{ $item->odp->rw->name }} |
                    {{ $item->odp->home_odc }}
                </td>
                <td>{{ $item->olt->hometown->name }} | {{ $item->olt->name }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="17" class="text-center">Tidak Ada Data</td>
            </tr>
        @endforelse
    </tbody>
</table>