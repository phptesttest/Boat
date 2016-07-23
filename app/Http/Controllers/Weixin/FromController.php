<?php

namespace App\Http\Controllers\Weixin;

use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\code;

class FromController extends Controller
{
    
    /*
	*功能：验证该手机是否合法，合法则放回验证码，不合法则返回错误提示
	*请求方式：post
	*参数：phoneNub 手机号
	*返回值类型：json
	*返回参数：$errcode 0 表示验证成功。1 表示验证失败
    *返回参数 $msg 提示信息
    */
    public function phoneTest(){

    	$phoneNub=Request::input('phoneNub');
    	$count=strlen(trim($phoneNub));
        $msg="";
        $errcode="";
    	if (!($count==11&&(preg_match("/1[3458]{1}\d{9}$/",$phoneNub)))) {
    		$msg="手机号输入错误，请重新输入";
            $errcode=1;
    	}else{
    		$code=rand(1000,9999);
            $content="欢迎使用友谊的小船！【手机验证】".$code."。该验证码十五分钟有效";
            $ch = curl_init();
            $url = 'http://apis.baidu.com/kingtto_media/106sms/106sms?mobile='.$phonNub.'&content='.$content;
            $header = array(
                'apikey:dedaedee61bd43a1f5c7fd688d0bebf9',
            );
            // 添加apikey到header
            curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // 执行HTTP请求
            curl_setopt($ch , CURLOPT_URL , $url);
            $res = curl_exec($ch);

            if (strpos('#'.$res,"ok")) {
                
                //将手机号和验证码存入数据库
                $code=new code();
                $code->phoneNub=$phoneNub;
                $code->code=$code;
                date_default_timezone_set('PRC');
                $code->time=time();
                if($code->save()){
                    $msg="验证码发送成功";
                    $errcode=0;
                }
                else{
                    $msg="验证码发送失败，请稍后再试";
                    $errcode=1;
                }
                
            }
            else(
                $msg="验证码发送错误，请检验手机号码是否合法";
                $errcode=1;
            )
    	}
    $array=array(
            'errcode'=>$errcode,
            'msg'=>$msg,
        );
    $json=json_encode($array);
    return $json;
    }
}
