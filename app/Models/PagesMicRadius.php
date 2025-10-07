<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesMicRadius extends Model
{
    use HasFactory;
    protected $table = 'pages_mic_radius';
    protected $fillable = ['pages_id', 'mic_radius_id'];
}
