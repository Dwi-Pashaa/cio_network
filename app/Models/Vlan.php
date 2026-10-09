<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vlan extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'vlan_networks';
    protected $fillable = [
        'code',
        'name',
        'organization_id',
        'ip_address',
        'support_pppoe',
        'support_voucher',
        'regencie_id',
        'district_id',
        'village_id',
        'hometown_id',
    ];

    protected $casts = [
        'support_pppoe'   => 'boolean',
        'support_voucher' => 'boolean',
    ];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'vlans_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function regencie()
    {
        return $this->belongsTo(Regency::class, 'regencie_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometown_id');
    }

    public function olts()
    {
        return $this->belongsToMany(OLT::class, 'vlan_olts', 'vlan_id', 'olt_id')->withTimestamps();
    }

    public function mixRadiuses()
    {
        return $this->belongsToMany(MicRadius::class, 'vlan_mix_radiuses', 'vlan_id', 'mic_radius_id')->withTimestamps();
    }

    public function pakets()
    {
        return $this->belongsToMany(Paket::class, 'vlan_pakets', 'vlan_id', 'paket_id')->withTimestamps();
    }

    public function prices()
    {
        return $this->belongsToMany(Price::class, 'vlan_prices', 'vlan_id', 'price_id')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $date = now()->format('Y');
            $lastTransaction = self::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->first();

            $lastNumber = $lastTransaction ? (int)substr($lastTransaction->code, -4) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $model->code = "VLN{$date}{$newNumber}";
        });
    }
}
