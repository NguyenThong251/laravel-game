<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    const LANG_COMMON = 'common';
    const LANG_CN = 'zh_cn';

    const OPEN_TYPE_TRUE = 1;
    const OPEN_TYPE_FALSE = 0;

    public static $isOpenMap = [
        self::OPEN_TYPE_TRUE => 'Enable',
        self::OPEN_TYPE_FALSE => 'Disable'
    ];

    const BOOL_TYPE_TRUE = 1;
    const BOOL_TYPE_FALSE = 0;

    public static $boolTypeMap = [
        self::BOOL_TYPE_TRUE => 'Yes',
        self::BOOL_TYPE_FALSE => 'No'
    ];

    public function scopeLangs($query, $lang = '')
    {
        if (!strlen($lang)) $lang = getRequestLang();
        return $query->whereIn('lang', [self::LANG_COMMON, $lang]);
    }

    public function scopeCnLangs($query, $lang = '')
    {
        if (!strlen($lang)) $lang = getRequestLang();
        return $query->where('lang', 'like', substr($lang, 0, 2) . '%');
    }
}
