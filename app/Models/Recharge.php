<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    public $guarded = ["id"];

    public static $list_field = [
        'bill_no' => ['name' => 'Transaction serial number', 'type' => 'text', 'is_show' => true],
        'member_id' => ['name' => 'Member ID', 'type' => 'number', 'validate' => 'required', 'is_show' => true],
        'name' => ['name' => 'Name of transferor', 'type' => 'text', 'is_show' => true],
        'account' => ['name' => 'Transfer Account', 'type' => 'text', 'is_show' => true],

        /**
        'origin_money' => ['name' => 'Recharge amount before conversion','type' => 'text','is_show' => false],
        'forex' => ['name' => 'Transaction (conversion) ratio','type' => 'text','is_show' => false],
         **/
        'lang' => ['name' => 'Language/Currency', 'type' => 'select', 'is_show' => false, 'data' => 'platform.lang_fields'],

        'money' => ['name' => 'Recharge amount', 'type' => 'number', 'is_show' => true],
        // 'payment_type' => ['name' => 'Payment Type','type' => 'select','is_show' => true,'data' => 'platform.recharge_type'],
        'payment_type' => ['name' => 'Payment Type', 'type' => 'select', 'is_show' => true, 'data' => 'platform.payment_type'],
        'payment_pic' => ['name' => 'Payment voucher', 'type' => 'picture', 'is_show' => true],

        'diff_money' => ['name' => 'Credit', 'type' => 'number', 'validate' => 'required', 'is_show' => true],
        'before_money' => ['name' => 'Amount before recharge', 'type' => 'number', 'is_show' => false],
        'after_money' => ['name' => 'Amount after recharge', 'type' => 'number', 'is_show' => false],
        'score' => ['name' => 'integral', 'type' => 'number', 'is_show' => false],

        'fail_reason' => ['name' => 'Cause of failure', 'type' => 'text'],
        'hk_at' => ['name' => 'Remittance time filled in by the customer', 'type' => 'datetime'],
        'confirm_at' => ['name' => 'Confirm transfer time', 'type' => 'datetime'],

        'status' => ['name' => 'Payment Status', 'type' => 'select', 'is_show' => true, 'data' => 'platform.recharge_status'],
        'user_id' => ['name' => 'Administrator ID', 'type' => 'number', 'is_show' => true],
    ];

    const STATUS_UNDEAL = 1; // 待确认
    const STATUS_SUCCESS = 2; // 审核通过
    const STATUS_FAILED = 3; // 审核失败

    const PREFIX_THIRDPAY = 'online_';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function member()
    {
        return $this->belongsTo('App\Models\Member', 'member_id', 'id');
    }

    protected $appends = ['status_text', 'payment_type_text'];

    public function getStatusTextAttribute()
    {
        return isset_and_not_empty(config('platform.recharge_status'), $this->attributes['status'], $this->attributes['status']);
    }

    public function getPaymentTypeTextAttribute()
    {
        // return isset_and_not_empty(config('platform.recharge_type'),$this->attributes['payment_type'],$this->attributes['payment_type']);
        return isset_and_not_empty(config('platform.payment_type'), $this->attributes['payment_type'], $this->attributes['payment_type']);
    }

    public function getPaymentDetailAttribute()
    {
        return $this->attributes['payment_detail'] && !is_array($this->attributes['payment_detail']) ? json_decode($this->attributes['payment_detail'], 1) : $this->attributes['payment_detail'];
    }

    public function getPaymentRate()
    {
        if (array_key_exists('payment_id', $this->payment_detail)) {
            return Payment::find($this->payment_detail['payment_id'])->rate;
        }
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

    public function isThirdPay()
    {
        return \Str::contains($this->payment_type, self::PREFIX_THIRDPAY);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function addRechargeML()
    {
        $percent = systemconfig('ml_percent');

        if (!$percent) return;

        $percent = sprintf("%.2f", $percent / 100);

        // 增加会员的码量
        $this->member->increment('ml_money', sprintf("%.2f", $this->money * $percent));
    }
}
