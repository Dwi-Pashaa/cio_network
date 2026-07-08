<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Mix Radius...</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem 2.5rem;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,.08), 0 4px 10px -6px rgba(0,0,0,.05);
            max-width: 420px;
            width: 100%;
        }
        .spinner {
            width: 44px; height: 44px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #4f46e5;
            border-radius: 50%;
            animation: spin .8s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        h2 { font-size: 1.1rem; color: #1e293b; margin-bottom: .3rem; font-weight: 600; }
        p { font-size: .88rem; color: #64748b; margin-bottom: 1.5rem; }
        .btn {
            display: inline-block;
            padding: .55rem 1.5rem;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: .88rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }
        .btn:hover { background: #4338ca; }
        .btn-outline {
            background: transparent;
            color: #4f46e5;
            border: 1px solid #4f46e5;
            margin-left: .5rem;
        }
        .btn-outline:hover { background: #eef2ff; }
        .info { font-size: .8rem; color: #94a3b8; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="spinner" id="spinner"></div>
        <h2>Mengarahkan ke Mix Radius...</h2>
        <p>Login sebagai <strong>{{ $micRadius->name }}</strong></p>
        <div>
            <button class="btn" onclick="document.getElementById('loginForm').submit()">Lanjutkan Manual</button>
            <a href="javascript:window.close()" class="btn btn-outline">Batal</a>
        </div>
        <div class="info">Jika tidak otomatis redirect dalam 3 detik, klik "Lanjutkan Manual"</div>
    </div>

    <form id="loginForm" action="https://mixcio.topsetting.com:973/rad-admin/post" method="POST" style="display:none;">
        <input type="text" name="username" value="{{ $micRadius->name }}">
        <input type="password" name="password" value="{{ $micRadius->mix_password }}">
    </form>

    <script>
        setTimeout(function() {
            document.getElementById('loginForm').submit();
        }, 2000);
    </script>
</body>
</html>
