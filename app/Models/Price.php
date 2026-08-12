<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'price';
    protected $fillable = ['name', 'description', 'is_public', 'use_bukti_bayar', 'organization_id'];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'use_bukti_bayar' => 'boolean',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
