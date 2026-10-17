<?php

namespace App\Services;

use App\Models\TransferBarangOperasional;
use Illuminate\Support\Facades\Log;

class TransferBarangNotificationService
{
    protected FonteMessagingService $fonteService;

    public function __construct(FonteMessagingService $fonteService)
    {
        $this->fonteService = $fonteService;
    }

    /**
     * Kirim notifikasi WA ke penerima bahwa ada barang yang perlu di-ACC
     */
    public function notifyPendingTransfer(TransferBarangOperasional $transfer): bool
    {
        $transfer->loadMissing(['penerima', 'pengirim', 'barangOperasional.tipeBarang']);

        $penerima = $transfer->penerima;
        if (!$penerima || empty($penerima->telp)) {
            Log::info("TransferBarangNotification: Penerima ID {$transfer->penerima_id} tidak memiliki nomor telepon.");
            return false;
        }

        $pengirimNama = $transfer->pengirim ? $transfer->pengirim->name : 'Admin / Gudang Pusat';
        $barangNama = $transfer->barangOperasional ? $transfer->barangOperasional->nama_barang : 'Barang Operasional';
        $tipeNama = $transfer->barangOperasional?->tipeBarang ? $transfer->barangOperasional->tipeBarang->nama_tipe : '-';
        $satuan = $transfer->barangOperasional ? $transfer->barangOperasional->satuan : 'Unit';
        $tanggal = $transfer->tanggal_transfer ? $transfer->tanggal_transfer->format('d/m/Y H:i') : date('d/m/Y H:i');
        $catatan = $transfer->catatan ?: '-';
        $urlRiwayat = route('barang-operasional.riwayat');

        $message = "📦 *PEMBERITAHUAN TRANSFER BARANG OPERASIONAL*\n"
            . "Halo *{$penerima->name}*,\n\n"
            . "Anda menerima pengiriman/transfer barang operasional baru dengan rincian:\n"
            . "• *Kode Transaksi:* {$transfer->kode_transaksi}\n"
            . "• *Pengirim:* {$pengirimNama}\n"
            . "• *Barang:* {$barangNama} ({$tipeNama})\n"
            . "• *Jumlah:* {$transfer->jumlah} {$satuan}\n"
            . "• *Tanggal:* {$tanggal}\n"
            . "• *Catatan:* {$catatan}\n\n"
            . "⚠️ *Status: Menunggu Konfirmasi / ACC*\n"
            . "Mohon periksa fisik barang dan lakukan konfirmasi penerimaan (ACC) melalui sistem di tautan berikut:\n"
            . "🔗 {$urlRiwayat}\n\n"
            . "_Pesan otomatis dari CIO Network Solution_";

        $sent = $this->fonteService->sendMessage($penerima->telp, $message);
        if ($sent) {
            $transfer->update(['wa_notif_sent' => true]);
        }

        return $sent;
    }

    /**
     * Kirim notifikasi WA ke pengirim bahwa barang telah di-ACC
     */
    public function notifyTransferApproved(TransferBarangOperasional $transfer): bool
    {
        $transfer->loadMissing(['penerima', 'pengirim', 'barangOperasional']);

        $pengirim = $transfer->pengirim;
        if (!$pengirim || empty($pengirim->telp)) {
            return false;
        }

        $penerimaNama = $transfer->penerima ? $transfer->penerima->name : 'Penerima';
        $barangNama = $transfer->barangOperasional ? $transfer->barangOperasional->nama_barang : 'Barang Operasional';
        $satuan = $transfer->barangOperasional ? $transfer->barangOperasional->satuan : 'Unit';
        $tanggal = now()->format('d/m/Y H:i');
        $catatan = $transfer->catatan_konfirmasi ?: '-';

        $message = "✅ *KONFIRMASI: TRANSFER BARANG TELAH DI-ACC*\n"
            . "Halo *{$pengirim->name}*,\n\n"
            . "Transfer barang operasional berikut telah *DITERIMA & DI-ACC* oleh *{$penerimaNama}*:\n"
            . "• *Kode Transaksi:* {$transfer->kode_transaksi}\n"
            . "• *Barang:* {$barangNama}\n"
            . "• *Jumlah:* {$transfer->jumlah} {$satuan}\n"
            . "• *Waktu Diterima:* {$tanggal}\n"
            . "• *Catatan Penerima:* {$catatan}\n\n"
            . "Stok telah resmi masuk ke akun penerima.\n\n"
            . "_Pesan otomatis dari CIO Network Solution_";

        return $this->fonteService->sendMessage($pengirim->telp, $message);
    }

    /**
     * Kirim notifikasi WA ke pengirim bahwa barang DITOLAK
     */
    public function notifyTransferRejected(TransferBarangOperasional $transfer): bool
    {
        $transfer->loadMissing(['penerima', 'pengirim', 'barangOperasional']);

        $pengirim = $transfer->pengirim;
        if (!$pengirim || empty($pengirim->telp)) {
            return false;
        }

        $penerimaNama = $transfer->penerima ? $transfer->penerima->name : 'Penerima';
        $barangNama = $transfer->barangOperasional ? $transfer->barangOperasional->nama_barang : 'Barang Operasional';
        $satuan = $transfer->barangOperasional ? $transfer->barangOperasional->satuan : 'Unit';
        $alasan = $transfer->catatan_konfirmasi ?: 'Tidak ada alasan yang dicantumkan';

        $message = "❌ *PEMBERITAHUAN: TRANSFER BARANG DITOLAK*\n"
            . "Halo *{$pengirim->name}*,\n\n"
            . "Transfer barang dengan Kode Transaksi *{$transfer->kode_transaksi}* telah *DITOLAK* oleh *{$penerimaNama}*.\n"
            . "• *Barang:* {$barangNama} ({$transfer->jumlah} {$satuan})\n"
            . "• *Alasan Penolakan:* {$alasan}\n\n"
            . "⚠️ Saldo/stok Anda telah dikembalikan secara otomatis ke akun Anda.\n\n"
            . "_Pesan otomatis dari CIO Network Solution_";

        return $this->fonteService->sendMessage($pengirim->telp, $message);
    }
}
