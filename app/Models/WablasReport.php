<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WablasReport extends Model
{
    use HasFactory;
    protected $table = 'wablas_report';
    protected $fillable = [
        'wablas_id',
        'from',
        'to',
        'message',
        'status',
        'date',
        'sent_at',
    ];

    public $timestamps = false;
}
