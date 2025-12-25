<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODC extends Model
{
    use HasFactory;
    protected $table = 'odc_networks';
    protected $guarded = [];

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

    public function patchCore()
    {
        return $this->belongsTo(PatchCore::class, 'patch_core_id', 'id');
    }

    public function plc()
    {
        return $this->belongsTo(PLC::class, 'plc_id', 'id');
    }
}
