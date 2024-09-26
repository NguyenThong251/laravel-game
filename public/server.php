<?php


/*file_get_contents('php://input')
                   file_put_contents("success.txt",json_encode($_REQUEST)."\n", FILE_APPEND);*/


 /*           $api = 'https://usdt.tokenview.com/cn/tx/95d2cfd2d00c6201fd49b65114d8868e8d6f125d3ac221ae62ad6bc726b2c7ba';
            $resource = file_get_contents_by_curl( $api );  
            $str_num = strpos($resource, '所在块');
            $str = substr($resource,$str_num,700);

            $str_num = strpos($str, '从');
            $str = substr($str,$str_num,240);
			$arr = '';
			preg_match('/<span.*>(.*)<\/span>/isU',$str,$arr);
            if($arr){
				$d_usdt = $arr[1];
			}*/
list($msec, $sec) = explode(' ', microtime());

$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);			
$date = gmdate("Y-m-d\TH:i:s\Z");
		
$key = '5B539BE920B3F22389FABCCDB93C834C';
$content1 = $date.'GET'.'/api/v5/asset/deposit-history';
$hash = hash_hmac('sha256', $content1, $key,true);
$hash = base64_encode($hash);
$headers = [
    "OK-ACCESS-KEY:198ea971-2960-4267-a4ab-ac2506b200cc",
    "OK-ACCESS-SIGN:".$hash,
	"OK-ACCESS-TIMESTAMP:".$date,
	"OK-ACCESS-PASSPHRASE:Qq123456"
];
$url = 'https://www.okx.com/api/v5/asset/deposit-history';
$data = file_get_contents_by_curl($url,$headers);
var_dump($msectime);
var_dump($date);
var_dump($data);
die();
function file_get_contents_by_curl($url,$headers){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($curl);
curl_close($curl);
return $result;
}
?>