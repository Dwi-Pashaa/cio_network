<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OLT extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'olt_networks';
    protected $guarded = [];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'olts_id', 'id');
    }

    public function hometown()
    {
        return $this->belongsTo(HomeTown::class, 'hometowns_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'olt_users',
            'olt_id',
            'user_id'
        );
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
