<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Base;
use App\Models\Drawing;
use App\Models\GameRecord;
use App\Models\Member;
use App\Models\Recharge;
use App\Models\SystemConfig;
use App\Services\AgentService;
use App\Services\GameService;
use App\Services\MenuService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends AdminBaseController
{
    public function main()
    {
        $voice_list = SystemConfig::where('config_group', 'notice')
            ->where('type', 'file')->where('value', '<>', '')->get();

        $user = $this->guard()->user();
        return view("admin.main", compact('voice_list', 'user'));
    }

    public function index(Request $request)
    {
        // 获取统计数据
        $startDay = Carbon::now()->startOfDay();
        $startMonth = Carbon::now()->startOfMonth();

        // 注册数据
        $today_register = Member::where('created_at', '>', $startDay)->filterDemoAccount()->count();
        $month_register = Member::where('created_at', '>', $startMonth)->filterDemoAccount()->count();

        // 营销数据（包括 会员的福利金额，代理的佣金 和 全民代理的返点）
        $today_free = app(AgentService::class)->getTotalFreeMoney($startDay);
        $month_free = app(AgentService::class)->getTotalFreeMoney($startMonth);

        // 今日投注
        $today_bet = GameRecord::where('created_at', '>', $startDay)
            ->whereNotIn('member_id', Member::demoIdLists())
            ->where('status', GameRecord::STATUS_COMPLETE)
            ->sum('betAmount');
        $month_bet = GameRecord::where('created_at', '>', $startMonth)
            ->whereNotIn('member_id', Member::demoIdLists())
            ->sum('betAmount');

        // 游戏营收 (派彩金额 - 投注金额)
        $today_game_profit = GameRecord::where('created_at', '>', $startDay)
            ->whereNotIn('member_id', Member::demoIdLists())
            ->sum('netAmount');
        $today_game_profit = sprintf("%.2f", $today_game_profit - $today_bet);

        $month_game_profit = GameRecord::where('created_at', '>', $startMonth)
            ->whereNotIn('member_id', Member::demoIdLists())
            ->where('status', GameRecord::STATUS_COMPLETE)
            ->sum('netAmount');
        $month_game_profit = sprintf("%.2f", $month_game_profit - $month_bet);

        // 获取最近7天的注册数据
        /**
        $last_7days_counts = DB::table('members')->select(DB::raw("count(*) as member_count, date_format(created_at, '%Y-%m-%d') as date"))->where('created_at','>', Carbon::now()->subDays(6)->startOfDay())->groupBy('date')->get();

        // 循环处理结果
        $last_7days = [];
        for($i = 0; $i < 7; $i++){
            $dates = Carbon::now()->subDays(6 - $i)->startOfDay()->format('Y-m-d');
            $datas = $last_7days_counts->where('date',$dates)->first();
            $last_7days[$dates] = $datas->member_count ?? 0;
        }
         **/

        // 获取最近10天的充值数据
        $last_10day_sum = DB::table('recharges')
            ->select(DB::raw("sum(money) as recharge_sum, date_format(created_at, '%Y-%m-%d') as date"))
            ->where('created_at', '>', Carbon::now()->subDays(9)->startOfDay())
            ->whereNotIn('member_id', Member::demoIdLists())
            ->where('status', Recharge::STATUS_SUCCESS)
            ->groupBy('date')->get();

        $last_10days = [];
        for ($i = 0; $i < 10; $i++) {
            $dates = Carbon::now()->subDays(9 - $i)->startOfDay()->format('Y-m-d');
            $datas = $last_10day_sum->where('date', $dates)->first();
            $last_10days[$dates] = floatval($datas->recharge_sum ?? 0);
        }

        // 获取最近10天的提款数据
        $last_10day_drawing_sum = DB::table('drawings')
            ->select(DB::raw("sum(money) as drawing_sum, date_format(created_at, '%Y-%m-%d') as date"))
            ->where('created_at', '>', Carbon::now()->subDays(9)->startOfDay())
            ->whereNotIn('member_id', Member::demoIdLists())
            ->where('status', Drawing::STATUS_SUCCESS)
            ->groupBy('date')->get();

        $last_10days_drawing = [];
        for ($i = 0; $i < 10; $i++) {
            $dates = Carbon::now()->subDays(9 - $i)->startOfDay()->format('Y-m-d');
            $datas = $last_10day_drawing_sum->where('date', $dates)->first();
            $last_10days_drawing[$dates] = floatval($datas->drawing_sum ?? 0);
        }

        return view("admin.index.index", compact('today_register', 'month_register', 'today_free', 'month_free', 'today_bet', 'month_bet', 'today_game_profit', 'month_game_profit', 'last_10days', 'last_10days_drawing'));
    }
    public function fix_url()
    {
        $oldurl = \systemconfig('site_domain', Base::LANG_COMMON);
        // $newurl = config('APP_URL');
        $newurl = env('APP_URL');

        if (!strlen($oldurl)) return $this->failed(trans('res.index.site_domain_required'));

        if (!$newurl) return $this->failed(trans('res.index.app_url_required'));

        if ($oldurl == $newurl) return $this->failed(trans('res.index.url_same_error'));

        try {
            // 创建 软连接
            app(MenuService::class)->checkUploadsFolder();

            // 批量替换所有的图片地址
            app(GameService::class)->replaceAllPic();
        } catch (\Exception $e) {
            return $this->failed($e->getMessage());
        }

        // 执行替换图片操作后，应该整个页面刷新
        // return $this->success([],'操作成功');
        return $this->success(['redirect' => route('admin.main')], trans('res.base.operate_success'));
    }
}
