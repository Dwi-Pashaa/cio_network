<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsedurSpamValidation extends Model
{
    use HasFactory;

    protected $table = 'prosedur_spam_validations';

    protected $guarded = [];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    // ── RELATIONS ──────────────────────────────────────────────

    public function prosedurSpam()
    {
        return $this->belongsTo(ProsedurSpam::class, 'prosedur_spam_id');
    }

    public function validatedByUser()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // ── HELPERS ────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
