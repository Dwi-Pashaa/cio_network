<header class="navbar navbar-expand-md sticky-top modern-header d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="." style="text-decoration: none">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo">
            </a>
        </div>
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link user-profile-btn d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar" style="background-image: url({{ asset('static/avatars/000m.jpg') }})"></span>
                    <div class="d-none d-xl-block user-info">
                        <div class="user-name" style="font-weight: 700;">{{ Auth::user()->name }}</div>
                        <div class="user-role mt-1" style="font-size: 0.75rem;">
                            <span class="text-primary fw-bold"
                                style="background: #eef2ff; padding: 2px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                {{ Auth::user()->getRoleNames()->first() ?? 'Tidak Ada Role' }}
                                <span style="color: #a5b4fc; margin: 0 1px;">•</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 21h18" />
                                    <path d="M5 21v-14l8-4v18" />
                                    <path d="M19 21v-10l-6-4" />
                                    <path d="M9 9h.01" />
                                    <path d="M9 12h.01" />
                                    <path d="M9 15h.01" />
                                    <path d="M9 18h.01" />
                                </svg>
                                {{ Auth::user()->organization->name ?? 'Admin / Tanpa Organisasi' }}
                            </span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end modern-dropdown">
                    <!-- Dropdown Header/Info for Mobile & Ext Info -->
                    <div class="dropdown-header d-xl-none" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <h6 class="text-overflow m-0" style="font-weight: 700; color: #1a1a2e; font-size: 0.95rem;">
                            {{ Auth::user()->name }}
                        </h6>
                        <span
                            style="font-size: 0.8rem; color: #6b7280; display: block;">{{ Auth::user()->email }}</span>
                        <div
                            style="font-size: 0.75rem; color: #4f46e5; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 4px; background: #eef2ff; padding: 4px 8px; border-radius: 6px; width: fit-content; flex-wrap: wrap;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                            {{ Auth::user()->getRoleNames()->first() ?? 'Tidak Ada Role' }}
                            <span style="color: #a5b4fc; margin: 0 1px;">•</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21h18" />
                                <path d="M5 21v-14l8-4v18" />
                                <path d="M19 21v-10l-6-4" />
                                <path d="M9 9h.01" />
                                <path d="M9 12h.01" />
                                <path d="M9 15h.01" />
                                <path d="M9 18h.01" />
                            </svg>
                            {{ Auth::user()->organization->name ?? 'Admin / Tanpa Organisasi' }}
                        </div>
                    </div>
                    <div class="dropdown-divider d-xl-none my-2"></div>

                    <a href="{{ route('logout') }}" class="dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2">
                            </path>
                            <path d="M9 12h12l-3 -3"></path>
                            <path d="M18 15l3 -3"></path>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
