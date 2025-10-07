<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MicRadius extends Model
{
    use HasFactory;
    protected $table = 'mic_radius';
    protected $fillable = ['hometowns_id', 'code', 'name', 'latitude', 'longitude'];

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometowns_id', 'id');
    }
}
