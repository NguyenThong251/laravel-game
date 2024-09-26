<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>{{ systemconfig('site_title') ?? 'motoo' }} -- 电子升级模式</title>
    <meta name="keywords" content="{{ systemconfig('site_keyword') }}">

    <link href="{{ asset('/web/css/slot/common.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('/web/css/slot/css.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('/web/css/slot/main.css') }}" type="text/css" rel="stylesheet">

    <script type=text/javascript src="{{ asset('/web/js/slot/common.js') }}"></script>
</head>
<body>
<style>
    .inline-span {float: none !important;display: inline !important;margin-left: 3px !important;}
</style>
    <div class="top">
        <div class="tops">
            <ul>
                <a class="cur" href="{{ route('web.credit_pay.index') }}" target="_blank">
                    <li class="d1">
                        电子升级
                    </li>
                    <span></span>
                </a>

                <a href="{{ route('web.levelup.live') }}" target="_blank">
                    <li class="d2">
                        真人升级
                    </li>
                    <span></span>
                </a>

                <a href="{{ quicklink('activity_hall') }}" target="_blank">
                    <li class="d3">
                        活动申请
                    </li>
                    <span></span>
                </a>

                <a href="{{ quicklink('pc_register') }}" target="_blank">
                    <li class="d4">
                        立即注册
                    </li>
                </a>

                <a href="{{ route('web.credit_pay.index') }}" target="_blank">
                    <li class="d5">
                        免息借呗
                    </li>
                    <span></span>
                </a>

                <a href="{{ systemconfig('site_mobile') }}" target="_blank">
                    <li class="d6">
                        手机投注
                    </li>
                    <span></span>
                </a>

                <a href="{{ systemconfig('wap_app_link') }}" target="_blank">
                    <li class="d7">
                        APP下载
                    </li>
                    <span></span>
                </a>

                <a href="{{ systemconfig('service_link') }}"
                                         target="_blank">
                    <li class="d8">
                        在线客服
                    </li>
                </a>
            </ul>

            <div class="logo" style="top: 50%;transform: translateY(-50%);overflow: hidden;max-width: 200px;width: 200px;text-align: center;"><img src="{{ systemconfig('site_logo') }}"></div>
        </div>
    </div>

    <div class="center">
        <div class="gg">
            <div class="lt">
                <div class="zxgg"></div>
                <div class="zit">
                    <marquee scrollamount="3" direction="left" onmouseover=this.stop() onmouseout=this.start()>
                        @foreach(App\Models\SystemNotice::groupName('电子升级大厅')->pluck('content') as $v)
                            <a href="#">{{ $v }}</a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>

        <div class="c_center">

            <!-- 查询弹窗 -->
            <div class="kaishi1" style="z-index:99999; top:160px;@if(($info)) display:block; @else display:none; @endif">
                <div class="title"></div>
                <div class="form_s">
                    <table width="94%" border="1">
                        <tr>
                            <th scope="col">会员帐号</th>
                            <th scope="col">当前等级</th>
                            <th scope="col">累计投注</th>
                            <th scope="col">等级礼金</th>
                            <th scope="col">周俸禄</th>
                            <th scope="col">月俸禄</th>
                            <th scope="col">升级投注</th>
                        </tr>
                        @if($info)
                        <tr>
                            <td>{{ $name }}</td>
                            <td>{{ $info['level'] }}</td>
                            <td>{{ $info['total_bet'] }}</td>
                            <td>{{ $info['level_award'] }}</td>
                            <td>{{ $info['week_award'] }}</td>
                            <td>{{ $info['month_award'] }}</td>
                            <td>{{ $info['remain_bet'] ? '距离下一级还差'.$info['remain_bet'].'注' :'' }}</td>
                        </tr>
                        @endif
                    </table>

                    <table width="94%" border="1" style="margin-top:8px;">
                        <tr>
                            <th scope="col">会员帐号</th>
                            <th scope="col">奖励类型</th>
                            <th scope="col">奖励金额</th>
                            <th scope="col">发放时间</th>
                            {{--
                            <th scope="col">当周有效投注</th>
                            <th scope="col">当周等级彩金</th>
                            <th scope="col">投注周期</th>
                            --}}
                        </tr>
                        <tbody id="table2">
                            @if($info && $info['list']->count())
                                @foreach($info['list'] as $item)
                                    <tr>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $item->operate_type_text }}</td>
                                        <td>{{ $item->money }}</td>
                                        <td>{{ $item->created_at->toDateString() }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            {{--
                            @foreach(range(0,100) as $item)
                                <tr>
                                    <td>pp198773</td>
                                    <td>18</td>
                                    <td>0</td>
                                    <td>1月06号-1月12号(第一百五十六周) </td>
                                </tr>
                            @endforeach
                            --}}

                        </tbody>
                    </table>
                    <div class="yj">
                        <span id="spanFirst"></span>
                        <span id="spanPre"></span>
                        <span id="spanNext"></span>
                        <span id="spanLast"></span>
                        <span>第</span>
                        <span id="spanPageNum"></span>
                        <span>页/共</span>
                        <span id="spanTotalPage"></span>
                        <span>页</span>
                        <font id="close" onclick="window.history.back(-1);">关闭</font>
                    </div>

                </div>
                <div class="xx1"></div>
            </div>


            <div class="btn_s">
                <form action="{{ route('web.levelup.slot.search') }}" method="post">
                    @csrf
                    <div class="search">
                        <p></p>
                        <input class="sr" type="text" name="name" placeholder="| 请输入会员账号"/>
                        <input class="djs" type="submit" name="f2" value=""/>
                    </div>
                </form>
            </div>

            <div class="contentz">
                <div class="content">
                    <div class="content_s">
                        <!--表格1-->
                        <h2><img src="{{ asset('web/images/slot') }}/indexx_04.png"></h2>
                        <div class="con_zy" style="margin-top:40px;margin-bottom:10px;text-align:center;">
                            喜讯：2020年7月2号起所有电子/棋牌/捕鱼永久累计打码，让您的会员账号变成永久收益金管家！
                        </div>
                        <p>
                            声明：强势升级后的累积打码标准将会进行调整，等级礼金、周俸禄、月俸禄也会进行提升；特此声明所有会员的等级变动都以更新后为准！
                        </p>
                        <p style="margin-bottom:10px;">
                            在{{ systemconfig('site_name') }}的每一笔电子/棋牌/捕鱼有效投注都会永久累积，总打码达到标准即可升级；未达1级标准将不予计算。每升一级即可获得相对应的等级礼金，等级礼金累计可获688000元， 周周都有周俸禄，月月再领月俸禄，<span>让您的{{ systemconfig('site_name') }}账号充满价值，真正成为永久收益金管家！</span>会员可登入网址：<a href="{{ route('web.credit_pay.index') }}"
                                                                                               target="_blank">{{ getUrl(route('web.credit_pay.index')) }} </a>查询！
                        </p>

                        {{--晋升等级表格--}}
                        <table width="100%" border="1">
                            <tbody>
                            <tr>
                                <th scope="col" style="width:9.1%;">
                                    晋升标准等级
                                </th>
                                <th scope="col" style="width:9.1%;">
                                    累计打码
                                </th>
                                <th scope="col" style="width:11.1%;">
                                    等级礼金
                                </th>
                                <th scope="col" style="width:9.1%;">
                                    周俸禄
                                </th>
                                <th scope="col" style="width:9.1%;">
                                    月俸禄
                                </th>
                                <th scope="col" style="width:9.1%">
                                    借呗额度
                                </th>
                                <th scope="col" style="width:9.1%;">
                                    累计礼金
                                </th>
                                <th scope="col" style="width:9.1%;">
                                    荣升VIP
                                </th>
                                <th scope="col" style="width:9.1%;">
                                    存取款加速通道
                                </th>
                                <th scope="col" style="width:11.1%;">
                                    一对一客服经理
                                </th>
                            </tr>

                            <?php $total_level_money = 0; ?>
                            @foreach($levels as $item)
                                <tr>
                                    <td>{{ $item }}级</td>
                                    <?php
                                        $level_money = $data->where('level',$item)->where('level_award_type',\App\Models\Task::LEVEL_TYPE_LEVEL)->first()->award_content['money'];
                                        $total_level_money = $total_level_money + $level_money;
                                    ?>
                                    <td>{{ float_number($data->where('level',$item)->where('condition_money','>',0)->first()->condition_money) }}</td>
                                    <td>{{ $level_money }}元</td>
                                    <td>{{ money_unit($data->where('level',$item)->where('level_award_type',\App\Models\Task::LEVEL_TYPE_WEEK)->first()->award_content['money'] ?? '') }}</td>
                                    <td>{{ money_unit($data->where('level',$item)->where('level_award_type',\App\Models\Task::LEVEL_TYPE_MONTH)->first()->award_content['money'] ?? '') }}</td>
                                    <td>{{ money_unit($data->where('level',$item)->where('level_award_type',\App\Models\Task::LEVEL_TYPE_BORROW)->first()->award_content['money'] ?? '') }}</td>
                                    <td>{{ $total_level_money }}</td>
                                    <td>
                                        √
                                    </td>
                                    <td>
                                        √
                                    </td>
                                    <td>
                                        √
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <p style="margin-top:15px;">
                            例:
                            会员每一笔电子/棋牌/捕鱼投注累计达到10万打码量即可获得等级礼金30元，无周俸禄，每月可获得5元月俸禄，当累计到600万有效投注，即可再次获得升级礼金800元，100元周俸禄，每月可获得300元月俸禄！
                        </p>
                        <!--表格3-->
                        <h2><img src="{{ asset('web/images/slot') }}/indexx_08.png"></h2>
                        <span>如何查询金管家等级？</span>
                        <p>
                            请登入“金管家升级版” {{ getUrl(route('web.credit_pay.index')) }} 输入会员账号，进行自助查询有效投注、等级等明细。
                        </p>
                        <span>如何申请金管家礼金？</span>
                        <p>
                            无需申请, 距离上次登录7天后再次登录即可自动到账，彩金无需打码，可直接提款。如：会员直接从等级2升级到到等级5，跨越3个等级，即可获得升级礼金： 55（等级3）+100（等级4）+200（等级5）=355元
                        </p>
                        <span>如何申请每周俸禄？</span>
                        <p>
                            按您的等级来计算，距离上次登录7天后再次登录即可自动到账，等级每周俸禄详情如下：
                        </p>
                        <table width="100%" style="margin-top:20px;margin-bottom:20px;" border="1">
                            <tbody>

                            @foreach(range(0,floor(count($levels) / 16)) as $cell)
                                <tr>
                                    <th style="width:58px;" scope="col">等级</th>
                                    @foreach(range(1 + $cell * 16, min(($cell + 1) * 16 , count($levels))) as $item)
                                    <th scope="col">{{ $item }}级</th>
                                    @endforeach
                                </tr>

                                <tr>
                                    <td>周俸禄</td>
                                    @foreach(range(1 + $cell * 16, min(($cell + 1) * 16 , count($levels))) as $item)
                                        <td>
                                            {{ money_unit($data->where('level',$item)->where('level_award_type',\App\Models\Task::LEVEL_TYPE_WEEK)->first()->award_content['money'] ?? '') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <span> 如何申请每月俸禄？</span>
                        <p>
                            按您的等级计算，距离上次登录30天后再次登录即可自动到账，等级每月俸禄详情如下：
                        </p>
                        <table width="100%" style="margin-top:20px;margin-bottom:20px;" border="1">
                            <tbody>

                            @foreach(range(0,floor(count($levels) / 16)) as $cell)
                                <tr>
                                    <th style="width:58px;" scope="col">等级</th>
                                    @foreach(range(1 + $cell * 16, min(($cell + 1) * 16 , count($levels))) as $item)
                                        <th scope="col">{{ $item }}级</th>
                                    @endforeach
                                </tr>

                                <tr>
                                    <td>月俸禄</td>
                                    @foreach(range(1 + $cell * 16, min(($cell + 1) * 16 , count($levels))) as $item)
                                        <td>
                                            {{ money_unit($data->where('level',$item)->where('level_award_type',\App\Models\Task::LEVEL_TYPE_MONTH)->first()->award_content['money'] ?? '') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footers">
        COPYRIGHT &copy; {{ systemconfig('site_name') }} RESERVED
    </div>

    <script src="{{ asset('/web/js/slot/main.min.js') }}" charset="utf-8"></script>
    <script>
        $(".back-top").click(function () {
            var speed = 800;
            $('body,html').animate({scrollTop: 0}, speed);
            return false;
        });
    </script>
    <div class="reveal-modal-bg"
         style="position: absolute; z-index:88888; opacity: 0.8; cursor: pointer;display:none;"></div>

</body>

<script type=text/javascript src="{{ asset('/web/js/slot/week.js') }}"></script>
</html>