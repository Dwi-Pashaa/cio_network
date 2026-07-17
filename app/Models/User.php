<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\LogsActivityHelper;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivityHelper;

    protected $fillable = [
        'username',
        'name',
        'email',
        'telp',
        'password',
        'olt_id',
        'mic_radius_id',
        'organization_id',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guard_name = 'web';

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
        )
            ->withPivot('total')
            ->withTimestamps();
    }

    public function paket(): BelongsToMany
    {
        return $this->belongsToMany(
            Paket::class,
            'user_paket',        // pivot table
            'user_id',           // FK user
            'paket_id'       // FK patch core
        )
            ->withTimestamps();
    }

    public function micRadius(): BelongsToMany
    {
        return $this->belongsToMany(
            MicRadius::class,
            'user_mic_radius',      // pivot table
            'user_id',               // foreign key di pivot: user
            'mic_radius_id'          // foreign key di pivot: mix radius
        );
    }

    public function micRadiusAccess(): BelongsToMany
    {
        return $this->belongsToMany(
            MicRadius::class,
            'user_mic_radius_access',
            'user_id',
            'mic_radius_id'
        );
    }

    public function routerAccess(): BelongsToMany
    {
        return $this->belongsToMany(Router::class, 'user_router_access', 'user_id', 'router_id')
            ->withTimestamps();
    }

    public function patchCoreAccess(): BelongsToMany
    {
        return $this->belongsToMany(PatchCore::class, 'user_patch_core_access', 'user_id', 'patch_core_id')
            ->withTimestamps();
    }

    public function pages()
    {
        return $this->belongsToMany(Pages::class, 'user_pages', 'user_id', 'pages_id')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
