<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeBarangOperasional extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'tipe_barang_operasionals';

    protected $fillable = [
        'organization_id',
        'nama_tipe',
        'has_mac_address',
        'has_serial_number',
        'keterangan',
    ];

    protected $casts = [
        'has_mac_address'   => 'boolean',
        'has_serial_number' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function barang()
    {
        return $this->hasMany(BarangOperasional::class, 'tipe_barang_id');
    }
}
