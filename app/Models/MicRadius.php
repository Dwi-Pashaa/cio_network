<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MicRadius extends Model
{
    use HasFactory;
    protected $table = 'mic_radius';
    protected $fillable = ['organization_id', 'hometowns_id', 'code', 'name', 'latitude', 'longitude', 'mix_password'];

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

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_mic_radius',
            'mic_radius_id',
            'user_id'
        );
    }
}
