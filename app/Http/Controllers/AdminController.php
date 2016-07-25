<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Request;
use App\User;
use Session;
use Redirect;
use App\common;
use DB;

class AdminController extends Controller
{

    public function pagelist(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        return view('admin.pagelist');
    }

//祝福排行统计
    public function  countwishrank(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        return view('admin.count.wishrank');
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
    //获赠端信息列表
    public function receivelist(){
        $common=new common();
        if($common->checkLogin()==0){
            return redirect('/');
        }
        //默认取出全部记录
        /*$time=0;
        $wish=3;
        $data="";
        if (Request::input('time')) {
            $time=Request::input('time');//0表示全部时间，1表示24小时之内
        }
        if (Request::input('wish')) {
            $wish=Request::input('wish');//0表示未发起祝福，1表示友谊的小船，2表示爱情的巨轮，3表示全部
        }
        if (Request::input('wish')==0) {
            $wish=Request::input('wish');
        }*/
        //对祝福类型筛选
       // if ($wish==3) {
           $data=DB::table('toinfos')
            ->orderBy('time','desc')->get();
        /*}       
        else{
            $data=DB::table('toinfos')
            ->where('type','=',$wish)
            ->orderBy('time','desc')->get();
        }
        if(count($data)==0) {
            return view('admin.receiver.list')->with('errors','没有搜索结果');   
        }*/
        $array=[
            'data'=>$data,
        ];
        return view('admin.receiver.list',$array);
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
        $type=Request::input('type');//1为按订单编号搜索，2为按手机号码搜索
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
        $time=0;
        $wish=4;
        $data="";
        if (Request::input('time')) {
            $time=Request::input('time');//0表示全部时间，1表示24小时之内
        }
        if (Request::input('wish')) {
            $wish=Request::input('wish');//1表示未发起祝福，2表示友谊的小船，3表示爱情的巨轮，4表示全部
        }

        //对祝福类型筛选
        if ($wish==4) {
           $data=DB::table('frominfos')
            ->orderBy('time','desc')->get();
        }       
        else{
            $data=DB::table('frominfos')
            ->where('type','=',($wish-1))
            ->orderBy('time','desc')->get();
        }
        $array=[
            'data'=>$data,
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
        
        return view('admin.index'); 
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
}
