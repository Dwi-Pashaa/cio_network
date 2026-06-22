<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory, LogsActivityHelper;
    protected $table = 'customer_types';
    protected $fillable = ['name', 'status', 'organization_id'];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'types_id', 'id');
    }
}
