<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TroubleshootProgress extends Model
{
    protected $guarded = [];

    protected $casts = [
        'step' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function troubleshoot()
    {
        return $this->belongsTo(Troubleshoot::class);
    }
}
