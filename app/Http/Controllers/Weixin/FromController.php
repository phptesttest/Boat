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

class FromController extends Controller
{
    
    /*
    *功能：验证该手机是否合法，合法则返回验证码，不合法则返回错误提示
    *请求方式：post
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
            $phones=DB::table('toinfos')->where('toNub','=',$phoneNub)
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
        $content="【手机验证】欢迎使用友谊的小船！".$code."。该验证码十五分钟有效";
        $ch = curl_init();
        $url = 'http://apis.baidu.com/kingtto_media/106sms/106sms?mobile='.$phoneNub.'&content='.$content;
        $header = array(
            'apikey:5eaa9e313db7ce31cce56b820a4f60aa',

        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);

        if (strpos('#'.$res,"ok")) {
            
            //将手机号和验证码存入数据库
            $codeobj=new code();
            $codeobj->phoneNub=$phoneNub;
            $codeobj->code=$code;
            date_default_timezone_set('PRC');
            $codeobj->time=time();
            if($codeobj->save()){
                $msg="验证码发送成功";
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
    *请求方式：post
    *参数：phoneNub 手机号 code 验证码
    *返回值类型：json
    *返回参数：$errcode 0 表示正确。1 表示错误
    *返回参数 $msg 提示信息
    */
    public function validateFun(){
        date_default_timezone_set('PRC');
        $phoneNub=Request::input('phoneNub');
        $code=Request::input('code');
        $errcode="";
        $msg="";
        $lists=DB::table('codes')->where('phoneNub','=',$phoneNub)
            ->orderBy('time','desc')->get();
        if (count($lists)==0) {
            $errcode=1;
            $msg="该手机号不存在";
        }else{
            if ((time()-$lists[0]->time)>15*60) {//验证码发出超过十五分钟无效
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
    *请求方式：post
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
        
        if ($type==1) {//赠送端
            //$changeNub 有资格发起祝福的条数
            $changes=DB::table('frominfos')->where('fromNub','=',$phoneNub)
            ->where('type','=','0')
            ->select('time','number')
            ->orderBy('time','desc')->get();
            $changeNub=count($changes);
            $arrchanges=array();
            //获取未发出祝福详情
            foreach ($changes as $key => $value) {
                $arrchanges[]=array('time'=>$value->time,'number'=>$value->number);
            }
            //祝福总条数
            $lists=DB::table('wishes')->where('fromNub','=',$phoneNub)
            ->where('type','>','0')
            ->select('number','fromname','toname','time','type',)
            ->orderBy('time','desc')->get();
            $count=count($lists);
            $array=array();
            //获取发出祝福详情
            foreach ($lists as $key => $value) {
                $array[]=array('fromname'=>$value->fromname,'toname'=>$value->toname,'time'=>$value->time,'number'=>$value->number,'type'=>$value->type);
            }
            
            $errcode=0;           
        }
        if ($type==2) {//获赠方获取祝福详情
            //获取收到祝福列表
            $lists=DB::table('wishes')->where('toNub','=',$phoneNub)
            ->select('fromname','toname','time','type','id','state');
            ->orderBy('time','desc')->get();
            $count=count($lists);
            $array=array();
            //获取发出祝福详情
            foreach ($lists as $key => $value) {
                $array[]=array('fromname'=>$value->fromname,'toname'=>$value->toname,'time'=>$value->time,'wishId'=>$value->id,'type'=>$value->type,'state'=>$value->state);
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
    *请求方式：post
    *参数：phoneNub 当前操作用户手机号;type 祝福类型 1表示友谊的小船，2表示爱情的巨轮
    *参数：fromname 你的称呼; toname 对方的称呼; toNub 对方的手机号码;
    *参数：contentType 1表示文字 2表示语音; 
    *参数：content 祝福内容（类型是文字则是文本；类型是语音则是meidia_id）
    *参数：photo 照片信息（若不传照片则为空，否则为四张照片的media_id，中间用逗号隔开）
    *返回值类型：json
    *返回参数：$errcode 0 表示正确。1 表示错误
    *返回参数 $msg 提示信息
    */
    public createWish(){

    }

    /*
    *功能：获取祝福详细信息
    *请求方式：post
    *参数：wishId 请求的祝福的id
    *返回值类型：json
    *返回参数：$errcode 0 表示成功。1 表示失败
    *返回参数 $data 详情数据
    */
    public function getDetail($wishId){
        $wish=wish::find($wishId);
        $errcode=""；
        $data="";
        if(count($wish)！=0) {
            $data=array(
                'errcode'=>$errcode,
                'msg'=>$msg,
                'text'=>$wish->text,
                'voice'=>$wish->voice,
                'toName'=>$wish->toname,
                'fromName'=>$wish->fromname,
                'fromNub'=>$wish->fromNub,
                'photoPath'=>$wish->photoPaht,
                'state'=>$wish->state,
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
    *请求方式：post
    *参数：wishId 请求的祝福的id
    *返回值类型：json
    *返回参数：$errcode 0 表示成功。1 表示失败
    *返回参数 :friend 友谊的小船排行数据 ; love 爱情的巨轮排行数据
    */
    public function link(){
        $errcode='';
        $friendArr=array();
        $loveArr=array();
        $friends=DB::table('wishes')->where('type','=',1)
        ->select('toname','fromname','distance','state')
        ->orderBy('distance','desc')
        ->get();
        foreach ($friends as $key => $value) {
            $friendArr[]=array('toname'=>$value->toname,'fromname'=>$fromname,'distance'=>$value->distance,'state'=>$value->state);
        }
        $loves=DB::table('wishes')->where('type','=',2)
        ->select('toname','fromname','distance','state')
        ->orderBy('distance','desc')
        ->get();
        foreach ($loves as $key => $value) {
            $loveArr[]=array('toname'=>$value->toname,'fromname'=>$fromname,'distance'=>$value->distance,'state'=>$value->state);
        }
        $array=array(
            'errcode'=>$errcode,
            'friend'=>$friendArr,
            'love'=>$loveArr
            );
        $json=json_encode($array);
        return $json;
    }
}
