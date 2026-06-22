<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'setting';
    protected $fillable = [
        'key',
        'value',
    ];
}
