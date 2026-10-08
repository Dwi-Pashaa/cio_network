<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBarangOperasional extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'transfer_barang_operasionals';

    protected $fillable = [
        'organization_id',
        'kode_transaksi',
        'barang_operasional_id',
        'pengirim_id',
        'penerima_id',
        'jumlah',
        'catatan',
        'tanggal_transfer',
    ];

    protected $casts = [
        'tanggal_transfer' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function barangOperasional()
    {
        return $this->belongsTo(BarangOperasional::class, 'barang_operasional_id');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_transaksi)) {
                $date = now()->format('Ymd');
                $last = self::whereDate('created_at', now()->toDateString())
                    ->orderBy('id', 'desc')
                    ->first();

                $lastNumber = $last ? (int) substr($last->kode_transaksi, -4) : 0;
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                $model->kode_transaksi = "TRF{$date}{$newNumber}";
            }

            if (empty($model->tanggal_transfer)) {
                $model->tanggal_transfer = now();
            }
        });
    }
}
