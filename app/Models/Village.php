<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    protected $table = 'villages';
    protected $fillable = ['code', 'name', 'district_id', 'regencie_id'];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'villages_id', 'id');
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

            $model->code = "DS{$date}{$newNumber}";
        });
    }
}
