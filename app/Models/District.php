<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'districts';
    protected $fillable = ['code', 'name', 'regencie_id', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'districts_id', 'id');
    }

    public function regencie()
    {
        return $this->belongsTo(Regency::class, 'regencie_id', 'id');
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

            $model->code = "KC{$date}{$newNumber}";
        });
    }
}
