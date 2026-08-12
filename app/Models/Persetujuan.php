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

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
