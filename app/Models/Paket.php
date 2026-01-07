<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Paket extends Model
{
    use HasFactory;
    protected $table = 'paket';
    protected $fillable = ['name'];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_paket',
            'paket_id',   // FK ke paket
            'user_id'     // FK ke user
        )->withTimestamps();
    }
}
