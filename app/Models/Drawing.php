<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drawing extends Model
{
    protected $guarded = ['id'];

    public static $list_field = [
        'bill_no' => ['name' => 'Transaction serial number', 'type' => 'text', 'is_show' => true],
        'member_id' => ['name' => 'Member ID', 'type' => 'number', 'is_show' => true],

        'name' => ['name' => 'Transaction serial number', 'type' => 'text'],
        'money' => ['name' => 'Withdrawal amount', 'type' => 'number', 'is_show' => true],
        'account' => ['name' => 'Account Information', 'type' => 'text'],
        'before_money' => ['name' => 'Amount before withdrawal', 'type' => 'number', 'is_show' => false],
        'after_money' => ['name' => 'Amount after withdrawal', 'type' => 'number', 'is_show' => false],
        'score' => ['name' => 'integral', 'type' => 'number', 'is_show' => false],
        'counter_fee' => ['name' => 'Handling Fees', 'type' => 'number', 'validate' => 'required', 'is_show' => true],
        'fail_reason' => ['name' => 'Cause of failure', 'type' => 'text'],
        'member_bank_info' => ['name' => 'User bank data json', 'type' => 'text'],
        'member_remark' => ['name' => 'User withdrawal notes', 'type' => 'text'],

        'confirm_at' => ['name' => 'Confirm transfer time', 'type' => 'datetime'],
        'status' => ['name' => 'Withdrawal Status', 'type' => 'select', 'is_show' => true, 'data' => 'platform.drawing_status'],
        'user_id' => ['name' => 'Administrator ID', 'type' => 'number', 'is_show' => true],
    ];

    const STATUS_UNDEAL = 1; // 待确认
    const STATUS_SUCCESS = 2; // 审核通过
    const STATUS_FAILED = 3; // 审核失败

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function member()
    {
        return $this->belongsTo('App\Models\Member', 'member_id', 'id');
    }

    public function scopeMemberName($query, $name)
    {
        return $name ? $query->whereHas('member', function ($q) use ($name) {
            $q->where('name', 'like', '%' . $name . '%');
        }) : $query;
    }

    public function scopeUserName($query, $name)
    {
        return $name ? $query->whereHas('user', function ($q) use ($name) {
            $q->where('name', 'like', '%' . $name . '%');
        }) : $query;
    }

    public function scopeMemberLang($query, $lang)
    {
        return $lang ? $query->whereHas('member', function ($q) use ($lang) {
            $q->where('lang', $lang);
        }) : $query;
    }

    protected $appends = ['status_text'];

    public function getStatusTextAttribute()
    {
        return isset_and_not_empty(config('platform.drawing_status'), $this->attributes['status'], $this->attributes['status']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
