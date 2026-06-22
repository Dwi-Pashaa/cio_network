<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'price';
    protected $fillable = ['name', 'organization_id'];
}
