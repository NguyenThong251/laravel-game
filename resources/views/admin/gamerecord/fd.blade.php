<?php
    // 获取未发放返点的游戏记录
    $records = \App\Models\GameRecord::where('status',\App\Models\GameRecord::STATUS_COMPLETE)
    ->where('is_fd',0)->limit(50)->latest()->get();

    $errMsg = '';
    $count = 0;

    foreach ($records as $item){
        $res = app(\App\Services\AgentService::class)->getAgentFdMoneyLogByRecord($item);
        if($res['code'] < 0)  $errMsg .= $item->billNo.' - '.$res['msg'].'<br>';
        else $count++;
    }
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>(Universal Agent) Issue agent rebates</title>
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
    var limit="30";

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
            National Agent: Successfully issued<?=$count?>Strip data
            <span id="timeinfo"></span>

            @if($errMsg)
                <span id="errMsg" style="color:red;">{{ $errMsg }}</span>
            @endif
        </td>
    </tr>
</table>
</body>
</html>