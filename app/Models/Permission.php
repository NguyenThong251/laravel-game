<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission as Base;

class Permission extends Base
{
    protected $cast = [
        "is_show" => "boolean"
    ];

    public $hidden = ['lang_json'];

    const TYPE_SHOW = 1;
    const TYPE_NOT_SHOW = 0;

    public static $isShowMap = [
        self::TYPE_SHOW => 'show',
        self::TYPE_NOT_SHOW => 'not show'
    ];

    public static $list_field = [
        'id' => 'ID',
        'name' => 'Permission Name',
        'icon' => 'icon',
        'pid' => 'Parent ID',
        'route_name' => 'Route Name',
        'weight' => 'Weight',
        'description' => 'describe',
        'remark' => 'Remark',
        'created_at' => 'Creation time',
        'updated_at' => 'Update time'
    ];

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'pid');
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'pid');
    }

    public function getLangJsonArrAttribute()
    {
        return json_decode($this->lang_json, 1);
    }

    public function scopeGetByRouteName($query, $name)
    {
        return $query->where('route_name', $name);
    }

    public function scopeGuard($query, $guard)
    {
        return $query->where('guard_name', $guard);
    }

    /**
     * 判断权限是否有pid
     * @param $model
     * @return bool
     */
    public function isModelHasPid($model)
    {
        return !(is_null($model->pid) || $model->pid == 0);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function isItemShow()
    {
        if (!$this->remark || !\Str::contains($this->remark, '=')) return true;

        $arr = explode('=', $this->remark);

        return systemconfig($arr[0]) == $arr[1];
    }

    public function getLangName($lang = '')
    {
        return Arr::get($this->lang_json_arr, $lang ? $lang : app()->getLocale(), $this->name);
    }

    public static function getRouteTitle()
    {
        $mod = Permission::where('route_name', Route::currentRouteName())->orderByDesc('level')->first();
        return $mod ? $mod->getLangName() : '';
    }
}
