<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODC extends Model
{
    use HasFactory;
    protected $table = 'odc_networks';
    protected $fillable = ['code', 'hometowns_id', 'rts_id', 'rws_id', 'home_odc'];

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
}
