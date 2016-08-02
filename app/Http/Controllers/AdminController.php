<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Request;
use App\User;
use App\wish;
use Session;
use Redirect;
use App\common;
use DB;

class AdminController extends Controller
{


    //测试
    public function test(){
        /*$direct=urlencode('http://moongame.hoomdo.com/test.html?wishId=1');
        $appid='wx079deb492ee1955c';
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$direct."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
        echo $url;*/
        //header("location:http://moongame.mamac.cn/test?id=2");
        return view('test');
    }
    //祝福排行统计
    public function  countwishrank(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        $friends=DB::table('wishes')->where('type','=',1)
        ->select('orderNub','level','toname','fromname','distance','state','id')
        ->orderBy('distance','desc')
        ->get();
        $loves=DB::table('wishes')->where('type','=',2)
        ->select('orderNub','level','toname','fromname','distance','state','id')
        ->orderBy('distance','desc')
        ->get();
        $array=[
            'friends'=>$friends,
            'loves'=>$loves,
        ];
        return view('admin.count.wishrank',$array);
    }

    //流量统计
    public function countflow(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        return view('admin.count.flow');
    }

    public function receivesearch(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        $type=Request::input('type');//1为按订单编号搜索，2为按手机号码搜索
        $msg=Request::input('msg');
        $msg=trim($msg);
        $data="";
        if (!$type) {
            # code...
        }
        if ($type==1) {
            $data=DB::table('toinfos')->where('orderNub','=',$msg)
            ->orderBy('time','desc')->get();
            if (count($data)==0) {
                return redirect()->back()->with('errors','没有搜索结果');   
            }
        }
        if ($type==2) {
            $data=DB::table('toinfos')->where('toNub','=',$msg)
            ->orderBy('time','desc')->get();
            if (count($data)==0) {
                return redirect()->back()->with('errors','没有搜索结果');   
            }
        }
        $array=[
            'data'=>$data,
        ];
        return view('admin.receiver.search',$array);
    }

    public function receivesearchFun(){
        $type=Request::input('type');//1为按订单编号搜索，2为按手机号码搜索
        $msg=Request::input('msg');
        $msg=trim($msg);
        $data="";
        if ($type==1) {
            $data=DB::table('wishes')->where('orderNub','=',$msg)
            ->orderBy('time','desc')->get();
            if (count($data)==0) {
                return redirect()->back()->with('errors','没有搜索结果');   
            }
        }
        if ($type==2) {
            $data=DB::table('wishes')->where('toNub','=',$msg)
            ->orderBy('time','desc')->get();
            if (count($data)==0) {
                return redirect()->back()->with('errors','没有搜索结果');   
            }
        }
        $array=[
            'data'=>$data,
        ];
        return view('admin.receiver.search',$array);
    }

    public function pagedetail($id){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        $detail=wish::find($id);
        $photoPath=$detail->photopath;
        $arr=explode(',',$photoPath);
        $array=[
            'detail'=>$detail,
            'photo1'=>$arr[0],
            'photo2'=>$arr[1],
            'photo3'=>$arr[2],
            'photo4'=>$arr[3],
        ];
        return view('admin.receiver.pagelist',$array);
    }

    //获赠端信息列表
    public function receivelist(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        //默认取出全部记录
        $time=1;
        $state=1;
        $wish=4;
        $data="";
        if (Request::input('time')) {
            $time=Request::input('time');//1表示全部时间
        }
        if (Request::input('wish')) {
            $wish=Request::input('wish');//1表示未发起祝福，2表示友谊的小船，3表示爱情的巨轮，4表示全部
        }
        if (Request::input('state')) {
            $state=Request::input('state');//1表示全部，2表示待起航，3表示航行中，4表示已进水，5表示快侧翻，7表示已侧翻
        }
        //对祝福类型筛选
        if ($wish==4) {
            if ($time==1) {
                if ($state==1) {//全祝福类型，全时间，全状态筛选
                    $data=DB::table('wishes')
                        ->orderBy('time','desc')->get();
                }else{//全祝福类型，全时间，状态有条件筛选
                    $data=DB::table('wishes')
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get();
                }
                
            }else{
                if ($state==1) {//全祝福类型，时间有条件，全状态筛选
                   $data=DB::table('wishes')
                        ->where('time','=',$time)
                        ->orderBy('time','desc')->get(); 
                }else{//全祝福类型，时间有条件，状态有条件筛选
                   $data=DB::table('wishes')
                        ->where('time','=',$time)
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get(); 
                }
                
            }
           
        }       
        else{
            if ($time==1) {
                if ($state==1) {//祝福有条件，全时间，全状态筛选
                    $data=DB::table('wishes')
                        ->where('type','=',($wish-1))
                        ->orderBy('time','desc')->get();
                }else{//祝福有条件，全时间，状态有条件筛选
                    $data=DB::table('wishes')
                        ->where('type','=',($wish-1))
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get();
                }
                
            }else{
                if ($state==1) {//祝福有条件，时间有条件，全状态筛选
                   $data=DB::table('wishes')
                        ->where('type','=',($wish-1))
                        ->where('time','=',$time)
                        ->orderBy('time','desc')->get(); 
                }else{//祝福有条件，时间有条件，状态有条件筛选
                   $data=DB::table('wishes')
                        ->where('type','=',($wish-1))
                        ->where('time','=',$time)
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get(); 
                }
                
            }
        }
        if(count($data)==0) {
            $array=[
                'data'=>$data,
                'wish'=>$wish,
                'time'=>$time,
                'state'=>$state,
            ];
            return view('admin.receiver.list',$array)->with('errors','没有搜索结果');   
        }
        $array=[
            'data'=>$data,
            'wish'=>$wish,
            'time'=>$time,
            'state'=>$state,
        ];
        return view('admin.receiver.list',$array);
    }

