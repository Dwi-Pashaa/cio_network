<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatchCore extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'patch_core';
    protected $fillable = [
        'name',
        'organization_id'
    ];

    public function user()
    {
        return $this->belongsToMany(
            User::class,
            'user_patch_core',        // pivot table
            'patch_core_id',       // FK patch_core
            'user_id'            // FK user
        )->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
