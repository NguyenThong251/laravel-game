<?php

namespace App\Services;

use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Models\Api;
use App\Models\ApiGame;
use App\Models\GameRecord;
use App\Models\Member;
use App\Models\SystemConfig;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SelfService{

    protected $config;

    public $debug;
    public $salt ;
    public $betLimitCode;
    public $currencyCode;
    public $isspeed;
    public $isdemo;

    public function __construct(){

        // 获取 api 基本配置
        $this->config = SystemConfig::getConfigGroup('remote_api');
        
        // 判断是否有请求api相关的参数
         if(!array_key_exists('remote_api_domain',$this->config) || !array_key_exists('remote_api_id',$this->config)
            && !array_key_exists('remote_api_key',$this->config))
             throw new InternalException(trans('res.api.game.api_parameter_err'));
    }

    // 选填参数 gameType,gameCode
    // username不需要带上前缀
    // public function register($api_code, $username,$password,$is_test = 0){
    public function register($api_code, $username,$password){
        $post_data = [	
            'api_code' =>$api_code,
            'username' => $username,
			'password' => $password,			
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/register';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/register';
        }

        $receive = $this->send_post_data($url,$post_data);
         
        return $receive;
    }

    // public function login($api_code,$username,$password,$gameType,$gameName = '',$is_Mobile = 0,$gameId = ''){
    public function login($api_code,$gameCode,$gameType,$lang,$member_anme,$isMobile){
        $post_data = [	
            'api_code' =>$api_code,
            'username' => $member_anme,
			'gameType' => $gameType,
			'gameCode' => $gameCode,
			'isMobile' => $isMobile,			
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/login';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/login';
        }

        $receive = $this->send_post_data($url,$post_data);
         
        return $receive;
    }

    // $api_code,$username,$password
    public function balance($api_code,$member_anme){
        $post_data = [	
            'api_code' =>$api_code,
            'username' => $member_anme,		
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/balance';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/balance';
        }

        $receive = $this->send_post_data($url,$post_data);
         
        return $receive;
    }

    // app(App\Services\SelfService::class)->autotransfer(['name' => '','api_code' => '','merchant_bill_no_1' => getBillNo(),'merchant_bill_no_2' => getBillNo(),])

    /**
     * {
            "status": {
                "errorCode": 20000,
                "msg": "成功"
            },
            "data": {
                "code": 20000,
                "message": "成功",
                "url": "",
                "data": {
                    "out_api_id": 5301,
                    "out_amount": 5,
                    "in_api_id": 5401,
                    "in_amount": 5
            }
        }
     */
    public function autotransfer($data){
        $data['method'] = 'autotransfer';
        $data = $this->combineDefaultParams($data);

        $result = $this->send_post($data);
        return $result;
    }

    public function deposit($api_code,$username,$amount,$transferno){
        $post_data = [	
            'api_code' =>$api_code,
            'username' => $username,
			'amount' => $amount,
			'transferno' => $transferno,		
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/deposit';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/deposit';
        }

        $receive = $this->send_post_data($url,$post_data);
         
        return $receive;
    }

    public function withdrawal($api_code,$username,$amount,$transferno){
        $post_data = [	
            'api_code' =>$api_code,
            'username' => $username,
			'amount' => $amount,
			'transferno' => $transferno,		
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/withdrawal';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/withdrawal';
        }

        $receive = $this->send_post_data($url,$post_data);
         
        return $receive;
    }

    /**
    public function transfer($billno,$api_code,$username,$password,$amount,$type = 1){
        writelog("调用 transfer 方法，参数：".json_encode(func_get_args()));

        $result = [
            'status' => [
                'errorCode' => 0,
                'msg' => '额度转换错误'
            ]
        ];

        return json_encode($result);
    }
    */

    // app(App\Services\SelfService::class)->credit('IG')
    // 返回信息：{"status":{"errorCode":0,"msg":"成功"},"data":"13.00","url":""}
    public function credit($api_code){
		$api_code = preg_replace("/\\d+/",'', $api_code);
        $post_data = [	
            'api_code' =>$api_code,		
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/credit';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/credit';
        }

        $json = $this->send_post_data($url,$post_data);
        return $json;
    }

    public function combineDefaultParams($data){
        // 根据 api_code 找到对应的 api_id
        if(array_key_exists('api_code',$data) && $data['api_code']) {
            $api = Api::where('api_name',$data['api_code'])->first();

            if(!$api) throw new InvalidRequestException(trans('res.api.common.server_error'));

            $data['api_id'] = $api->api_id;
        }

        return array_merge([
            'api_account' => $this->config['remote_api_id'],
            'api_key' => $this->config['remote_api_key'],
        ],$data);
    }

    // 刷新数据库中商户的接口余额
    public function refreshApiMoney($api_code){
        $res = json_decode($this->credit($api_code),1);

        if(!is_array($res)) throw new InvalidRequestException(trans('res.api.common.net_again_err'));

        if($res['Code'] != 0) throw new InvalidRequestException(trans('res.api.common.err_code').$res['Code'].'，'.trans('res.api.common.err_msg').$res['Message']);

        // 更新数据库字段
        Api::where('api_name',$api_code)->update([
            'api_money' => $res['Data']['money']
        ]);

        return $res['Data']['money'];
    }

    // 掉单查询
    // app(App\Services\SelfService::class)->checktransfer(App\Models\Member::find(7))
    /**
     * {
            "status": {
                "errorCode": 0,
                "msg": "成功"
            },
            "data": [],
            "url": ""
        }
     *
     * @param $member
     * @param $end_at
     * @param string $bill_no
     * @return bool|mixed
     */
    public function checktransfer($member,$end_at = '',$start_at = '',$bill_no = '',$api_code = ''){
        $params = $this->combineDefaultParams([
            'username' => $member->name,
            // 'password' => $member->original_password,
            'method' => 'transferrecord',
            'api_code' => $api_code,
            'end_at' => $end_at,
            'start_at' => $start_at,
            // 'lang' => $member->lang
            //'end_at' => '2020-07-02 21:44:04'
        ]);

        if($bill_no) $params['merchant_bill_no'] = $bill_no;
        $result = $this->send_post($params);
        // writelog("调用 checktransfer 方法，参数：".json_encode($params).'，返回:'.$result);

        return $result;
    }

    // app(App\Services\SelfService::class)->getGamelist('AG')
    public function getGamelist($api_code){
        $params = $this->combineDefaultParams([
            'method' => 'gamelist',
            'api_code' => $api_code,
        ]);

        $result = $this->send_post($params);
        // writelog("调用 gamelist 方法，参数：".json_encode($data).'，返回:'.$result);

        return $result;
        //return curls($this->config['remote_api_domain'],$params,1);
    }

    public function getLotteryApiResult($method,$data = []){
        $params = $this->combineDefaultParams([
            'method' => $method,
            'api_code' => Api::LY_LOTTERY
        ]);
        $params = array_merge($params,$data);
        return $this->send_post($params);
    }

    // app(App\Services\SelfService::class)->gamerecord('AG')

    /**
     * $api_code,$start_at,$end_at,$page,$pageSize = 500
     * @param $data
     * @return bool|mixed
     */
    public function gamerecord($data){
        $post_data = [	
            'page' =>$data['page'],
			'pageSize' => $data['pageSize'],
			'start_at' => $data['start_at'],
			'end_at' => $data['end_at']
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/gamerecord';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/gamerecord';
        }

        $json = $this->send_post_data($url,$post_data);

        return $json;
    }

    /**
     * {
            "bill_no": "13434344545",
            "api_id": 4,
            "api_code": "BBIN5",
            "player_name": "xxxuser2",
            "name": "user1",
            "merchant_id": 1,
            "bet_amount": "89023.00",
            "net_amount": "23.00",
            "net_win_loss": "2323.00",
            "valid_bet_amount": "3443.00",
            "bet_time": "2021-04-19 16:28:26",
            "bet_time_bj": "2021-04-19 06:28:28",
            "game_type": 1,
            "game_code": null,
            "game_name": "游戏阿萨斯",
            "description": null,
        }
     *
     */
    public function savegamerecord($data){

        $api_list = Api::pluck('api_name','api_id')->toArray();

        foreach ($data as $v){
            $mod = GameRecord::where('billNo',$v['billNo'])->where('rowid',$v['rowid'])->first();

            // 游戏记录存在
            if ($mod) {
				var_dump($v['rowid']);
                if ($mod->status == 'X') {
                    $mod->update($this->getUpdatedFields([], $v));
                }				
                continue;
            }

            $memberName = $v['username'];
            $member = Member::where('name',$memberName)->first();

            if($member && $member->is_demo) continue;

            $record = [
                'billNo' => $v['billNo'],
				'rowid' => $v['rowid'],
                'name' => $memberName,
                'playerName' => $v['playName'],
                'betAmount' => $v['betAmount'],
                'gameType' => convertGameType($v['gameType']),
                // 'roundNo' => $v['roundNo'],
                'playDetail' => $v['playType'],
                //'wagerDetail' => $v['wagerDetail'],
                'gameResult' => $v['Lottery_results'],
                'betTime' => date('Y-m-d H:i:s',$v['betTime']),
                'confirmTime' => date('Y-m-d H:i:s',$v['betTime']),

                'member_id' => $member->id ?? 0,
                //'api_name' => Arr::get($api_list,$v['api_id'],''),
				'api_name' => $v['code'],
            ];

            $record = $this->getUpdatedFields($record,$v);
            GameRecord::create($record);		
        }
    }

    public function getUpdatedFields($record,$data){

        $record['playDetail'] = $data['playType'];
        $record['gameResult'] = $data['Lottery_results'];

        $record['lastUpdateTime'] = date('Y-m-d H:i:s',$data['betTime']);
        $record['validBetAmount'] = $data['validBetAmount'];
        $record['netAmount'] = $data['netAmount'] + $data['betAmount'];
		$record['status'] = $data['status'];
		if($data['status'] ==1 ){
			$record['status'] = 'COMPLETE';
		}elseif($data['status'] ==2 ){
			$record['status'] = 'X';
		}elseif($data['status'] ==3 ){
			$record['status'] = 'N';
		}elseif($data['status'] ==4 ){
			$record['status'] = 'CANCEL';
		}
		if($data['status'] ==1 && $data['netAmount'] == 0){
			$record['validBetAmount'] = 0;
		}
        $record['result'] = json_encode($data);
        return $record;
    }
	
    public function Record_mark($data){
        $post_data = [	
            'id' =>$data,
        ];
        if(preg_match('/^http(s)?:\\/\\/.+/',$this->config['remote_api_domain'])){
            $url = $this->config['remote_api_domain'].'/Record_mark';
        }else{
            $url = 'http://'.$this->config['remote_api_domain'].'/Record_mark';
        }

        $json = $this->send_post_data($url,$post_data);

        return $json;
    }
	
    // "errorCode": 20000, 表示操作成功
    public function send_post($data){
        if($data['api_code'] == Api::JZ_LOTTERY) return app(JzService::class)->convertApiRequest($data);

        $url = $this->config['remote_api_domain'];

        // 将参数进行加密处理
        $params = $this->getFormatterPostData($data);

        $json = curls($url,$params,1);

        if(!in_array($data['method'],['gamelist','gamerecord','balance']) || ($data['method'] == 'transferrecord' && !Arr::get($data,'merchant_bill_no')))
            writelog('返回结果：'.$json);
        return $json;
    }

    /**
    public function send_post($data){
        $url = '';
        if($data['api_code'] == Api::LY_LOTTERY){
            $api = Api::where('api_name',Api::LY_LOTTERY)->first();
            if(!$api) throw new \Exception(trans('res.api.game.lottery_api_not_exist'));
            $url = $api->remark;

            if(!Str::startsWith($url,'http')) throw new InvalidRequestException(trans('res.api.game.lottery_error'));

            $data['top'] = 'LyGame';
            $data['source'] = 'api';
            $data['website'] = env('APP_URL');

            if(array_key_exists('username',$data))  $data['username'] = $this->config['remote_api_prefix'].\Arr::get($data,'username');
        }else{
            $url = $this->config['remote_api_domain'];

            // 将参数进行加密处理
            $data = $this->getFormatterPostData($data);
        }

        $json = curls($url,$data,1);

        if($data['api_code'] == Api::LY_LOTTERY){
            if(!$json) return json_encode(['status' => ['errorCode' => -1,'msg' => '网络错误']]);

            $res = json_decode($json,1);
            $res['status']['errorCode'] = \Arr::get($res,'code');
            $res['status']['msg'] = \Arr::get($res,'msg',\Arr::get($res,'message'));
            return json_encode($res);
        }else{
            return $json;
        }
    }
     */

    public function getFormatterPostData($data){
        $data = array_filter($data);
        ksort($data);

        $str = '';
        foreach ($data as $key => $value) {
            $str .= $key . '=' . $value . '|';
        }
        $str = $str . Arr::get($data,'api_key');

        $sign_str = $this->encryption($str);

        if(!in_array($data['method'],['gamelist','gamerecord','balance']))
            writelog('['.$data['method'].']请求参数：'.json_encode($data,320));

        return [
            's' => $sign_str,
            'k' => Arr::get($data,'api_key')
        ];
    }

    public function encryption($string = "")
    {
        return base64_encode($string);
    }
    protected function send_post_data($url,$data = null)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data))
        {
			$data['account'] = $this->config['remote_api_id'];
			$data['api_key'] = $this->config['remote_api_key'];
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

       $output = curl_exec($curl);

        curl_close($curl);
 
        return $output;
    }	
}