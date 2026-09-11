<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('code') - @yield('title') &mdash; {{ config('app.name', 'Andira CN Solution') }}</title>
    
    <!-- Google Fonts Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tabler CSS -->
    <link href="{{ asset('css/tabler.min.css') }}" rel="stylesheet" />
    
    <style>
        :root {
            --error-primary: #0284c7;
            --error-primary-dark: #0369a1;
            --error-primary-light: #e0f2fe;
            --error-text-main: #0f172a;
            --error-text-sub: #475569;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 50%, #e2e8f0 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--error-text-main);
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Background Orbs */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.55;
            pointer-events: none;
        }

        .ambient-orb-1 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.35) 0%, rgba(2, 132, 199, 0.05) 70%);
            top: -100px;
            left: -100px;
        }

        .ambient-orb-2 {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(79, 70, 229, 0.03) 70%);
            bottom: -120px;
            right: -120px;
        }

        .error-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 640px;
            padding: 1.5rem;
            margin: auto;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(226, 232, 240, 0.6);
            padding: 3rem 2.5rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        /* Brand Logo Area */
        .error-brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            text-decoration: none;
            margin-bottom: 2rem;
            padding: 8px 18px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .error-brand:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .error-brand img {
            height: 32px;
            width: auto;
            border-radius: 4px;
            object-fit: contain;
        }

        /* Error Icon Circle */
        .error-icon-box {
            width: 96px;
            height: 96px;
            margin: 0 auto 1.5rem auto;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.06);
        }

        /* Color Schemes */
        .theme-404 .error-icon-box { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .theme-403 .error-icon-box { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .theme-500 .error-icon-box { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }
        .theme-419 .error-icon-box { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .theme-503 .error-icon-box { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .theme-429 .error-icon-box { background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff; }

        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .theme-404 .error-badge { background: #dbeafe; color: #1d4ed8; }
        .theme-403 .error-badge { background: #fee2e2; color: #b91c1c; }
        .theme-500 .error-badge { background: #ffe4e6; color: #be123c; }
        .theme-419 .error-badge { background: #fef3c7; color: #b45309; }
        .theme-503 .error-badge { background: #dcfce7; color: #15803d; }
        .theme-429 .error-badge { background: #f3e8ff; color: #7e22ce; }

        .error-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--error-text-main);
            margin-bottom: 0.75rem;
            line-height: 1.25;
            letter-spacing: -0.02em;
        }

        .error-desc {
            font-size: 0.96rem;
            line-height: 1.6;
            color: var(--error-text-sub);
            max-width: 480px;
            margin: 0 auto 2rem auto;
        }

        /* Action Buttons */
        .error-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .btn-error-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.4rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #ffffff !important;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-error-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.45);
            color: #ffffff !important;
        }

        .btn-error-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.4rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #334155 !important;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-error-secondary:hover {
            transform: translateY(-2px);
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a !important;
        }

        .error-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        @media (max-width: 576px) {
            .error-card {
                padding: 2rem 1.5rem;
            }
            .error-title {
                font-size: 1.4rem;
            }
            .error-actions {
                flex-direction: column;
                width: 100%;
            }
            .btn-error-primary, .btn-error-secondary {
                width: 100%;
            }
        }
    </style>
</head>

<body class="theme-@yield('code')">
    <div class="ambient-orb ambient-orb-1"></div>
    <div class="ambient-orb ambient-orb-2"></div>

    <div class="error-wrapper">
        <div class="error-card">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="error-brand" title="Kembali ke Beranda">
                <img src="{{ asset('img/logo_2.jpeg') }}" alt="Logo Andira" onerror="this.style.display='none'">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo CN Solution" onerror="this.style.display='none'">
            </a>

            <!-- Icon -->
            <div class="error-icon-box">
                @yield('icon')
            </div>

            <!-- Error Code Badge -->
            <div>
                <span class="error-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    Error @yield('code')
                </span>
            </div>

            <!-- Title -->
            <h1 class="error-title">@yield('title')</h1>

            <!-- Description -->
            <p class="error-desc">
                @yield('message')
            </p>

            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn-error-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>

                <button type="button" class="btn-error-secondary" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ url('/') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Halaman Sebelumnya</span>
                </button>

                @yield('extra_action')
            </div>

            <!-- Footer Meta -->
            <div class="error-footer">
                &copy; {{ date('Y') }} &bull; Layanan Jaringan & Manajemen Pelanggan
            </div>
        </div>
    </div>
</body>

</html>
