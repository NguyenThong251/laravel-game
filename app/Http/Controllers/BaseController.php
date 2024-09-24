<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;
use App\Services\AdminLogService;
use App\Traits\CurdTrait;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

/**
 * 封装 CURD 基本操作
 *
 * @Description
 * @example
 * @since
 * @date 2020-03-04
 */
class BaseController extends Controller
{
    use ApiResponseTrait, CurdTrait;

    protected $guard_name = "";

    protected $model;
    protected $model_name;
    protected $view_folder; // 视图目录，类似 admin.user

    protected $create_field = [];
    protected $update_field = [];

    // 处理成功时返回的data
    protected $create_success_data = [];
    protected $update_success_data = [];


    public function __construct()
    {
        if ($this->model) {
            $this->model_name = Str::lower(class_basename($this->model));

            // 类似 admin.user
            $this->view_folder = $this->root_folder . '.' . $this->model_name; //.Str::plural($this->model_name);
        }
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $data = $this->model->where($this->convertWhere($params))->latest()->paginate(15);
        return view("{$this->view_folder}.index", compact('data', 'params'));
    }

    public function show(Request $request, $id)
    {
        $model = $this->model->findOrFail($id);
        return view("layouts.show", compact('model'));
    }

    public function create()
    {
        return view("{$this->view_folder}.create_and_edit");
    }

    public function store(Request $request)
    {
        // 1. 验证数据
        $data = $request->only($this->create_field);

        $this->validateRequest(
            $data,
            $this->storeRule(),
            method_exists($this, 'ruleMessage') ? $this->ruleMessage() : [],
            method_exists($this, 'attributeName') ? $this->attributeName($this->model) : []
        );

        $data = $this->storeHandle($data);
        $data = array_filter($data, function ($temp) {
            return $temp !== null;
        });

        if ($res = $this->add($data)) {
            return $this->successWithUrl($this->create_success_data, trans('res.base.add_success'), $request->get('redirect_url', $this->indexUrl()));
        } else {
            return $this->failed(trans('res.base.add_fail'));
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->only($this->update_field);

        $this->validateRequest(
            $data,
            $this->updateRule($id),
            method_exists($this, 'ruleMessage') ? $this->ruleMessage() : [],
            method_exists($this, 'attributeName') ? $this->attributeName($this->model) : []
        );

        $data = $this->updateHandle($data);

        /**
        $data = array_filter($data,function($temp){
            return $temp !== null;
        });
         **/

        if ($res = $this->updateById($id, $data)) {
            return $this->successWithUrl($this->update_success_data, trans('res.base.update_success'), $request->get('redirect_url', $this->indexUrl()));
        } else {
            return $this->failed(trans('res.base.update_fail'));
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = $request->get("ids") ?? $id;

        if ($this->delete($id)) {
            return $this->success(["reload" => true], trans('res.base.delete_success'));
        } else {
            return $this->failed(trans('res.base.delete_fail'));
        }
    }

    public function indexUrl()
    {
        $models = Str::plural($this->model_name);
        return route("{$this->root_folder}.{$models}.index");
    }

    public function getEditViewName()
    {

        return "{$this->view_folder}.create_and_edit";
    }

    public function storeRule()
    {
        return [];
    }

    public function updateRule($id)
    {
        return [];
    }

    protected function storeHandle($data)
    {
        return $data;   // TODO: Change the autogenerated stub
    }

    protected function updateHandle($data)
    {
        return $data;
    }

    public function ruleMessage()
    {
        return [];
    }

    public function attributeName($model)
    {
        try {
            $data = trans('res.' . Str::snake(getClassBaseName($model)) . '.field');

            if (is_array($data)) return $data;

            if (!property_exists($model, '$list_field') && !is_array($model::$list_field)) {
                return [];
            }

            $list_field = $model::$list_field;
            if (!is_array($list_field)) return [];

            $result = [];
            foreach ($list_field as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = $value['name'];
                } else {
                    $result[$key] = $value;
                }
            }
            // dd($result);
            return $result;
        } catch (Exception $e) {
            return [];
        }

        return [];
    }

    protected function getLangAttribute($field)
    {
        return (trans('res.' . $field . '.field') && is_array(trans('res.' . $field . '.field'))) ? trans('res.' . $field . '.field') : [];
    }

    /** 
     * 将多个表单验证的错误信息返回成一个字符串
     */
    protected function validateRequest($data, $validateRules, $ruleMessages = [], $attributeName = [])
    {
        if (!$attributeName && $this->model) {
            $attributeName = method_exists($this, 'attributeName') ? $this->attributeName($this->model) : [];
        }

        $validator = Validator::make($data, $validateRules, $ruleMessages, $attributeName);

        if ($validator->fails()) {
            $this->dealFailValidator($validator);
        }
    }

    protected function dealFailValidator($validator)
    {
        // 有错误，处理错误信息并且返回
        $errors = $validator->errors();
        $errorTips = '';
        foreach ($errors->all() as $message) {
            $errorTips = $errorTips . $message . ',';
        }
        $errorTips = substr($errorTips, 0, strlen($errorTips) - 1);
        //return $this->failed($errorTips, 422);
        throw new InvalidRequestException($errorTips, 422);
    }

    protected function UserLogout()
    {
        // 记录登录日志
        if ($this->guard_name == "web") {
            app(AdminLogService::class)->logoutLogCreate();
        }
        $this->guard()->logout();
        // request()->session()->invalidate();
    }
}
