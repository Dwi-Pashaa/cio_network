<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MicRadius extends Model
{
    use HasFactory;
    protected $table = 'mic_radius';
    protected $fillable = ['hometowns_id', 'code', 'name', 'latitude', 'longitude'];

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometowns_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'mix_radius_users',
            'mic_radius_id',
            'user_id'
        );
    }
}
