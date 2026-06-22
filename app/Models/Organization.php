<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'organization';
    protected $fillable = [
        'name',
        'type',
    ];
}
