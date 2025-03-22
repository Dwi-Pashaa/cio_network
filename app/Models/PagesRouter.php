<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagesRouter extends Model
{
    use HasFactory;

    protected $table = 'pages_routers';
    protected $fillable = ['pages_id', 'routers_id'];
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
