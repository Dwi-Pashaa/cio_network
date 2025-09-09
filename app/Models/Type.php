<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $table = 'customer_types';
    protected $fillable = ['name'];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'types_id', 'id');
    }
}
