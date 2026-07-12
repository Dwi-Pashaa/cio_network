<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPLC extends Model
{
    use HasFactory;
    protected $table = 'user_plc';
    protected $fillable = [
        'user_id',
        'plc_id',
        'total',
        'organization_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plc()
    {
        return $this->belongsTo(PLC::class, 'plc_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_plc', 'plc_id', 'user_id')
            ->withPivot('total')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
