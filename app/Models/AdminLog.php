<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminLog extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    const LOG_TYPE_LOGIN = 1; // Backstage login
    const LOG_TYPE_LOGOUT = 2; // Backstage logout
    const LOG_TYPE_ACTION = 3; // Background Operations
    const LOG_TYPE_SYSTEM = 4; // System abnormality

    public static $logTypeMap = [
        self::LOG_TYPE_LOGIN => 'Backstage login',
        self::LOG_TYPE_LOGOUT => 'Backstage logout',
        self::LOG_TYPE_ACTION => 'Background Operations',
        self::LOG_TYPE_SYSTEM => 'System abnormality'
    ];

    // 详情页面的数据解释
    public static $list_field = [
        'id' => 'ID',
        'user_id' => 'Administrator ID',
        'user_name' => 'Administrator Username',
        'url' => 'Operation Address',
        'data' => 'Operational Data',
        'ip' => 'Operation IP',
        'address' => 'IP real address',
        'ua' => 'Operating Environment',
        'type' => 'Operation Type',
        'type_text' => 'Operation Type Description',
        'description' => 'Operation Description',
        'remark' => 'Operation Notes',
        'created_at' => 'Creation time',
        'updated_at' => 'Update time'
    ];

    protected $appends = ['type_text', 'user_name'];

    public function getTypeTextAttribute()
    {
        return isset_and_not_empty(self::$logTypeMap, $this->attributes['type'], $this->attributes['type']);
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
