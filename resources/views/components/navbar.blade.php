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
                            <li class="nav-item {{ Route::is('user*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('user.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Data User
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ Route::is('type*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('type.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-align-box-left-middle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M9 15h-2" /><path d="M13 12h-6" /><path d="M11 9h-4" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Data Type Pelanggan
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Data Pelanggan
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Master Jaringan
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="https://tabler.io/docs" target="_blank" rel="noopener">
                                        Data Router
                                    </a>
                                    <a class="dropdown-item" href="https://tabler.io/docs" target="_blank" rel="noopener">
                                        Data Vlan
                                    </a>
                                    <a class="dropdown-item" href="./changelog.html">
                                        Data ODC
                                    </a>
                                    <a class="dropdown-item" href="https://github.com/tabler/tabler"
                                        target="_blank" rel="noopener">
                                        Data ODP
                                    </a>
                                    <a class="dropdown-item" href="https://github.com/tabler/tabler"
                                        target="_blank" rel="noopener">
                                        Data OLT
                                    </a>
                                </div>
                            </li>
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
                                    <a class="dropdown-item {{ Route::is("kabupaten*") ? 'active' : '' }}" href="{{ route('kabupaten.index') }}" rel="noopener">
                                        Data Kabupaten
                                    </a>
                                    <a class="dropdown-item {{ Route::is("kecamatan*") ? 'active' : '' }}" href="{{ route('kecamatan.index') }}" rel="noopener">
                                        Data Kecamatan
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown {{ request()->is('master-settlement*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Master Pemukiman
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item {{ Route::is("kampung*") ? 'active' : '' }}" href="{{ route('kampung.index') }}" rel="noopener">
                                        Data Kampung
                                    </a>
                                    <a class="dropdown-item {{ Route::is("desa*") ? 'active' : '' }}" href="{{ route('desa.index') }}" rel="noopener">
                                        Data Desa
                                    </a>
                                    <a class="dropdown-item {{ Route::is("rt*") ? 'active' : '' }}" href="{{ route('rt.index') }}" rel="noopener">
                                        Data RT
                                    </a>
                                    <a class="dropdown-item {{ Route::is("rw*") ? 'active' : '' }}" href="{{ route('rw.index') }}" rel="noopener">
                                        Data RW
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>