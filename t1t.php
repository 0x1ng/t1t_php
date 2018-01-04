<?php
$version=5;
if(intval($_POST['num'])!=0){
	$score=intval($_POST['num']);
}else{
	exit('没输数量');
}

if(empty($_POST['sessionid'])){
	exit('没输sessionid') ;
}else{
	$session_id=$_POST['sessionid'];
}
$base_site = 'https://mp.weixin.qq.com/wxagame/';

$path     = 'wxagame_getfriendsscore';
$base_req =array("base_req"=>array("session_id"=>$session_id,"fast"=>1));
$response = getCurl($base_site.$path,json_encode($base_req));
$arr      =json_decode($response,true);
$times    = $arr['my_user_info']['times'] + 1;

$path = 'wxagame_init';
$POST = $base_req;
$POST['version']=9;

$response = getCurl($base_site.$path,json_encode($POST));
$arr=json_decode($response,true);
if($arr['base_resp']['errcode']==-1){
	exit('session_id错误') ;
}
//print_r($arr);exit();
for($i=0;$i==$score;$i++){
	$action[]=[0.711,1.29,false];
	$musicList[]=false;
	$touchList[]=[232,586];
	
}
//$action = [[0.711,1.29,false],[0.687,1.36,false],[0.754,1.19,false],[0.511,1.7,true],[0.501,1.73,true],[0.535,1.67,false],[0.694,1.36,false],[0.686,1.36,false],[0.484,1.77,true],[0.934,0.85,false],[0.479,1.77,false],[0.653,1.43,false],[0.646,1.43,false],[0.83,1.06,false],[0.703,1.29,false],[0.871,0.99,false],[0.623,1.5,false],[0.655,1.43,false]];
//$musicList = [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false];
//$touchList = [[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586],[232,586]];
$data=array(
	'score'=>$score,
	'times'=>$times,
	'game_data'=>json_encode(array(
		'seed'=>getMillisecond(),
		'action'=>$action,
		'musicList'=>$musicList,
		'touchList'=>$touchList,
		'version'=>1,
	))
);

$path = 'wxagame_settlement';
$POST = $base_req;
$POST['action_data']=encrypt(json_encode($data),$session_id);

$response = getCurl($base_site.$path,json_encode($POST));
if($arr['base_resp']['errcode']==0){
	echo'刷分成功！腾讯服务器返回信息：';
}else{
	echo'刷分失败！腾讯服务器返回信息：';
}
echo $response;



function getMillisecond() { 
  list($t1, $t2) = explode(' ', microtime()); 
  return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000); 
} 


function encrypt($text, $originKey){
	$key = substr($originKey,0,16);
	$iv = substr($originKey,0,16);
	$message = $text;
	$blocksize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$len = strlen($message); //取得字符串长度
	$pad = $blocksize - ($len % $blocksize); //取得补码的长度
	$message .= str_repeat(chr($pad), $pad); //用ASCII码为补码长度的字符， 补足最后一段
	$xcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $message, MCRYPT_MODE_CBC, $iv);
	return base64_encode($xcrypt);
}

function getCurl($url, $post = 0, $cookie = 0, $header = 0, $nobaody = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $klsf[] = 'Accept:*/*';
    $klsf[] = 'Accept-Language:zh-cn';
    $klsf[] = 'Content-Type:application/json';
    $klsf[] = 'User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 11_2_1 like Mac OS X) AppleWebKit/604.4.7 (KHTML, like Gecko) Mobile/15C153 MicroMessenger/6.6.1 NetType/WIFI Language/zh_CN';
    $klsf[] = 'Referer:https://servicewechat.com/wx7c8d593b2c3a7703/5/page-frame.html';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $klsf);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($nobaody) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret;
}
