<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Notifikasi CIO Network' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            padding: 24px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header .subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: #bfdbfe;
        }
        .content {
            padding: 30px;
        }
        .title-badge {
            display: inline-block;
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .message-box {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            border-radius: 4px;
            font-size: 14px;
            white-space: pre-line;
            margin-bottom: 24px;
            color: #334155;
        }
        .metadata-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 13px;
        }
        .metadata-table th, .metadata-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .metadata-table th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            width: 35%;
        }
        .metadata-table td {
            color: #0f172a;
            font-weight: 500;
        }
        .action-button-container {
            text-align: center;
            margin: 30px 0 10px 0;
        }
        .action-button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CIO NETWORK SOLUTION</h1>
            <div class="subtitle">Sistem Notifikasi & Monitoring Terpadu</div>
        </div>

        <div class="content">
            @if(!empty($title))
                <div class="title-badge">{{ $title }}</div>
            @endif

            @if(!empty($messageBody))
                <div class="message-box">{!! nl2br(e($messageBody)) !!}</div>
            @endif

            @if(!empty($metadata) && is_array($metadata))
                <table class="metadata-table">
                    <tbody>
                        @foreach($metadata as $label => $value)
                            @if(!empty($value))
                                <tr>
                                    <th>{{ $label }}</th>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if(!empty($actionUrl))
                <div class="action-button-container">
                    <a href="{{ $actionUrl }}" class="action-button" target="_blank">
                        {{ $actionText ?? 'Buka Tautan / Lihat Detail' }}
                    </a>
                </div>
            @endif
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px 0;">Pesan ini dikirim secara otomatis oleh sistem <strong>CIO Network</strong>.</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} CIO Network Solution. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
