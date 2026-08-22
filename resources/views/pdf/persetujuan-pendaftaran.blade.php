<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { 
            size: A4; 
            margin: 0; 
        }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12px; 
            color: #000000; 
            margin: 1.5cm 2cm; 
            line-height: 1.7; 
        }
        
        /* Kop Surat (Letterhead) */
        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000000;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table td {
            border: none !important;
            padding: 0 !important;
            vertical-align: middle;
        }
        .kop-logos-cell {
            width: 140px;
            text-align: center;
            white-space: nowrap;
        }
        .kop-logo-img {
            max-height: 18px;
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }
        .kop-logo-img + .kop-logo-img {
            margin-left: 8px;
        }
        .kop-text-cell {
            text-align: center;
            padding-left: 20px !important;
        }
        .kop-text-cell h2 {
            margin: 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            font-weight: 800;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-text-cell p {
            margin: 4px 0 0 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 9px;
            color: #000000;
            line-height: 1.5;
        }

        /* Document Title */
        .doc-title-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .doc-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 4px 0;
            color: #000000;
        }
        .doc-code {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000000;
            font-weight: 400;
            margin: 0;
        }

        /* Sections */
        .section { margin-bottom: 22px; }
        .section h3 { 
            background: #f1f5f9; 
            color: #000000;
            padding: 6px 10px; 
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px; 
            font-weight: 700;
            border-radius: 4px; 
            margin: 0 0 8px 0; 
            border-left: 3px solid #000000;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        /* Table Details */
        .details-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 5px;
            margin-left: 10px;
        }
        .details-table td { 
            padding: 5px 8px; 
            vertical-align: top;
            font-size: 10px;
            border: none !important;
        }
        .details-table td:first-child { 
            font-weight: 700; 
            color: #000000; 
            width: 160px; 
        }
        .details-table td.colon {
            width: 10px;
            padding: 5px 0;
            color: #000000;
            font-weight: 700;
        }
        .details-table td:last-child {
            color: #000000;
            font-weight: 500;
        }
        .wifi-highlight {
            font-weight: 700;
            font-family: monospace;
            font-size: 10px;
        }

        .kontent { 
            padding: 3px 8px; 
            line-height: 1.7; 
            color: #000000;
            text-align: justify;
            font-size: 10px;
        }

        /* Signatures layout using Table for DomPDF alignment */
        .ttd-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .ttd-table td {
            text-align: center;
            vertical-align: top;
            border: none !important;
            padding: 0 10px !important;
        }
        .ttd-table td.qr-cell {
            width: 90px !important;
            vertical-align: middle;
            padding: 0 5px !important;
        }
        .ttd-table td.sig-cell {
            width: auto;
        }
        .ttd-box {
            display: inline-block;
            width: 200px;
            text-align: center;
        }
        .ttd-image { 
            max-height: 70px; 
            width: auto;
            display: block;
            margin: 0 auto;
        }
        .ttd-space {
            height: 70px;
        }
        .ttd-box p { 
            margin: 2px 0; 
            font-size: 9.5px;
            color: #000000;
        }
        .ttd-box .ttd-role {
            color: #000000;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .footer { 
            margin-top: 45px; 
            font-size: 8px; 
            color: #000000; 
            text-align: center; 
            border-top: 1px solid #000000; 
            padding-top: 12px; 
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                <td class="kop-logos-cell">
                    @if(file_exists(public_path('img/logo_2.jpeg')))
                        <img src="{{ public_path('img/logo_2.jpeg') }}" class="kop-logo-img" alt="Logo Andira">
                    @endif
                    @if(file_exists(public_path('img/logo.jpg')))
                        <img src="{{ public_path('img/logo.jpg') }}" class="kop-logo-img" alt="Logo CN">
                    @endif
                </td>
                <td class="kop-text-cell">
                    <h2>CIO NETWORK SOLUTION</h2>
                    <p>
                        Penyedia Layanan Internet Cepat, Stabil, dan Terpercaya<br>
                        Jl. Bojong Jl. Toha Ramdan, Ciparay, Kec. Ciparay, Kabupaten Bandung, Jawa Barat 40381<br>
                        Hubungi Kami: 085324780031 | Email: cs@cionetwork.id
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Judul Dokumen -->
    <div class="doc-title-container">
        <h3 class="doc-title">SURAT PERNYATAAN PERSETUJUAN BERLANGGANAN</h3>
        @php
            preg_match('/\d+/', $pendaftaran->kode, $matches);
            $numOnly = $matches ? $matches[0] : $pendaftaran->kode;
            $romanMonths = [
                1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 
                7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
            ];
            $monthRoman = $romanMonths[(int)$pendaftaran->created_at->format('n')];
            $day = $pendaftaran->created_at->format('d');
            $year = $pendaftaran->created_at->format('Y');
            $nomorSurat = "{$numOnly}/SBJL/CIO/{$day}/{$monthRoman}/{$year}";
        @endphp
        <p class="doc-code">Nomor: {{ $nomorSurat }}</p>
    </div>

    <!-- Data Pelanggan -->
    <div class="section">
        <h3>I. Informasi Pelanggan</h3>
        <table class="details-table">
            <tr><td>Nama Lengkap</td><td class="colon">:</td><td>{{ $pendaftaran->nama }}</td></tr>
            <tr><td>Alamat Email</td><td class="colon">:</td><td>{{ $pendaftaran->email ?? '-' }}</td></tr>
            <tr><td>Nomor Telepon / WA</td><td class="colon">:</td><td>{{ $pendaftaran->no_telepon }}</td></tr>
            <tr><td>Tipe Layanan Internet</td><td class="colon">:</td><td>{{ $pendaftaran->tipeLayanan?->name ?? '-' }}</td></tr>
            @if($pendaftaran->name_wifi)
                <tr><td>Nama WiFi (SSID)</td><td class="colon">:</td><td><span class="wifi-highlight">{{ $pendaftaran->name_wifi }}</span></td></tr>
            @endif
            @if($pendaftaran->password_wifi)
                <tr><td>Password WiFi</td><td class="colon">:</td><td><span class="wifi-highlight">{{ $pendaftaran->password_wifi }}</span></td></tr>
            @endif
            <tr><td>Kampung</td><td class="colon">:</td><td>{{ $pendaftaran->hometown?->name ?? '-' }}</td></tr>
            <tr><td>Desa</td><td class="colon">:</td><td>{{ $pendaftaran->village?->name ?? '-' }}</td></tr>
            <tr><td>Tipe Paket</td><td class="colon">:</td><td>{{ $pendaftaran->paket?->name ?? '-' }}</td></tr>
            <tr><td>Tipe Pembayaran / Harga</td><td class="colon">:</td><td>{{ $pendaftaran->price?->name ?? '-' }}</td></tr>
            <tr><td>Tanggal Registrasi</td><td class="colon">:</td><td>{{ $pendaftaran->created_at->format('d/m/Y H:i') }} WIB</td></tr>
        </table>
    </div>

    <!-- Isi Persetujuan -->
    <div class="section" >
        <h3>II. Ketentuan Layanan & Persetujuan</h3>
        <div class="kontent">
            {!! $pendaftaran->persetujuan?->konten ?? 'Tidak ada konten persetujuan.' !!}
        </div>
    </div>

    <!-- Tanda Tangan Bersebelahan -->
    @php
        use Endroid\QrCode\QrCode;
        use Endroid\QrCode\Writer\PngWriter;
        $qrCode = new QrCode(url('/search-pendaftaran?cstmrid=' . $pendaftaran->kode));
        $qrWriter = new PngWriter();
        $qrResult = $qrWriter->write($qrCode);
        $qrDataUri = 'data:image/png;base64,' . base64_encode($qrResult->getString());
    @endphp
    @php
        use App\Models\Customer;
        $customerObj = Customer::where('name', $pendaftaran->nama)->first();
        $ttdPath = $customerObj?->tanda_tangan_customer;
        $namaPelanggan = $customerObj?->name;
    @endphp
    <table class="ttd-table" style="page-break-inside: avoid;">
        <tr>
            <td class="sig-cell">
                <div class="ttd-box">
                    <p class="ttd-role">Pelanggan,</p>
                    @if($ttdPath && file_exists(public_path($ttdPath)))
                        <img src="{{ public_path($ttdPath) }}" class="ttd-image" alt="TTD Customer">
                    @else
                        <div class="ttd-space"></div>
                    @endif
                    <p style="margin: 4px 0 1px 0;"><strong><u>{{ $namaPelanggan }}</u></strong></p>
                    <p style="font-size: 8.5px; color: #000; margin: 0;">Pelanggan</p>
                </div>
            </td>
            <td class="qr-cell">
                <img src="{{ $qrDataUri }}" style="width: 70px; height: 70px; display: block; margin: 0 auto;" alt="QR Code">
                <p style="font-size: 7px; color: #666; margin: 2px 0 0 0;">{{ $pendaftaran->kode }}</p>
            </td>
            <td>
                <div class="ttd-box">
                    <p class="ttd-role">Penyedia Layanan,</p>
                    <p style="font-size: 8.5px; color: #000; margin: 0 0 2px 0;">{{ ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][(int)$pendaftaran->created_at->format('w')] }}, {{ $pendaftaran->created_at->format('d') }} {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][(int)$pendaftaran->created_at->format('n')-1] }} {{ $pendaftaran->created_at->format('Y') }}</p>
                    @if(file_exists(public_path('img/logo.jpg')))
                        <img src="{{ public_path('img/logo.jpg') }}" class="ttd-image" alt="TTD Admin" style="width: 50%; margin-top: 20px; margin-bottom: 20px;">
                    @else
                        <div class="ttd-space"></div>
                    @endif
                    <p style="margin: 4px 0 1px 0;"><strong><u>CIO Network Solution</u></strong></p>
                    <p style="font-size: 8.5px; color: #000; margin: 0;">Direktur</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer Dokumen -->
    <div class="footer">
        Dokumen elektronik ini digenerate secara otomatis oleh sistem pendaftaran mandiri CIO Network.<br>
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} WIB
    </div>
</body>
</html>
