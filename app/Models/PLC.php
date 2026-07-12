<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PLC extends Model
{
    use HasFactory;
    protected $table = 'plc';
    protected $fillable = [
        'name',
        'type',
        'serial_number',
        'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
