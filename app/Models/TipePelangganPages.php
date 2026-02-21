<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipePelangganPages extends Model
{
    use HasFactory;
    protected $table = 'tipe_pelanggan_pages';
    protected $fillable = [
        'pages_id',
        'tipe_pelanggan_id',
    ];

    public function pages()
    {
        return $this->belongsTo(Pages::class, 'pages_id', 'id');
    }

    public function tipe_pelanggan()
    {
        return $this->belongsTo(Type::class, 'tipe_pelanggan_id', 'id');
    }
}
