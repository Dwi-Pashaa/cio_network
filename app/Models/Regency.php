<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'regencies';
    protected $fillable = ['organization_id', 'code', 'name'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'regencies_id', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(
            User::class,
            'user_citie',        // pivot table
            'regencie_id',       // FK regency
            'user_id'            // FK user
        )->withTimestamps();
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

            $model->code = "KB{$date}{$newNumber}";
        });
    }
}
