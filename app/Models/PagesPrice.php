<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesPrice extends Model
{
    use HasFactory;
    protected $table = 'pages_price';
    protected $fillable = ['pages_id', 'price_id'];

    public function pages()
    {
        return $this->belongsTo(Pages::class, 'pages_id', 'id');
    }
}
