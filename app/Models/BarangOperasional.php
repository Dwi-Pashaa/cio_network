<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangOperasional extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'barang_operasionals';

    protected $fillable = [
        'organization_id',
        'tipe_barang_id',
        'kode_barang',
        'nama_barang',
        'merk',
        'serial_number',
        'mac_address',
        'satuan',
        'total_stok',
        'spesifikasi',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function tipeBarang()
    {
        return $this->belongsTo(TipeBarangOperasional::class, 'tipe_barang_id');
    }

    public function userStocks()
    {
        return $this->hasMany(UserBarangOperasional::class, 'barang_operasional_id');
    }

    public function transferHistory()
    {
        return $this->hasMany(TransferBarangOperasional::class, 'barang_operasional_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_barang)) {
                $date = now()->format('Ymd');
                $last = self::whereDate('created_at', now()->toDateString())
                    ->orderBy('id', 'desc')
                    ->first();

                $lastNumber = $last ? (int) substr($last->kode_barang, -4) : 0;
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                $model->kode_barang = "BOP{$date}{$newNumber}";
            }
        });
    }
}
