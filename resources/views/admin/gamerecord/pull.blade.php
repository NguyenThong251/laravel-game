<?php
// 获取 接口，显示title，refresh时间，起始时间，截止时间
$service = app(\App\Services\SelfService::class);

$count = 0;$errMsg = "";

// 获取游戏记录的参数
$time = time();
$start_at = $time - 3600;
if(request()->has('start_at')){
	$start_at = $time - request()->get('start_at');
}

$end_at = $time;
$params = [
    'page' => 1,
	'pageSize' => 500,
	'start_at' => $start_at,
	'end_at' => $end_at
];


// 获取游戏记录

try{
    $res = json_decode($service->gamerecord($params),1);

    if(!is_array($res)) throw new Exception('Network error, please try again');

    if($res['Code'] != 0) throw new \Exception($res['Message'],$res['Code']);

    if(!$res['Data']['data'] || count($res['Data']['data']) <= 0) throw new \Exception('Game record is empty',$res['Code']);

    $service->savegamerecord($res['Data']['data']);

    $count += $res['Data']['total_count'];

    if($res['Data']['lastPage'] > 1){

        for ($i = 2; $i <= $res['Data']['lastPage']; $i++){
            $params['page'] = $i;

            $res = json_decode($service->gamerecord($params),1);

            if(!is_array($res)) throw new Exception('Network error, please try again');

            if($res['Code'] != 0) throw new \Exception($res['Message'],$res['Code']);

            if(count($res['Data']['data']) <= 0) throw new \Exception('Game record is empty',$res['Code']);

            // $service->savegamerecord($res['Data']['record'],$params);
            $service->savegamerecord($res['Data']['data']);

            //$count += $res['Data']['total_count'];
        }

    }

}catch (Exception $e){
    $errMsg = 'error message: '.$e->getMessage().', error code: '.$e->getCode();
}
$limit = rand(100,300);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style type="text/css">
        body,td,th {
            font-size: 12px;
        }
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>
<body>
<script>
    // 定时时间
    var limit=<?=$limit?>;

    if (document.images){
        var parselimit=limit
    }
    function beginrefresh(){
        if (!document.images)
            return
        if (parselimit==1)
            window.location.reload()
        else{
            parselimit-=1
            curmin=Math.floor(parselimit)
            if (curmin!=0)
                curtime=curmin+"Automatically obtain after seconds!"
            else
                curtime=cursec+"Automatically obtain after seconds!"
            timeinfo.innerText=curtime
            setTimeout("beginrefresh()",1000)
        }
    }

    window. onload=beginrefresh;
</script>
<table width="100%"border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left">
            <input type='button' name='button' value="refresh" onClick="window.location.reload()">
            <input type="button" name='button2' value="Make up the order" onclick="window.open('{{ route('admin.gamerecord.pull',array_merge(request()->all(),['start_at' => '86400'])) }}')">
            Total records:Successfully collected<?=$count?>Strip data. 
            <span id="timeinfo"></span>

            @if($errMsg)
                <span id="errMsg" style="color:red;">{{ $errMsg }}</span>
            @endif
        </td>
    </tr>
</table>
</body>
</html>