<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MikrotikDevice extends Model
{
    use HasFactory;

    protected $table = 'mikrotik_devices';

    protected $fillable = [
        'mac_address',
        'ip_address',
        'host_name',
        'device_type',
        'status',
        'is_active',
        'source',
        'expires_after',
        'interface',
        'comment',
        'last_seen_at',
        'raw_payload',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'last_seen_at' => 'datetime',
        'raw_payload'  => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'mac_address', 'mac_address');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBound($query)
    {
        return $query->where('status', 'bound');
    }

    public function scopeDynamic($query)
    {
        return $query->where('device_type', 'dynamic');
    }

    public function scopeStatic($query)
    {
        return $query->where('device_type', 'static');
    }
}