    //设置祝福是否公开
    public function wishset($wishId){
        $isopen=Request::input('wishset');
        $wish=wish::find($wishId);
        $wish->isopen=$isopen;
        $wish->save();
        return redirect('admin/receiver/pagelist/'.$wishId);

    }

    //送礼端信息搜索
    public function givesearch(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        return view('admin.giver.search');
    }

    //送礼端信息搜索的处理
    public function givesearchFun(){
        $type=Request::input('type');//1为按订单编号搜索，2为按手机号码搜索，3为渠道编码
        $msg=Request::input('msg');
        $msg=trim($msg);
        $data="";
        if ($type==1) {
            $data=DB::table('frominfos')->where('orderNub','=',$msg)
            ->orderBy('time','desc')->get();
            if (count($data)==0) {
                return redirect()->back()->with('errors','没有搜索结果');   
            }
        }
        if ($type==2) {
            $data=DB::table('frominfos')->where('fromNub','=',$msg)
            ->orderBy('time','desc')->get();
            if (count($data)==0) {
                return redirect()->back()->with('errors','没有搜索结果');   
            }
        }
        if ($type==3) {
            $data=DB::table('frominfos')->where('coding','=',$msg)
            ->orderBy('time','desc')->get();
            if (count($data)==0) {
                return redirect()->back()->with('errors','没有搜索结果');   
            }  
        }
        $array=[
            'data'=>$data,
            
        ];
        return view('admin.giver.search',$array);

    }

    //送礼端信息搜索,筛选，导出
    public function givelist(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        //默认取出全部记录
        $time=1;
        $wish=4;
        $data="";
        $coding="";
        if (Request::input('time')) {
            $time=Request::input('time');//1表示全部时间
        }
        if (Request::input('wish')) {
            $wish=Request::input('wish');//1表示未发起祝福，2表示友谊的小船，3表示爱情的巨轮，4表示全部
        }

        //对祝福类型筛选
        if ($wish==4) {
            //对时间筛选
            if ($time==1) {
                $data=DB::table('frominfos')
                ->orderBy('coding','ASC')->get();
            }else{
                $data=DB::table('frominfos')
                ->where('time','=',$time)
                ->orderBy('coding','ASC')->get();
            }
           
        }       
        else{
            if ($time==1) {
                $data=DB::table('frominfos')
                ->where('type','=',($wish-1))
                ->orderBy('coding','ASC')->get();
            }else{
                $data=DB::table('frominfos')
                ->where('type','=',($wish-1))
                ->where('time','=',$time)
                ->orderBy('coding','ASC')->get();
            }
            
        }
        $array=[
            'data'=>$data,
            'wish'=>$wish,
            'time'=>$time,
        ];
        return view('admin.giver.list',$array);
    }
    //后台主页
    public function index(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        return view('admin.index');
    }
    //
    public function login(){
    	return view('admin.login');
    }

    //登录处理
    public function loginDeal(){
    	$user=User::all();
    	if (count($user)==0) {
    		$user=new User();
    		$user->name='boatfriend';
    		$user->password='123456';
    		$user->save();
    		$user=User::all();
    		
    	}
    	
    	$username=Request::input('username');
    	$password=Request::input('password');
    	if ($username!=$user[0]->name) {
    		return redirect()->back()->with('errors','账号错误');
    	}
    	if ($password!=$user[0]->password) {
    		return redirect()->back()->with('errors','密码错误');
    	}
        Session::put('admin','adminIn');
        
        //return view('admin.index'); 
        return redirect('/admin/index');
    }

