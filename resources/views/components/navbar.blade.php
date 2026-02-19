<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Dashboard
                                    </span>
                                </a>
                            </li>
                            @role('Admin')
                                <li class="nav-item dropdown {{ request()->is('role*') || request()->is('user*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Data
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item {{ Route::is("role*") ? 'active' : '' }}" href="{{ route('role.index') }}" rel="noopener">
                                            Data Level
                                        </a>
                                        <a class="dropdown-item {{ Route::is("user*") ? 'active' : '' }}" href="{{ route('user.index') }}" rel="noopener">
                                            Data Users
                                        </a>
                                    </div>
                                </li>
                            @endrole
                            @canany(['lihat barang', 'lihat mac address'])
                                <li class="nav-item dropdown {{ request()->is('barang*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Barang
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat patch core')
                                            <a class="dropdown-item {{ Route::is("patch.core*") ? 'active' : '' }}" href="{{ route('patch.core.index') }}" rel="noopener">
                                                Data Patch Core
                                            </a>
                                        @endcan
                                        @can('lihat plc')
                                            <a class="dropdown-item {{ Route::is("plc*") ? 'active' : '' }}" href="{{ route('plc.index') }}" rel="noopener">
                                                Data PLC
                                            </a>
                                        @endcan
                                        @canany(['lihat stock router', 'lihat stock patch core', 'lihat stock plc'])
                                            <div class="dropdown-divider"></div>
                                            @can('lihat stock router')
                                                <a class="dropdown-item {{ Route::is("user.router*") ? 'active' : '' }}" href="{{ route('user.router.index') }}" rel="noopener">
                                                    Stock Router
                                                </a>
                                            @endcan
                                            @can('lihat stock patch core')
                                                <a class="dropdown-item {{ Route::is("user.patch.core*") ? 'active' : '' }}" href="{{ route('user.patch.core.index') }}" rel="noopener">
                                                    Stock Patch Core
                                                </a>
                                            @endcan
                                            @can('lihat stock plc')
                                                <a class="dropdown-item {{ Route::is("user.plc*") ? 'active' : '' }}" href="{{ route('user.plc.index') }}" rel="noopener">
                                                    Stock PLC
                                                </a>
                                            @endcan
                                        @endcanany
                                    </div>
                                </li>
                            @endcanany
                            @canany(['lihat tipe paket', 'lihat tipe pembayaran', 'lihat pelanggan'])
                                <li class="nav-item dropdown {{ request()->is('type*') || request()->is('customer*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Pelanggan
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @role('Admin')
                                            <a class="dropdown-item {{ Route::is('type*') ? 'active' : '' }}" href="{{ route('type.index') }}" rel="noopener">
                                                Data Tipe Pelanggan
                                            </a>
                                        @endrole
                                        @can('lihat tipe paket')
                                            <a class="dropdown-item {{ Route::is("paket*") ? 'active' : '' }}" href="{{ route('paket.index') }}" rel="noopener">
                                                Data Tipe Paket
                                            </a>
                                        @endcan
                                        @can('lihat tipe pembayaran')
                                            <a class="dropdown-item {{ Route::is("price*") ? 'active' : '' }}" href="{{ route('price.index') }}" rel="noopener">
                                                Data Tipe Pembayaran
                                            </a>
                                        @endcan
                                        @can('lihat pelanggan')
                                            <a class="dropdown-item {{ Route::is("customer*") ? 'active' : '' }}" href="{{ route('customer.index') }}" rel="noopener">
                                                Data Pelanggan
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcanany
                            @canany(['lihat router', 'lihat vlan', 'lihat odc', 'lihat odp', 'lihat olt', 'lihat mic radius', 'lihat mac address'])
                                <li class="nav-item dropdown {{ request()->is('master-network*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Jaringan
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat router')
                                            <a class="dropdown-item {{ Route::is("router*") ? 'active' : '' }}" href="{{ route('router.index') }}" rel="noopener">
                                                Data Router
                                            </a>
                                        @endcan
                                        @can('lihat vlan')
                                            <a class="dropdown-item {{ Route::is("vlan*") ? 'active' : '' }}" href="{{ route('vlan.index') }}" rel="noopener">
                                                Data Vlan
                                            </a>
                                        @endcan
                                        @can('lihat odc')
                                            <a class="dropdown-item {{ Route::is("odc*") ? 'active' : '' }}" href="{{ route('odc.index') }}">
                                                Data ODC
                                            </a>
                                        @endcan
                                        @can('lihat odp')
                                            <a class="dropdown-item {{ Route::is("odp*") ? 'active' : '' }}" href="{{ route('odp.index') }}" rel="noopener">
                                                Data ODP
                                            </a>
                                        @endcan
                                        @can('lihat olt')
                                            <a class="dropdown-item {{ Route::is("olt*") ? 'active' : '' }}" href="{{ route('olt.index') }}" rel="noopener">
                                                Data OLT
                                            </a>
                                        @endcan
                                        @can('lihat mic radius')
                                            <a class="dropdown-item {{ Route::is("mic.radius*") ? 'active' : '' }}" href="{{ route('mic.radius.index') }}" rel="noopener">
                                                Data Mic Radius
                                            </a>
                                        @endcan
                                        @can('lihat mac address')
                                            <a class="dropdown-item {{ Route::is("mac.address*") ? 'active' : '' }}" href="{{ route('mac.address.index') }}" rel="noopener">
                                                Data Mac Address
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcanany
                            @canany(['lihat kabupaten', 'lihat kecamatan', 'lihat kampung', 'lihat desa', 'lihat rt', 'lihat rw'])
                                <li class="nav-item dropdown {{ request()->is('master-region*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Wilayah
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat kabupaten')
                                            <a class="dropdown-item {{ Route::is("kabupaten*") ? 'active' : '' }}" href="{{ route('kabupaten.index') }}" rel="noopener">
                                                Data Kabupaten/Kota
                                            </a>
                                        @endcan
                                        @can('lihat kecamatan')
                                            <a class="dropdown-item {{ Route::is("kecamatan*") ? 'active' : '' }}" href="{{ route('kecamatan.index') }}" rel="noopener">
                                                Data Kecamatan
                                            </a>
                                        @endcan
                                         @can('lihat kampung')
                                            <a class="dropdown-item {{ Route::is("kampung*") ? 'active' : '' }}" href="{{ route('kampung.index') }}" rel="noopener">
                                                Data Kampung
                                            </a>
                                        @endcan
                                        @can('lihat desa')
                                            <a class="dropdown-item {{ Route::is("desa*") ? 'active' : '' }}" href="{{ route('desa.index') }}" rel="noopener">
                                                Data Desa
                                            </a>
                                        @endcan
                                        @can('lihat rt')
                                            <a class="dropdown-item {{ Route::is("rt*") ? 'active' : '' }}" href="{{ route('rt.index') }}" rel="noopener">
                                                Data RT
                                            </a>
                                        @endcan
                                        @can('lihat rw')
                                            <a class="dropdown-item {{ Route::is("rw*") ? 'active' : '' }}" href="{{ route('rw.index') }}" rel="noopener">
                                                Data RW
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endcanany
                            @canany(['lihat halaman', 'lihat histori pemasangan'])
                                <li class="nav-item dropdown {{ request()->is('master-pages*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Halaman
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat halaman')
                                            <a class="dropdown-item {{ Route::is("halaman*") ? 'active' : '' }}" href="{{ route('halaman.index') }}" rel="noopener">
                                                Data Halaman
                                            </a>
                                        @endcan
                                        {{-- @can('lihat halaman')
                                            <a class="dropdown-item {{ Route::is("complain*") ? 'active' : '' }}" href="{{ route('complain.index') }}" rel="noopener">
                                                Data Halaman Complain
                                            </a>
                                        @endcan
                                        @can('lihat halaman')
                                            <a class="dropdown-item {{ Route::is("switch*") ? 'active' : '' }}" href="{{ route('switch.index') }}" rel="noopener">
                                                Data Halaman Pergantian Perangkat
                                            </a>
                                        @endcan --}}
                                        @can('lihat halaman')
                                            <a class="dropdown-item {{ Route::is("spam*") ? 'active' : '' }}" href="{{ route('spam.index') }}" rel="noopener">
                                                Data Spam
                                            </a>
                                        @endcan
                                        @can('chatting')
                                            <a class="dropdown-item {{ Route::is("chatting*") ? 'active' : '' }}" href="{{ route('chatting.index') }}" rel="noopener">
                                                Chatting
                                            </a>
                                        @endcan
                                        @can('lihat histori pemasangan')
                                            <a class="dropdown-item {{ Route::is("history*") ? 'active' : '' }}" href="{{ route('history.index') }}" rel="noopener">
                                                History Pemasangan
                                            </a>
                                        @endcan
                                        @can('lihat log wablas')
                                            <a class="dropdown-item {{ Route::is("report*") ? 'active' : '' }}" href="{{ route('report.index') }}" rel="noopener">
                                                Log Wablas
                                            </a>
                                        @endcan
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