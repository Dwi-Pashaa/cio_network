<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Tabler CSS -->
    <link href="{{ asset('css/tabler.min.css?1738096682') }}" rel="stylesheet" />
    <!-- Modern Layout CSS -->
    <link href="{{ asset('css/modern-layout.css') }}" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, #0b1530 0%, #0f2b5c 35%, #1d4ed8 70%, #3b82f6 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            overflow: hidden;
        }

        /* Floating blobs */
        .auth-left::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.35) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            animation: float1 8s ease-in-out infinite;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, transparent 70%);
            bottom: -80px;
            right: -80px;
            animation: float2 10s ease-in-out infinite;
        }

        @keyframes float1 {

            0%,
            100% {
                transform: translate(0, 0)
            }

            50% {
                transform: translate(40px, 30px)
            }
        }

        @keyframes float2 {

            0%,
            100% {
                transform: translate(0, 0)
            }

            50% {
                transform: translate(-30px, -40px)
            }
        }

        /* Grid pattern overlay */
        .auth-left-inner {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            max-width: 420px;
        }

        .auth-brand-logo {
            display: inline-flex;
            gap: 16px;
            padding: 10px 18px;
            background: #ffffff;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .auth-brand-logo img {
            height: 30px;
            width: auto;
            object-fit: contain;
        }

        .auth-left h1 {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }

        .auth-left p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            line-height: 1.7;
        }

        /* Stats badges */
        .auth-stats {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .auth-stat {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            padding: 0.75rem 1.25rem;
            text-align: center;
            min-width: 90px;
        }

        .auth-stat .num {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
        }

        .auth-stat .lbl {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }

        /* Decorative dots */
        .auth-dots {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 2;
        }

        .auth-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
        }

        .auth-dots span:first-child {
            background: rgba(255, 255, 255, 0.8);
            width: 20px;
            border-radius: 3px;
        }

        /* ── RIGHT PANEL ── */
        .auth-right {
            width: 480px;
            background: #f8f9fc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 3rem;
            position: relative;
            overflow-y: auto;
        }

        .auth-right-inner {
            width: 100%;
            max-width: 360px;
        }

        .auth-greeting {
            margin-bottom: 2.5rem;
        }

        .auth-greeting .welcome {
            font-size: 0.8rem;
            font-weight: 600;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }

        .auth-greeting h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 0.4rem;
        }

        .auth-greeting p {
            font-size: 0.9rem;
            color: #64748b;
        }

        /* Alerts */
        .auth-alert {
            border-radius: 12px;
            padding: 0.85rem 1.1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .auth-alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .auth-alert-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #b45309;
        }

        /* Form */
        .auth-form-group {
            margin-bottom: 1.25rem;
        }

        .auth-form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }

        .auth-input-wrap {
            position: relative;
        }

        .auth-input-wrap .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .auth-input-wrap input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            background: white;
            color: #0f172a;
            transition: all 0.2s ease;
            outline: none;
        }

        .auth-input-wrap input::placeholder {
            color: #94a3b8;
        }

        .auth-input-wrap input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .auth-input-wrap input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .auth-input-error {
            font-size: 0.78rem;
            color: #ef4444;
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Toggle password */
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 2px;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #2563eb;
        }

        /* Submit button */
        .auth-btn {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: 0.02em;
            margin-top: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .auth-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), transparent);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(29, 78, 216, 0.35);
        }

        .auth-btn:hover::before {
            opacity: 1;
        }

        .auth-btn:active {
            transform: translateY(0);
        }

        /* Footer */
        .auth-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.78rem;
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .auth-left {
                display: none;
            }

            .auth-right {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .auth-right {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- LEFT PANEL -->
    <div class="auth-left">
        <div class="auth-left-inner">
            <div class="auth-brand-logo">
                <img src="{{ asset('img/logo_2.jpeg') }}" alt="Logo 2">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo 1">
            </div>
            <h1>{{ config('app.name') }}</h1>
            <p>Platform terintegrasi untuk manajemen jaringan, pelanggan, dan operasional ISP secara efisien.</p>

            <div class="auth-stats">
                <div class="auth-stat">
                    <div class="num">ISP</div>
                    <div class="lbl">Platform</div>
                </div>
                <div class="auth-stat">
                    <div class="num">24/7</div>
                    <div class="lbl">Monitoring</div>
                </div>
                <div class="auth-stat">
                    <div class="num">100%</div>
                    <div class="lbl">Secure</div>
                </div>
            </div>
        </div>

        <div class="auth-dots">
            <span></span><span></span><span></span><span></span>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-right">
        <div class="auth-right-inner">
            <div class="auth-greeting">
                <div class="welcome">Selamat Datang</div>
                <h2>Masuk ke Akun Anda</h2>
                <p>Silakan masukkan username dan password Anda untuk melanjutkan.</p>
            </div>

            @if (session()->has('error'))
                <div class="auth-alert auth-alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('warning'))
                <div class="auth-alert auth-alert-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    {{ session('warning') }}
                </div>
            @endif

            @yield('content')

            <div class="auth-footer">
                &copy; {{ date('Y') }} {{ config('app.name') }} &mdash; All rights reserved.
            </div>
        </div>
    </div>

    <script src="{{ asset('js/tabler.min.js?1738096682') }}" defer></script>
</body>

</html>
