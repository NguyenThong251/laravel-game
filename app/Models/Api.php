<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Api extends Base
{
    const LY_LOTTERY = 'LY';
    const COMMON_WALLET_API = 'LYC';
    const JZ_LOTTERY = 'JZ';

    const SYSTEM_LOTTERY = [
        self::LY_LOTTERY,
        self::JZ_LOTTERY
    ];

    protected $guarded = ['id'];

    public static $list_field = [
        'api_id' => ['name' => 'Tap ID', 'type' => 'number', 'is_show' => true, 'validate' => 'required'],
        'api_name' => ['name' => 'Platform Logo', 'type' => 'text', 'is_show' => true, 'validate' => 'required'],
        'api_title' => ['name' => 'Platform Description Name', 'type' => 'text', 'is_show' => true, 'validate' => 'required'],
        'api_money' => ['name' => 'Interface balance', 'type' => 'number', 'is_show' => false],
        'prefix' => ['name' => 'Account prefix', 'type' => 'text'],
        'is_open' => ['name' => 'Open', 'type' => 'radio', 'validate' => 'required', 'data' => 'platform.is_open', 'is_show' => true, 'style' => 'platform.style_isopen'],
        'lang' => ['name' => 'Currency', 'type' => 'select', 'is_show' => true, 'data' => 'platform.lang_select'],
        'lang_list' => ['name' => 'Supported languages', 'is_show' => false],
        'weight' => ['name' => 'Weight', 'type' => 'number'],
        'remark' => ['name' => 'Remark', 'type' => 'text'],
        'icon_url' => ['name' => 'icon', 'type' => 'picture'],
    ];

    // 获取 api_id 列表
    // public function scopeGetApiIDArray($query){
    // return $query->pluck('api_name','id')->toArray();
    // }

    public function scopeGetApiNameArray($query)
    {
        return $query->pluck('api_title', 'api_name')->toArray();
    }

    public static function isCommonWallet()
    {
        return env('IS_COMMON_WALLET', 0);
    }

    public function getAccessLang($lang)
    {
        $list = $this->lang_list ? json_decode($this->lang_list) : [];
        return Arr::get(config('platform.api_lang'), in_array($lang, $list) ? $lang : current($list));
    }

    public static function isSystemLottery($api_code)
    {
        return in_array($api_code, self::SYSTEM_LOTTERY);
    }
}
