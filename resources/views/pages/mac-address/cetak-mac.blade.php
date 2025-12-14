<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Label MAC Address</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @page {
            margin: 5mm;
        }

        body {
            margin: 0;
        }

        .label {
            width: 6cm;
            height: 2cm;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: 22px;
            letter-spacing: 2px;
        }

        .label-wrapper {
            page-break-inside: avoid;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    @if ($macAddresses->count())
        <div class="row g-3">
            @foreach ($macAddresses as $item)
                <div class="col-4 label-wrapper">
                    <div class="label mx-auto">
                        {{ strtoupper($item->mac_address) }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center fst-italic">
            Belum Ada Data Mac Address
        </div>
    @endif
</div>

<script>
    window.print();
</script>

</body>
</html>
