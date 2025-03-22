<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesODC extends Model
{
    use HasFactory;
    protected $table = 'pages_odcs';
    protected $guarded = [];
    public $timestamps = false;

    public function pages()
    {
        return $this->belongsTo(Pages::class, 'pages_id');
    }

    public function router()
    {
        return $this->belongsTo(Router::class, 'routers_id');
    }
}
