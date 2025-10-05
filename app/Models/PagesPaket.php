<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesPaket extends Model
{
    use HasFactory;
    protected $table = 'pages_paket';
    protected $fillable = ['pages_id', 'paket_id'];
}
