<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSequence extends Model
{
    protected $fillable = ['prefix', 'last_number'];
}
