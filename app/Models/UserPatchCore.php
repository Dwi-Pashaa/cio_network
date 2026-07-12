<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPatchCore extends Model
{
    use HasFactory;
    protected $table = 'user_patch_core';
    protected $fillable = [
        'user_id',
        'patch_core_id',
        'total',
        'organization_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function patchCore()
    {
        return $this->belongsTo(PatchCore::class, 'patch_core_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_patch_core', 'patch_core_id', 'user_id')
            ->withPivot('total')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
