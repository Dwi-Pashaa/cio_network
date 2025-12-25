<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'username',
        'name',
        'email',
        'telp',
        'password',
        'olt_id',
        'mic_radius_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function router()
    {
        return $this->belongsToMany(Router::class, 'user_router', 'user_id', 'router_id')
            ->withPivot('total')
            ->withTimestamps();
    }

    public function mixRadius(): BelongsToMany
    {
        return $this->belongsToMany(
            MicRadius::class,
            'mix_radius_users',      // pivot table
            'user_id',               // foreign key di pivot: user
            'mic_radius_id'          // foreign key di pivot: mix radius
        );
    }

    public function olts(): BelongsToMany
    {
        return $this->belongsToMany(
            OLT::class,
            'olt_users',             // pivot table
            'user_id',               // foreign key di pivot: user
            'olt_id'                 // foreign key di pivot: olt
        );
    }

    public function regencie(): BelongsToMany
    {
        return $this->belongsToMany(
            Regency::class,
            'user_citie',        // pivot table
            'user_id',           // FK user
            'regencie_id'        // FK regency
        )->withTimestamps();
    }

    public function patchCore(): BelongsToMany
    {
        return $this->belongsToMany(
            PatchCore::class,
            'user_patch_core',        // pivot table
            'user_id',           // FK user
            'patch_core_id'       // FK patch core
        )->withTimestamps();
    }
}
