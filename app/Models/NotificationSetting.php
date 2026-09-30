<?php

namespace App\Models;

use App\Traits\LogsActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory, LogsActivityHelper;

    protected $table = 'notification_settings';

    protected $fillable = [
        'user_id',
        'organization_id',
        'channel',
        'notify_prosedur',
        'notify_troubleshoot',
        'notify_pendaftaran',
        'notify_complain',
    ];

    protected $casts = [
        'notify_prosedur'     => 'boolean',
        'notify_troubleshoot' => 'boolean',
        'notify_pendaftaran'  => 'boolean',
        'notify_complain'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
