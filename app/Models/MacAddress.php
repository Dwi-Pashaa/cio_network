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
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'mac_address', 'mac_address');
    }
}
