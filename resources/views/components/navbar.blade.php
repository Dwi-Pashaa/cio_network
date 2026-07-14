<header class="navbar-expand-md modern-navbar-wrapper">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar navbar-transparent">
            <div class="container-xl">
                <div class="row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav modern-nav">

                            {{-- ==================== Dashboard ==================== --}}
                            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Dashboard</span>
                                </a>
                            </li>

                            {{-- ==================== Manajemen ==================== --}}
                            @canany(['lihat user', 'lihat level', 'lihat organisasi'])
                                <li class="nav-item dropdown {{ request()->is('role*') || request()->is('user*') || Route::is('organization*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-manajemen"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 12l2 2l4 -4"/>
                                                <path d="M12 3a9 9 0 1 0 0 18a9 9 0 0 0 0 -18z"/>
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">Manajemen</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @if(auth()->user()->organization && auth()->user()->organization->type === 'internal' && auth()->user()->hasRole('Admin'))
                                            @can('lihat organisasi')
                                                <a class="dropdown-item {{ Route::is('organization*') ? 'active' : '' }}"
                                                    href="{{ route('organization.index') }}" rel="noopener">
                                                    Data Organisasi/Mitra
                                                </a>
                                            @endcan
                                        @endif
                                        @can('lihat level')
                                            <a class="dropdown-item {{ Route::is('role*') ? 'active' : '' }}"
                                                href="{{ route('role.index') }}" rel="noopener">
                                                Data Level
                                            </a>
                                        @endcan
                                        @can('lihat user')
                                            <a class="dropdown-item {{ Route::is('user*') ? 'active' : '' }}"
                                                href="{{ route('user.index') }}" rel="noopener">
                                                Data Users
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcanany

                            {{-- ==================== Barang ==================== --}}
                            @canany(['lihat barang', 'lihat mac address', 'lihat patch core', 'lihat plc', 'lihat stock router', 'lihat stock patch core', 'lihat stock plc'])
                                <li class="nav-item dropdown {{ request()->is('barang*') || request()->is('patch.core*') || request()->is('plc*') || request()->is('user.router*') || request()->is('user.patch.core*') || request()->is('user.plc*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-barang"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01"/>
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">Barang</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat patch core')
                                            <a class="dropdown-item {{ Route::is('patch.core*') ? 'active' : '' }}"
                                                href="{{ route('patch.core.index') }}" rel="noopener">
                                                Data Patch Core
                                            </a>
                                        @endcan
                                        @can('lihat plc')
                                            <a class="dropdown-item {{ Route::is('plc*') ? 'active' : '' }}"
                                                href="{{ route('plc.index') }}" rel="noopener">
                                                Data PLC
                                            </a>
                                        @endcan
                                        @canany(['lihat stock router', 'lihat stock patch core', 'lihat stock plc'])
                                            <div class="dropdown-divider"></div>
                                            @can('lihat stock router')
                                                <a class="dropdown-item {{ Route::is('user.router*') ? 'active' : '' }}"
                                                    href="{{ route('user.router.index') }}" rel="noopener">
                                                    Stock Router
                                                </a>
                                            @endcan
                                            @can('lihat stock patch core')
                                                <a class="dropdown-item {{ Route::is('user.patch.core*') ? 'active' : '' }}"
                                                    href="{{ route('user.patch.core.index') }}" rel="noopener">
                                                    Stock Patch Core
                                                </a>
                                            @endcan
                                            @can('lihat stock plc')
                                                <a class="dropdown-item {{ Route::is('user.plc*') ? 'active' : '' }}"
                                                    href="{{ route('user.plc.index') }}" rel="noopener">
                                                    Stock PLC
                                                </a>
                                            @endcan
                                        @endcanany
                                    </div>
                                </li>
                            @endcanany

                            {{-- ==================== Pelanggan ==================== --}}
                            @canany(['lihat tipe paket', 'lihat tipe pembayaran', 'lihat pelanggan', 'lihat tipe pelanggan', 'lihat tipe layanan'])
                                <li class="nav-item dropdown {{ request()->is('type*') || request()->is('customer*') || request()->is('paket*') || request()->is('price*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-pelanggan"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                                <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/>
                                                <path d="M3 12h6"/>
                                                <path d="M15 12h6"/>
                                                <path d="M12 3v6"/>
                                                <path d="M12 15v6"/>
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">Pelanggan</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat tipe layanan')
                                            <a class="dropdown-item {{ Route::is('type') || Route::is('type.create') || Route::is('type.edit') ? 'active' : '' }}"
                                                href="{{ route('type.index') }}" rel="noopener">
                                                Data Tipe Layanan
                                            </a>
                                        @endcan
                                        @can('lihat tipe pelanggan')
                                            <a class="dropdown-item {{ Route::is('type.customer*') ? 'active' : '' }}"
                                                href="{{ route('type.customer.index') }}" rel="noopener">
                                                Data Tipe Pelanggan
                                            </a>
                                        @endcan
                                        @can('lihat tipe paket')
                                            <a class="dropdown-item {{ Route::is('paket*') ? 'active' : '' }}"
                                                href="{{ route('paket.index') }}" rel="noopener">
                                                Data Tipe Paket
                                            </a>
                                        @endcan
                                        @can('lihat tipe pembayaran')
                                            <a class="dropdown-item {{ Route::is('price*') ? 'active' : '' }}"
                                                href="{{ route('price.index') }}" rel="noopener">
                                                Data Tipe Pembayaran
                                            </a>
                                        @endcan
                                        @can('lihat pelanggan')
                                            <a class="dropdown-item {{ Route::is('customer*') ? 'active' : '' }}"
                                                href="{{ route('customer.index') }}" rel="noopener">
                                                Data Pelanggan
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcanany

                            {{-- ==================== Jaringan ==================== --}}
                            @canany(['lihat router', 'lihat vlan', 'lihat odc', 'lihat odp', 'lihat olt', 'lihat server', 'lihat mic radius', 'lihat mac address'])
                                <li class="nav-item dropdown {{ request()->is('master-network*') || request()->is('router*') || request()->is('vlan*') || request()->is('odc*') || request()->is('odp*') || request()->is('olt*') || request()->is('server*') || request()->is('mic.radius*') || request()->is('mac.address*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-jaringan"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 9v11a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-11"/>
                                                <path d="M20 9l-8 -6l-8 6"/>
                                                <path d="M12 10v4"/>
                                                <path d="M10 12h4"/>
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">Jaringan</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat router')
                                            <a class="dropdown-item {{ Route::is('router*') ? 'active' : '' }}"
                                                href="{{ route('router.index') }}" rel="noopener">
                                                Data Router
                                            </a>
                                        @endcan
                                        @can('lihat vlan')
                                            <a class="dropdown-item {{ Route::is('vlan*') ? 'active' : '' }}"
                                                href="{{ route('vlan.index') }}" rel="noopener">
                                                Data Vlan
                                            </a>
                                        @endcan
                                        @can('lihat odc')
                                            <a class="dropdown-item {{ Route::is('odc*') ? 'active' : '' }}"
                                                href="{{ route('odc.index') }}" rel="noopener">
                                                Data ODC
                                            </a>
                                        @endcan
                                        @can('lihat odp')
                                            <a class="dropdown-item {{ Route::is('odp*') ? 'active' : '' }}"
                                                href="{{ route('odp.index') }}" rel="noopener">
                                                Data ODP
                                            </a>
                                        @endcan
                                        @can('lihat olt')
                                            <a class="dropdown-item {{ Route::is('olt*') ? 'active' : '' }}"
                                                href="{{ route('olt.index') }}" rel="noopener">
                                                Data OLT
                                            </a>
                                        @endcan
                                        @can('lihat server')
                                            <a class="dropdown-item {{ Route::is('server*') ? 'active' : '' }}"
                                                href="{{ route('server.index') }}" rel="noopener">
                                                Data Server
                                            </a>
                                        @endcan
                                        @can('lihat mic radius')
                                            <a class="dropdown-item {{ Route::is('mic.radius*') ? 'active' : '' }}"
                                                href="{{ route('mic.radius.index') }}" rel="noopener">
                                                Data Mic Radius
                                            </a>
                                        @endcan
                                        @can('lihat mac address')
                                            <a class="dropdown-item {{ Route::is('mac.address*') ? 'active' : '' }}"
                                                href="{{ route('mac.address.index') }}" rel="noopener">
                                                Data Mac Address
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcanany

                            {{-- ==================== Wilayah ==================== --}}
                            @canany(['lihat kabupaten', 'lihat kecamatan', 'lihat kampung', 'lihat desa', 'lihat rt', 'lihat rw'])
                                <li class="nav-item dropdown {{ request()->is('master-region*') || request()->is('kabupaten*') || request()->is('kecamatan*') || request()->is('kampung*') || request()->is('desa*') || request()->is('rt*') || request()->is('rw*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-wilayah"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7 -9 13 -9 13s-9 -6 -9 -13a9 9 0 0 1 18 0z"/>
                                                <path d="M12 10a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z"/>
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">Wilayah</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat kabupaten')
                                            <a class="dropdown-item {{ Route::is('kabupaten*') ? 'active' : '' }}"
                                                href="{{ route('kabupaten.index') }}" rel="noopener">
                                                Data Kabupaten/Kota
                                            </a>
                                        @endcan
                                        @can('lihat kecamatan')
                                            <a class="dropdown-item {{ Route::is('kecamatan*') ? 'active' : '' }}"
                                                href="{{ route('kecamatan.index') }}" rel="noopener">
                                                Data Kecamatan
                                            </a>
                                        @endcan
                                        @can('lihat kampung')
                                            <a class="dropdown-item {{ Route::is('kampung*') ? 'active' : '' }}"
                                                href="{{ route('kampung.index') }}" rel="noopener">
                                                Data Kampung
                                            </a>
                                        @endcan
                                        @can('lihat desa')
                                            <a class="dropdown-item {{ Route::is('desa*') ? 'active' : '' }}"
                                                href="{{ route('desa.index') }}" rel="noopener">
                                                Data Desa
                                            </a>
                                        @endcan
                                        @can('lihat rt')
                                            <a class="dropdown-item {{ Route::is('rt*') ? 'active' : '' }}"
                                                href="{{ route('rt.index') }}" rel="noopener">
                                                Data RT
                                            </a>
                                        @endcan
                                        @can('lihat rw')
                                            <a class="dropdown-item {{ Route::is('rw*') ? 'active' : '' }}"
                                                href="{{ route('rw.index') }}" rel="noopener">
                                                Data RW
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcanany

                            {{-- ==================== Prosedur ==================== --}}
                            @canany(['lihat halaman', 'pergantian perangkat', 'pemutusan layanan', 'pergantian layanan'])
                                <li class="nav-item dropdown {{ request()->is('master-pages*') || request()->is('prosedur*') || request()->is('halaman*') || request()->is('spam*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-prosedur"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2"/>
                                                <rect x="9" y="3" width="6" height="4" rx="1" ry="1"/>
                                                <path d="M9 12h6"/>
                                                <path d="M9 16h4"/>
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">Prosedur</span>
                                    </a>
                                    <div class="dropdown-menu">
                                         @can('lihat halaman')
                                            <a class="dropdown-item {{ Route::is('halaman*') ? 'active' : '' }}"
                                                href="{{ route('halaman.index') }}" rel="noopener">
                                                Data Halaman
                                            </a>
                                         @endcan
                                         @canany(['lihat antrean prosedur', 'validasi prosedur level 1', 'validasi prosedur level 2', 'validasi prosedur level 3', 'validasi prosedur level 4'])
                                            <a class="dropdown-item {{ Route::is('spam*') || Route::is('validasi.prosedur*') ? 'active' : '' }}"
                                                href="{{ route('spam.index') }}" rel="noopener">
                                                Data Spam & Validasi
                                            </a>
                                         @endcanany
                                         @can('kelola template chat')
                                            <a class="dropdown-item {{ Route::is('prosedur.templates*') ? 'active' : '' }}"
                                                href="{{ route('prosedur.templates.index') }}" rel="noopener">
                                                Template Chat Prosedur
                                            </a>
                                         @endcan
                                        @can('pergantian perangkat')
                                            <a class="dropdown-item {{ request()->is('prosedur') && request()->query('tipe') === 'onu-router' ? 'active' : '' }}"
                                                href="{{ route('public.prosedur') }}?tipe=onu-router" rel="noopener">
                                                Pergantian Perangkat
                                            </a>
                                        @endcan
                                        @can('pergantian layanan')
                                            <a class="dropdown-item {{ request()->is('prosedur') && request()->query('tipe') === 'pergantian-layanan' ? 'active' : '' }}"
                                                href="{{ route('public.prosedur') }}?tipe=pergantian-layanan" rel="noopener">
                                                Pergantian Layanan
                                            </a>
                                        @endcan
                                        @can('pemutusan layanan')
                                            <a class="dropdown-item {{ request()->is('prosedur') && request()->query('tipe') === 'pemutusan' ? 'active' : '' }}"
                                                href="{{ route('public.prosedur') }}?tipe=pemutusan" rel="noopener">
                                                Pemutusan Layanan
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcan

                            {{-- ==================== Lainnya ==================== --}}
                            @canany(['lihat halaman', 'lihat histori pemasangan', 'chatting', 'lihat log wablas', 'lihat log aktivitas'])
                                <li class="nav-item dropdown {{ request()->is('chatting*') || request()->is('history*') || request()->is('report*') || request()->is('activity-log*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-lainnya"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                                <path d="M9 15h6"/>
                                                <path d="M9 11h6"/>
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">Lainnya</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('chatting')
                                            <a class="dropdown-item {{ Route::is('chatting*') ? 'active' : '' }}"
                                                href="{{ route('chatting.index') }}" rel="noopener">
                                                Chatting
                                            </a>
                                        @endcan
                                        @can('lihat histori pemasangan')
                                            <a class="dropdown-item {{ Route::is('history*') ? 'active' : '' }}"
                                                href="{{ route('history.index') }}" rel="noopener">
                                                History Pemasangan
                                            </a>
                                        @endcan
                                        @can('lihat log wablas')
                                            <a class="dropdown-item {{ Route::is('report*') ? 'active' : '' }}"
                                                href="{{ route('report.index') }}" rel="noopener">
                                                Log Wablas
                                            </a>
                                        @endcan
                                        @can('lihat log aktivitas')
                                            <a class="dropdown-item {{ Route::is('activity.log.index*') ? 'active' : '' }}"
                                                href="{{ route('activity.log.index') }}" rel="noopener">
                                                History Log
                                            </a>
                                         @endcan
                                         @canany(['kelola troubleshoot', 'lihat troubleshoot'])
                                            <a class="dropdown-item {{ Route::is('troubleshoot*') ? 'active' : '' }}"
                                                href="{{ route('troubleshoot.index') }}" rel="noopener">
                                                Open Ticket
                                            </a>
                                         @endcanany
                                    </div>
                                </li>
                            @endcanany

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>