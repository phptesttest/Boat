<?php

namespace App\Http\Controllers\Weixin;

use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\code;
use App\frominfo;
use App\toinfo;
use DB;
use App\common;
use App\come;
use App\wish;

class FromController extends Controller
{
    
    /*
    *功能：验证该手机是否合法，合法则返回验证码，不合法则返回错误提示
    *请求方式：get
    *参数：phoneNub 手机号 type 验证端类型 1表示赠送的 2表示获赠端
    *返回值类型：json
    *返回参数：$errcode 0 表示验证成功。1 表示验证失败
    *返回参数 $msg 提示信息
    */
    public function phoneTest(){

        $phoneNub=Request::input('phoneNub');
        $type=Request::input('type');
        $count=strlen(trim($phoneNub));
        $msg="";
        $errcode="";
        $phones="";
        if(!($count==11&&(preg_match("/1[3458]{1}\d{9}$/",$phoneNub)))) {
            $msg="手机号输入错误，请重新输入";
            $errcode=1;
            $array=array(
                'errcode'=>$errcode,
                'msg'=>$msg,
            );
            $json=json_encode($array);
            return $json;
        }
        //验证该手机号是否有资格发起祝福，或是否有发出的祝福
        if($type==1){
            $phones=DB::table('frominfos')->where('fromNub','=',$phoneNub)
            ->orderBy('time','desc')->get();
            if (count($phones)==0) {
                $msg="该手机号没有发起祝福和发起祝福的机会，请重新输入";
                $errcode=1;
                $array=array(
                    'errcode'=>$errcode,
                    'msg'=>$msg,
                );
                $json=json_encode($array);
                return $json;       
            }
        }
        else if($type==2) {
            $phones=DB::table('wishes')->where('toNub','=',$phoneNub)
            ->orderBy('time','desc')->get();
            if (count($phones)==0) {
                $msg="此号码没有收到祝福哦！你还有其它号码吗？";
                $errcode=1;
                $array=array(
                    'errcode'=>$errcode,
                    'msg'=>$msg,
                );
                $json=json_encode($array);

                return $json;            
            }
        }else{
            $msg="type参数错误";
            $errcode=1;
            $array=array(
                'errcode'=>$errcode,
                'msg'=>$msg,
            );
            $json=json_encode($array);

            return $json;
        }
        //检验无误，发送验证码
        $code=rand(1000,9999);
        $apikey = "e228d2cbebb0d06c0e2e0d31c7d5b142";                   
        $ch = curl_init();        
        // 设置验证方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //设置通信方式 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/userinfo');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => $apikey)));
        $json_data =curl_exec($ch);      
        // 发送验证码短信
        $yzmcontent="【广州酒家卖美味】尊敬的用户，你的验证码是：".$code."，请在5分钟内输入。请勿告诉其他人。";  
        $data=array('content'=>urlencode($yzmcontent),'apikey'=>$apikey,'mobile'=>$phoneNub);
        curl_setopt ($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendyzm');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $json_data=curl_exec($ch);
        $array = json_decode($json_data,true);    
        curl_close($ch);   
        if ($array['code']==1) {
            //将手机号和验证码存入数据库
            $codeobj=new code();
            $codeobj->phoneNub=$phoneNub;
            $codeobj->code=$code;
            $codeobj->type=$type;
            date_default_timezone_set('PRC');
            $codeobj->time=time();
            if($codeobj->save()){
                $msg="success";
                $errcode=0;
            }
            else{
                $msg="验证码发送失败，请稍后再试";
                $errcode=1;
            }
            
        }
        else{
            $msg="验证码发送错误，请检验手机号码是否合法";
            $errcode=1;
        }
        
        $array=array(
                'errcode'=>$errcode,
                'msg'=>$msg,
            );
        $json=json_encode($array);

        return $json;
    }

    /*
    *功能：检验验证码是否正确
    *请求方式：get
    *参数：phoneNub 手机号 code 验证码
    *返回值类型：json
    *返回参数：$errcode 0 表示正确。1 表示错误
    *返回参数 $msg 提示信息
    */
    public function validateFun(){
        date_default_timezone_set('PRC');
        $phoneNub=Request::input('phoneNub'); 
        $code=Request::input('code');
        $type=Request::input('type');
        $errcode="";
        $msg="";
        $lists=DB::table('codes')->where('phoneNub','=',$phoneNub)
            ->where('type','=',$type)
            ->orderBy('time','desc')->get();
        if (count($lists)==0) {
            $errcode=1;
            $msg="该手机号不存在";
        }else{
            if ((time()-$lists[0]->time)>5*60) {//验证码发出超过五分钟无效
                $errcode=1;
                $msg="该验证码已过期"; 
                //验证码失效，从数据库删除
                foreach ($lists as $key => $value) {
                    $del=code::find($value->id);
                    $del->delete();
                }
            }else if ($code==$lists[0]->code) {
                $errcode=0;
                $msg="验证成功";
                //验证码失效，从数据库删除
                foreach ($lists as $key => $value) {
                    $del=code::find($value->id);
                    $del->delete();
                }
            }else{
                $errcode=1;
                $msg="验证码输入错误，请重新输入";
            }
        }
        $array=array(
                'errcode'=>$errcode,
                'msg'=>$msg,
            );
        $json=json_encode($array);

        return $json;
    }

    /*
    *功能：获取祝福列表
    *请求方式：get
    *参数：phoneNub 手机号 type 验证端类型 1表示赠送端 2表示获赠端
    *返回值类型：json
    *返回参数：$errcode 0 表示正确。1 表示错误
    *返回参数: $msg  提示信息
    *返回参数: $conut 已发出（接收到）祝福总条数
    *返回参数: $changeNub 有资格发起祝福的条数
    *返回参数: $wishes 发出(接收到)祝福详情 
    *返回参数: $changes 有资格发出的祝福详情（下单时间，数量） 
    */
    public function getLists(){
        $phoneNub=Request::input('phoneNub');
        $type=Request::input('type');
        $count="";
        $errcode="";
        $msg="";
        $changeNub="";
        $arrchanges="";
        $array="";
        
        if ($type==1) {//赠送端
            //$changeNub 有资格发起祝福的条数
            $changes=DB::table('frominfos')->where('fromNub','=',$phoneNub)
            ->where('type','=','0')
            ->select('time','number','orderNub')
            ->orderBy('time','desc')->get();
            $changeNub=count($changes);
            $arrchanges=array();
            //获取未发出祝福详情
            foreach ($changes as $key => $value) {
                $arrchanges[]=array('time'=>$value->time,'number'=>$value->number,'orderNub'=>$value->orderNub);
            }
            //发出祝福总条数
            $lists=DB::table('wishes')->where('fromNub','=',$phoneNub)
            ->where('type','>','0')
            ->select('id','number','fromname','toname','time','type','state')
            ->orderBy('time','desc')->get();
            $count=count($lists);
            $array=array();
            //获取发出祝福详情
            foreach ($lists as $key => $value) {
                $array[]=array('fromname'=>$value->fromname,'toname'=>$value->toname,'time'=>$value->time,'number'=>$value->number,'type'=>$value->type,'id'=>$value->id,'state'=>$value->state);
            }
            
            $errcode=0;           
        }
        if ($type==2) {//获赠方获取祝福详情
            //获取收到祝福列表
            $lists=DB::table('wishes')->where('toNub','=',$phoneNub)
            ->select('fromname','toname','time','type','id','state')
            ->orderBy('time','desc')->get(); 

            $count=count($lists);
            $array=array();
            //获取发出祝福详情
            foreach ($lists as $key => $value) {
                $common=new common();
                $state=$common->changeState($value->id);
                $array[]=array('fromname'=>$value->fromname,'toname'=>$value->toname,'time'=>$value->time,'wishId'=>$value->id,'type'=>$value->type,'state'=>$state);
            }
            
            $errcode=0;
        }
        
        $arr=array(
            'errcode'=>$errcode,
            'msg'=>$msg,
            'count'=>$count,
            'changeNub'=>$changeNub,
            'wishes'=>$array,
            'changes'=>$arrchanges
        );
        $json=json_encode($arr);
        return $json;
    }

    /*
    *功能：根据用户发起祝福，生成祝福存入数据库
    *请求方式：get
    *参数：phoneNub 当前操作用户手机号;type 祝福类型 1表示友谊的小船，2表示爱情的巨轮
    *参数：fromname 你的称呼; toname 对方的称呼; toNub 对方的手机号码;
    *参数：contentType 1表示文字 2表示语音;
    *参数：orderNub 订单编号; 
    *参数：number 月饼盒数; 
    *参数：content 祝福内容（类型是文字则是文本；类型是语音则是meidia_id）
    *参数：photo 照片信息（若不传照片则为空，否则为四张照片的media_id，中间用逗号隔开）
    *返回值类型：json
    *返回参数：$errcode 0 表示正确。1 表示错误
    *返回参数 $msg 提示信息
    */
    public function createWish(){
        $fromNub=Request::input('phoneNub');
        $orderNub=Request::input('orderNub');
        $type=Request::input('type');
        $number=Request::input('number');
        $fromname=Request::input('fromname');
        $toname=Request::input('toname');
        $toNub=Request::input('toNub');
        $contentType=Request::input('contentType');
        $content=Request::input('content');
        $photo=Request::input('photo');

        date_default_timezone_set('PRC');
        $time=date("Y-m-d");
        $state=0;
        $distance=0;
        $isopen=0;
        $level=1;
        $isphotoopen=0;
        $text="";
        $voice="";
        $photoPath="";
        if ($contentType==1) {
            $text=$content;
        }
        if ($contentType==2) {
            $medie_id=$content;
            $common=new common();
            $voice=$common->downloadVoice($medie_id);
        }
        if ((!is_null($photo))&&(strlen($photo))) {
            $photoArr=explode(",",$photo);
            for ($i=0; $i <4 ; $i++) {     
                if ($i==0) {
                    $common=new common();
                    $photoPath.=$common->downloadImage($photoArr[$i]);
                }else{
                    $common=new common();
                    $photoPath.=','.$common->downloadImage($photoArr[$i]);
                }
            }
        }
        $wish=new wish();
        $wish->fromNub=$fromNub;
        $wish->orderNub=$orderNub;
        $wish->type=$type;
        $wish->number=$number;
        $wish->fromname=$fromname;
        $wish->toname=$toname;
        $wish->toNub=$toNub;
        $wish->text=$text;
        $wish->time=$time;
        $wish->state=$state;
        $wish->distance=$distance;
        $wish->isopen=$isopen;
        $wish->level=$level;
        $wish->updated=time();
        $wish->isphotoopen=$isphotoopen;
        $wish->photoPath=$photoPath;
        $wish->voice=$voice;
        if ($wish->save()) {
            $errcode=0;
            $msg="";
        }
        else{
            $errcode=1;
            $msg="请确定参数无误";
        }
        
        $arr=array(
            'errcode'=>$errcode,
            'msg'=>$msg,
        );
        $json=json_encode($arr);
        return $json;        
    }

    /*
    *功能：获取祝福详细信息
    *请求方式：get
    *参数：wishId 请求的祝福的id
    *返回值类型：json
    *返回参数：$errcode 0 表示成功。1 表示失败
    *返回参数 $data 详情数据
    */
    public function getDetail(){
        $wishId=Request::input('wishId');
        $wish=wish::find($wishId);
        $errcode="";
        $data="";
        $msg='';
        if(count($wish)!=0) {
            $data=array(
                'text'=>$wish->text,
                'voice'=>$wish->voice,
                'toName'=>$wish->toname,
                'type'=>$wish->type,
                'level'=>$wish->level,
                'distance'=>$wish->distance,
                'fromName'=>$wish->fromname,
                'fromNub'=>$wish->fromNub,
                'photoPath'=>$wish->photopath,
                'state'=>$wish->state,
                'changes'=>$wish->changes,
                'isopen'=>$wish->isopen,
                'isphotoopen'=>$wish->isphotoopen
            );
            $errcode=0;
        }else{
            $errcode=1;
        }
        $array=array(
            'errcode'=>$errcode,
            'data'=>$data,
            );
        $json=json_encode($array);
        return $json;
    }

    /*
    *功能：获取祝福排行版数据集
    *请求方式：get
    *返回值类型：json
    *返回参数：$errcode 0 表示成功。1 表示失败
    *返回参数 :friend 友谊的小船排行数据 ; love 爱情的巨轮排行数据
    */
    public function link(){
        $errcode='';
        $friendArr=array();
        $loveArr=array();
        $friends=DB::table('wishes')->where('type','=',1)
        ->select('toname','fromname','distance','state','id')
        ->orderBy('distance','desc')
        ->get();
        foreach ($friends as $key => $value) {
            $common=new common();
            $state=$common->changeState($value->id);
            $friendArr[]=array('toname'=>$value->toname,'fromname'=>$value->fromname,'distance'=>$value->distance,'state'=>$state);
        }
        $loves=DB::table('wishes')->where('type','=',2)
        ->select('toname','fromname','distance','state','id')
        ->orderBy('distance','desc')
        ->get();
        foreach ($loves as $key => $value) {
            $common=new common();
            $state=$common->changeState($value->id);
            $loveArr[]=array('toname'=>$value->toname,'fromname'=>$value->fromname,'distance'=>$value->distance,'state'=>$state);
        }
        $errcode=0;
        $array=array(
            'errcode'=>$errcode,
            'friend'=>$friendArr,
            'love'=>$loveArr
            );
        $json=json_encode($array);
        return $json;
    }

    /*
    *功能：点赞网页授权
    *请求方式：get
    */
    public function auth(){
        $code=$_GET['code'];
        $appid="wx079deb492ee1955c";
        $secret="41ca99a03cf2b5f4a2a91d5df2cfb39b";
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        $res=file_get_contents($url);
        $array=json_decode($res);
        $openid=$array->openid;
        $common=new common();
        $flag=$common->isCome($openid);
        echo $openid.'<br/>';
        echo $flag;
        //header('location:'.'http://moongame.mamac.cn/test.html'.'?flag='.$flag);
    }

    /*
    *功能：用户点赞，处理船状态的改变
    *请求参数：wishId 点赞的祝福的id
    *请求方式：get
    *返回值类型：json
    *返回参数：errcode 0 表示成功。1 表示失败
    *返回参数：distance 点赞成功以后该祝福的新距离数
    *返回参数：level  船的形态等级
    */
    public function comeFun(){
        date_default_timezone_set('PRC');
        $errcode='';
        $distance="";
        $level="";
        $comes=0;
        $wishId=Request::input('wishId');
        $common=new common();
        $common->changeState($wishId);
        $wish=wish::find($wishId);
        $type=$wish->type;
        if ($type==1) {//友谊的小船,加100米
            $wish->distance=$wish->distance+1;
            $distance=$wish->distance;
        }
        if ($type==2) {//爱情的巨轮，加1000米
            $wish->distance=$wish->distance+10;
            $distance=$wish->distance;
        }
        $wish->comes=$wish->comes+1;
        $comes=$wish->comes;
        $wish->updated=time();
        if ($wish->save()) {
            $errcode=0;
        }else{
            $errcode=1;
        }

        $common=new common();
        $level=$common->changeLevel($wishId,$distance,$type);
        $common->isFix($comes,$wishId);
        $array=array(
            'errcode'=>$errcode,
            'distance'=>$distance,
            'level'=>$level,
            );
        $json=json_encode($array);
        return $json;        
    }

    /*
    *功能：返回签名
    *请求参数：url
    *请求方式：get
    *返回值类型：json
    *返回参数：$errcode 0 表示成功。1 表示失败
    *返回参数：signPackage
    */
    public function getsignPackage(){
        date_default_timezone_set('PRC');
        $common=new common();
        $jsapiTicket = $common->getJsApiTicket();
        $url =Request::input('url');
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=".$jsapiTicket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$url;
        $signature = sha1($string);
        $signPackage = array(
          "appId"     => 'wx079deb492ee1955c',
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
        );
        $array=array(
            'errcode'=>0,
            'signPackage'=>$signPackage,
            );
        $json=json_encode($array);
        return $json;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /*
    *功能：获赠端起航，改变公开属性及船的状态
    *请求参数：wishId 点赞的祝福的id
    *请求参数：state 小船的状态  0表示待启航、1表示航行中、2表示已进水、3表示快翻侧、5表示已翻侧)
    *请求参数：isopen 祝福是否公开 1表示公开 0表示不公开
    *请求参数：isphotoopen 照片是否公开 1表示公开 0表示不公开
    *请求方式：get
    *返回值类型：json
    *返回参数：errcode 0 表示成功。1 表示失败
    */
    public function sail(){
        $errcode='';
        $wishId=Request::input('wishId');
        $isopen=Request::input('isopen');
        $state=Request::input('state');
        $changes=Request::input('changes');
        $isphotoopen=Request::input('isphotoopen');
        $wish=wish::find($wishId);
        $wish->isopen=$isopen;
        $wish->isphotoopen=$isphotoopen;
        $wish->state=$state;
        $state=$wish->state;
        $wish->changes=$changes;
        if ($wish->save()) {
            $errcode=0;
        }
        else{
            $errcode=1;
        }
        $array=array(
            'errcode'=>$errcode,
            );
        $json=json_encode($array);
        return $state;

    }
	/*
    *功能：判断该用户是否有点赞机会用户点赞
	*请求方式：get
	*请求参数 openid 
    *返回值类型：json
    *返回参数：flag 0表示可以点赞； 1表示不可以点赞
    */
    public function isCome(){
    	$errcode="";
    	$openid=Request::input('openid');
		date_default_timezone_set('PRC');
		$res=DB::table('comes')->where('openid','=',$openid)->orderBy('day','desc')->get();
		$errcode="";
		if (count($res)==0) {
			$new=new come();
			$new->openid=$openid;
			$new->day=date("Y-m-d");
			if ($new->save()) {
				$errcode=0;
			}
			else{
				$errcode=1;
			}
			
			$flag=0;
		}
		else{
			if ($res[0]->day!=date("Y-m-d")) {
				$update=come::find($res[0]->id);
				$update->day=date("Y-m-d");
				if ($update->save()) {
					$errcode=0;
				}
				else{
					$errcode=1;
				}
				$flag=0;
			}
			else{
				$flag=1;
				$errcode=0;
			}
		}
		$array=array(
			'errcode'=>$errcode,
            'flag'=>$flag,
            );
        $json=json_encode($array);
        return $json;
		
	}

}
