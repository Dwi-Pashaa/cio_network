<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMixRadius extends Model
{
    use HasFactory;
    protected $table = 'user_mic_radius';
    protected $fillable = ['user_id', 'mic_radius_id'];
}
