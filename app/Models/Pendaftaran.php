<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'pendaftaran';

    protected $guarded = [];

    public function tipeLayanan()
    {
        return $this->belongsTo(Type::class, 'tipe_layanan_id', 'id');
    }

    public function tipe_layanan()
    {
        return $this->belongsTo(Type::class, 'tipe_layanan_id', 'id');
    }

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometowns_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'villages_id', 'id');
    }

    public function pages()
    {
        return $this->belongsTo(Pages::class, 'pages_id', 'id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'id');
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function persetujuan()
    {
        return $this->belongsTo(Persetujuan::class, 'persetujuan_id', 'id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
