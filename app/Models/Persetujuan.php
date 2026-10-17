<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'persetujuan';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function pages()
    {
        return $this->hasMany(Pages::class, 'persetujuan_id', 'id');
    }
}
