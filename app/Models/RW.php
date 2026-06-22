<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RW extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'rws';
    protected $fillable = ['name', 'organization_id'];
}
