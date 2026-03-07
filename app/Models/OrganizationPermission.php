<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class OrganizationPermission extends Model
{
    use HasFactory;
    protected $table = 'organization_permission';
    protected $fillable = [
        'organization_id',
        'permission_id',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