    public function logout(){
        Session::forget('admin');
        Session::flush();
        return redirect('/');
    }
    
    public function import(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        };
        return view('admin.giver.import');        
    }

    //将execl文件导入数据库
    public function importFun(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }

        if (! empty ( $_FILES ['file_stu'] ['name'] )) {
            $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];

            /*判别是不是.xls或.xlsx文件，判别是不是excel文件*/
            if (!(strtolower ( $file_type ) == "xls"||strtolower ( $file_type ) == "xlsx"))              
            {
                return redirect()->back()->with('errors','不是Excel文件,请重新上传');
            }

            /*设置上传路径*/
            $savePath =rtrim(app_path(),'/app').'public\upload\\';

            /*以时间来命名上传的文件*/
            $str = date ( 'Ymdhis' ); 
            $file_name = $str . "." . $file_type;

            /*是否上传成功*/
            if (!move_uploaded_file( $tmp_file, $savePath.$file_name )) {
                return redirect()->back()->with('errors','上传失败');
            }
            //将excel数据导入数据库
            $common=new common();
            $common->importExcel($file_name);
            if(!@unlink($savePath.$file_name)){
                return redirect()->back()->with('errors','文件删除失败');
            };

        }else{
            return redirect()->back()->with('errors','文件上传失败');
        }
        return redirect()->back()->with('errors','导入成功');
    }

    //将送礼端数据库数据导出到excel
    public function giveExportFun($flag){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        $arr=explode(",",$flag);
        $time=$arr[0];
        $wish=$arr[1];
        $data="";
        //对祝福类型筛选
        if ($wish==4) {
            //对时间筛选
            if ($time==1) {
                $data=DB::table('frominfos')
                ->orderBy('time','desc')->get();
            }else{
                $data=DB::table('frominfos')
                ->where('time','=',$time)
                ->orderBy('time','desc')->get();
            }
           
        }       
        else{
            if ($time==1) {
                $data=DB::table('frominfos')
                ->where('type','=',($wish-1))
                ->orderBy('time','desc')->get();
            }else{
                $data=DB::table('frominfos')
                ->where('type','=',($wish-1))
                ->where('time','=',$time)
                ->orderBy('time','desc')->get();
            }
            
        }
        $common=new common();
        $common->giveExportExcel($data);
        return redirect('admin/giver/list');
    }
    //将获赠端数据导出到excel
    public function receiveExportFun($flag){
        $arr=explode(",",$flag);
        $time=$arr[0];
        $wish=$arr[1];
        $state=$arr[2];
        $data="";
        //对祝福类型筛选
        if ($wish==4) {
            if ($time==1) {
                if ($state==1) {//全祝福类型，全时间，全状态筛选
                    $data=DB::table('toinfos')
                        ->orderBy('time','desc')->get();
                }else{//全祝福类型，全时间，状态有条件筛选
                    $data=DB::table('toinfos')
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get();
                }
                
            }else{
                if ($state==1) {//全祝福类型，时间有条件，全状态筛选
                   $data=DB::table('toinfos')
                        ->where('time','=',$time)
                        ->orderBy('time','desc')->get(); 
                }else{//全祝福类型，时间有条件，状态有条件筛选
                   $data=DB::table('toinfos')
                        ->where('time','=',$time)
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get(); 
                }
                
            }
           
        }       
        else{
            if ($time==1) {
                if ($state==1) {//祝福有条件，全时间，全状态筛选
                    $data=DB::table('toinfos')
                        ->where('type','=',($wish-1))
                        ->orderBy('time','desc')->get();
                }else{//祝福有条件，全时间，状态有条件筛选
                    $data=DB::table('toinfos')
                        ->where('type','=',($wish-1))
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get();
                }
                
            }else{
                if ($state==1) {//祝福有条件，时间有条件，全状态筛选
                   $data=DB::table('toinfos')
                        ->where('type','=',($wish-1))
                        ->where('time','=',$time)
                        ->orderBy('time','desc')->get(); 
                }else{//祝福有条件，时间有条件，状态有条件筛选
                   $data=DB::table('toinfos')
                        ->where('type','=',($wish-1))
                        ->where('time','=',$time)
                        ->where('state','=',($state-2))
                        ->orderBy('time','desc')->get(); 
                }
                
            }
        }
        $common=new common();
        $common->receiveExportExcel($data);
        return redirect('admin/receiver/list');

    }
}
