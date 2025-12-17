<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MacAddress extends Model
{
    use HasFactory;
    protected $table = 'mac_address';
    protected $fillable = [
        'mac_address',
        'status',
        'status_device',
        'router_id',
        'user_id',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'mac_address', 'mac_address');
    }

    public function router()
    {
        return $this->belongsTo(Router::class, 'router_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
