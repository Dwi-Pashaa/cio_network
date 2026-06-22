<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsedurChatTemplate extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'prosedur_chat_templates';

    protected $fillable = [
        'code',
        'name',
        'template',
        'description',
    ];
}
