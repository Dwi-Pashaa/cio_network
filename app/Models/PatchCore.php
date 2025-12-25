<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatchCore extends Model
{
    use HasFactory;
    protected $table = 'patch_core';
    protected $fillable = [
        'name',
    ];
}
