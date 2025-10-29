<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRouter extends Model
{
    use HasFactory;
    protected $table = 'user_router';
    protected $fillable = ['user_id', 'router_id', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function router()
    {
        return $this->belongsTo(Router::class, 'router_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_router', 'router_id', 'user_id')
            ->withPivot('total')
            ->withTimestamps();
    }
}
