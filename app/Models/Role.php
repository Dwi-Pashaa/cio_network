<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope('organization', function (Builder $builder) {

    //         if (auth()->check() && !auth()->user()->hasRole('Admin')) {
    //             $builder->where('organization_id', auth()->user()->organization_id);
    //         }
    //     });
    // }

    public static function findByName(string $name, ?string $guardName = null): self
    {
        $guardName = $guardName ?? config('auth.defaults.guard');

        $role = static::withoutGlobalScopes()
            ->where('name', $name)
            ->where('guard_name', $guardName)
            ->where('organization_id', auth()->user()?->organization_id)
            ->first();

        if (! $role) {
            throw RoleDoesNotExist::named($name, $guardName);
        }

        return $role;
    }

    public static function findOrCreate(
        string $name,
        ?string $guardName = null,
        ?int $organizationId = null
    ): self {

        $guardName = $guardName ?? config('auth.defaults.guard');
        $organizationId = $organizationId ?? auth()->user()?->organization_id;

        $role = static::withoutGlobalScopes()
            ->where('name', $name)
            ->where('guard_name', $guardName)
            ->where('organization_id', $organizationId)
            ->first();

        if (! $role) {

            $role = new static();
            $role->name = $name;
            $role->guard_name = $guardName;
            $role->organization_id = $organizationId;
            $role->save();
        }

        return $role;
    }
}
