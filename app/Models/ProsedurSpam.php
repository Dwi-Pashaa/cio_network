<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsedurSpam extends Model
{
    use HasFactory;

    protected $table = 'prosedur_spams';

    protected $guarded = [];

    protected $casts = [
        'payload'     => 'array',
        'rejected_at' => 'datetime',
        'executed_at' => 'datetime',
    ];

    // ── RELATIONS ──────────────────────────────────────────────

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function executedBy()
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function validations()
    {
        return $this->hasMany(ProsedurSpamValidation::class, 'prosedur_spam_id')
                    ->orderBy('level');
    }

    // ── HELPERS ────────────────────────────────────────────────

    /**
     * Mengecek apakah semua level validasi sudah berstatus 'approved'.
     */
    public function isFullyApproved(): bool
    {
        $total    = $this->validations()->count();
        $approved = $this->validations()->where('status', 'approved')->count();

        return $total > 0 && $total === $approved;
    }

    /**
     * Mengambil checkpoint yang bisa diaksi oleh user berdasarkan permission-nya.
     * Mengembalikan null jika tidak ada atau sudah divalidasi.
     */
    public function getPendingCheckpointForUser(User $user): ?ProsedurSpamValidation
    {
        return $this->validations()
            ->where('status', 'pending')
            ->get()
            ->first(fn($v) => $user->can($v->required_permission));
    }

    /**
     * Membuat 4 baris checkpoint di prosedur_spam_validations
     * berdasarkan config/prosedur_levels.php.
     * Dipanggil sekali saat request baru dibuat.
     */
    public function createValidationCheckpoints(): void
    {
        $levels = config('prosedur_levels.levels', []);

        foreach ($levels as $level => $cfg) {
            $this->validations()->create([
                'level'               => $level,
                'level_label'         => $cfg['label'],
                'required_permission' => $cfg['permission'],
                'status'              => 'pending',
            ]);
        }
    }

    // ── SCOPES ─────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Filter berdasarkan organisasi user (kecuali tipe 'internal').
     */
    public function scopeForUser($query, User $user)
    {
        $orgType = optional($user->organization)->type;
        if ($orgType !== 'internal') {
            $query->where('organization_id', $user->organization_id);
        }
        return $query;
    }
}
