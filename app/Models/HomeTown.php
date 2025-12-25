<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeTown extends Model
{
    use HasFactory;
    protected $table = 'home_towns';
    protected $fillable = ['code', 'name', 'regencie_id', 'district_id'];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'hometowns_id', 'id');
    }

    public function regencie()
    {
        return $this->belongsTo(Regency::class, 'regencie_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
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

            $model->code = "KP{$date}{$newNumber}";
        });
    }
}
