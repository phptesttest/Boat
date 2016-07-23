<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Request;
use App\User;
use App\common;

class AdminController extends Controller
{
    //
    public function login(){
    	return view('Admin.login');
    }

    //登录处理
    public function loginDeal(){
    	$user=User::all();
    	if (count($user)==0) {
    		$user=new User();
    		$user->username='boatfriend';
    		$user->password='123456';
    		$user->save();
    		$user=User::all();
    		
    	}
    	
    	$username=Request::input('username');
    	$password=Request::input('password');
    	if ($username!=$user[0]->username) {
    		return redirect()->back()->with('errors','账号错误');
    	}
    	if ($password!=$user[0]->password) {
    		return redirect()->back()->with('errors','密码错误');
    	}
    	echo "登录成功";

    }
    
    public function import(){
        return view('admin.import');        
    }

    //将execl文件导入数据库
    public function importFun(){

        if (! empty ( $_FILES ['file_stu'] ['name'] )) {
            $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];

            /*判别是不是.xls或.xlsx文件，判别是不是excel文件*/
            if (!(strtolower ( $file_type ) == "xls"||strtolower ( $file_type ) == "xlsx"))              
            {
                echo '不是Excel文件,请重新上传';
                exit();
            }

            /*设置上传路径*/
            $savePath =rtrim(app_path(),'/app').'public\upload\\';

            /*以时间来命名上传的文件*/
            $str = date ( 'Ymdhis' ); 
            $file_name = $str . "." . $file_type;

            /*是否上传成功*/
            if (!move_uploaded_file( $tmp_file, $savePath.$file_name )) {
                echo '上传失败';
                exit();
            }
            //将excel数据导入数据库
            $common=new common();
            $common->importExcel($file_name);

        }else{
            echo "文件上传失败";
            exit();
        }
    }
}
