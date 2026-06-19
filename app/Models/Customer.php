<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customers';
    protected $guarded = [];

    public function router()
    {
        return $this->belongsTo(Router::class, 'routers_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'types_id', 'id');
    }

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometowns_id', 'id');
    }

    public function rt()
    {
        return $this->belongsTo(RT::class, 'rts_id', 'id');
    }

    public function rw()
    {
        return $this->belongsTo(RW::class, 'rws_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'villages_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'districts_id', 'id');
    }

    public function regencie()
    {
        return $this->belongsTo(Regency::class, 'regencies_id', 'id');
    }

    public function vlan()
    {
        return $this->belongsTo(Vlan::class, 'vlans_id', 'id');
    }

    public function odc()
    {
        return $this->belongsTo(ODC::class, 'odcs_id', 'id');
    }

    public function odp()
    {
        return $this->belongsTo(ODP::class, 'odps_id', 'id');
    }

    public function olt()
    {
        return $this->belongsTo(OLT::class, 'olts_id', 'id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'id');
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }

    public function mic_radius()
    {
        return $this->belongsTo(MicRadius::class, 'mic_radius_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tipePelanggan()
    {
        return $this->belongsTo(Type::class, 'tipe_pelanggan_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
