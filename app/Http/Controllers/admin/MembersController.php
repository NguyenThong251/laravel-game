<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MembersController extends AdminBaseController
{
    protected $model;
    protected $create_field = ['name', 'password', 'nickname', 'realname', 'email', 'phone', 'qq', 'line', 'facebook', 'gender', 'status', 'is_tips_on', 'is_in_on', 'qk_pwd', 'lang'];
    protected $update_field = ['name', 'password', 'nickname', 'realname', 'email', 'phone', 'qq', 'line', 'facebook', 'gender', 'status', 'is_tips_on', 'is_in_on', 'qk_pwd', 'lang'];
    public function __construct(Member $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function index(Request $request)
    {
        $online_list = $this->getOnlineMember()->toArray();
        $params = $request->all();
        $isOnline = $request->get('is_online');

        $data = $this->model->where($this->convertWhere($params))
            ->when(strlen($isOnline), function ($query) use ($online_list, $isOnline) {
                if ($isOnline)
                    $query->whereIn('id', $online_list);
                else
                    $query->whereNotIn('id', $online_list);
            })->filterDemoAccount()
            ->latest()->paginate(15);
        return view("{$this->view_folder}.index", compact('data', 'params', 'online_list'));
    }

    // 获取在线会员列表
    public function getOnlineMember()
    {
        $last_hour_log = MemberLog::where('created_at', '>', Carbon::now()->subHour())
            ->where('member_id', '>', 0)->get();

        $ids = $last_hour_log->pluck('created_at', 'member_id');

        $ids = collect(array_keys($ids->toArray()));

        foreach ($last_hour_log->where('type', MemberLog::LOG_TYPE_API_LOGOUT) as $item) {

            $max_created_at = $last_hour_log->where('type', '<>', MemberLog::LOG_TYPE_API_LOGOUT)->max('created_at');

            if ($item->created_at->gte($max_created_at)) {
                $ids = $ids->filter(function ($v) use ($item) {
                    return $v != $item->member_id;
                });
            }
        }
        return $ids;
    }
}
