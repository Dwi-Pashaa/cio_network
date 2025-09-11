<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchDevice extends Model
{
    use HasFactory;
    protected $table = 'switch_device';
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function typeOld()
    {
        return $this->belongsTo(Type::class, 'type_old_id', 'id');
    }

    public function routerOld()
    {
        return $this->belongsTo(Router::class, 'router_old_id', 'id');
    }

    public function typeNew()
    {
        return $this->belongsTo(Type::class, 'type_new_id', 'id');
    }

    public function routerNew()
    {
        return $this->belongsTo(Router::class, 'router_new_id', 'id');
    }
}
