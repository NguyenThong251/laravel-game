<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Payment extends Base
{
    public $guarded = ['id'];

    public static $list_field = [
        'desc' => ['name' => 'Payment method description', 'type' => 'text', 'is_show' => true],
        'account' => ['name' => 'Receiving account', 'type' => 'text', 'validate' => 'required', 'is_show' => false],
        'type' => ['name' => 'Payment Methods', 'type' => 'select', 'is_show' => true, 'data' => 'platform.payment_type'],
        'name' => ['name' => 'Payee Name', 'type' => 'text', 'validate' => 'required', 'is_show' => true],
        'qrcode' => ['name' => 'Payment QR Code', 'type' => 'picture', 'is_show' => true],
        'memo' => ['name' => 'Payment Notes', 'type' => 'text', 'is_show' => true],
        'rate' => ['name' => 'Gift ratio', 'type' => 'text', 'is_show' => true],
        'min' => ['name' => 'Min recharge amount', 'type' => 'text'],
        'max' => ['name' => 'Max recharge amount', 'type' => 'text'],
        // 'forex' => ['name' => '交易比例','type' => 'text','is_show' => true],
        'lang' => ['name' => 'language', 'type' => 'select', 'is_show' => true, 'data' => 'platform.lang_fields'],
        'is_open' => ['name' => 'Is it enabled?', 'type' => 'radio', 'is_show' => true, 'data' => 'platform.is_open', 'style' => 'platform.style_isopen']
    ];

    // const TYPE_THIRDPAY = 'thirdpay';
    const TYPE_BANKPAY = 'company_bankpay';
    const TYPE_USDT = 'company_usdt';

    const PREFIX_THIRDPAY = 'online_';
    const PREFIX_COMPANY = 'company_';

    public $appends = ['type_text'];

    public function getParamsAttribute()
    {
        return $this->attributes['params'] && !is_array($this->attributes['params']) ? json_decode($this->attributes['params'], 1) : $this->attributes['params'];
    }

    public function getTypeTextAttribute()
    {
        return $this->attributes['type'] ? trans('res.option.payment_type')[$this->attributes['type']] ?? '' : $this->attributes['type'];
    }

    public function getUsdtTypeTextAttribute()
    {
        return Arr::get(config('platform.usdt_type'), Arr::get($this->params, 'usdt_type'));
    }

    public function isMoneyNoLimited()
    {
        return $this->min == 0 && $this->max == 0;
    }

    public function isThirdPay()
    {
        return \Str::contains($this->type, self::PREFIX_THIRDPAY);
    }

    public function hideParentheses()
    {
        return preg_replace('/\(.*?\)/', '', $this->type_text);
    }
}
