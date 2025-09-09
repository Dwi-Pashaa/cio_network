<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OLT extends Model
{
    use HasFactory;
    protected $table = 'olt_networks';
    protected $fillable = ['code', 'hometowns_id', 'name'];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'olts_id', 'id');
    }

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometowns_id', 'id');
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

            $model->code = "OLT{$date}{$newNumber}";
        });
    }
}
