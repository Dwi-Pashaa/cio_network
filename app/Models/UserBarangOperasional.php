<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBarangOperasional extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'user_barang_operasionals';

    protected $fillable = [
        'organization_id',
        'user_id',
        'barang_operasional_id',
        'stok',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barangOperasional()
    {
        return $this->belongsTo(BarangOperasional::class, 'barang_operasional_id');
    }
}
