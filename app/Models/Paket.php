<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Paket extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'paket';
    protected $fillable = ['name', 'organization_id'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

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
