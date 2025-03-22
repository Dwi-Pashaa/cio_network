<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesODP extends Model
{
    use HasFactory;
    protected $table = 'pages_odps';
    protected $guarded = [];
    public $timestamps = false;

    public function pages()
    {
        return $this->belongsTo(Pages::class, 'pages_id');
    }
}
