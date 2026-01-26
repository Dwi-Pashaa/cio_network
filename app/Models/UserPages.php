<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPages extends Model
{
    use HasFactory;
    protected $table = 'user_pages';
    protected $fillable = [
        'user_id',
        'pages_id',
    ];
}
