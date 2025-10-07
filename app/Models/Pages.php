<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    protected $table = 'pages';
    protected $guarded = [];

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometowns_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'villages_id', 'id');
    }

    public function regencie()
    {
        return $this->belongsTo(Regency::class, 'regencies_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'districts_id', 'id');
    }

    public function router()
    {
        return $this->hasMany(PagesRouter::class, 'pages_id', 'id');
    }

    public function vlan()
    {
        return $this->hasMany(PagesVlan::class, 'pages_id', 'id');
    }

    public function odc()
    {
        return $this->hasMany(PagesODC::class, 'pages_id', 'id');
    }

    public function odp()
    {
        return $this->hasMany(PagesODP::class, 'pages_id', 'id');
    }

    public function olt()
    {
        return $this->hasMany(PagesOLT::class, 'pages_id', 'id');
    }

    public function paket()
    {
        return $this->hasMany(PagesPaket::class, 'pages_id', 'id');
    }

    public function mic_radius()
    {
        return $this->hasMany(PagesMicRadius::class, 'pages_id', 'id');
    }
}
