<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Request;
use App\qrcode;
use DB;

class QrcodeController extends Controller
{
    //
    public function manage($id=null){
    	if (!is_null($id)) {
    		$qrcode=qrcode::find($id);
    		$path=$qrcode->path;
    		$savePath =rtrim(app_path(),'/app').'public\qrcodes\\';
    		if(!@unlink($savePath.$path)){
                return redirect()->back()->with('errors','文件删除失败');
            };
            $qrcode->delete();
    		return redirect()->back()->with('errors','删除成功');
    	}
    	//$user=Seesion::get('user');
    	$coding='001';//$user->coding;
    	$qrcode=DB::table('qrcodes')->where('coding','=',$coding)
    		->get();
    	$data=array(
    		'qrcodes'=>$qrcode,
    	);
    	return view('admin.qrcode.manage',$data);
    }

    //上传二维码
    public function upload(){
    	$name=Request::input('name');
    	if (! empty ( $_FILES ['file_stu'] ['name'] )) {
            $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];

            //判断是不是照片格式
            if (!(strtolower ( $file_type ) == "png"||strtolower ( $file_type ) == "jpeg"||strtolower ( $file_type ) == "jpg"||strtolower ( $file_type ) == "gif"))              
            {
                return redirect()->back()->with('errors','不是照片文件,请重新上传');
            }

            /*设置上传路径*/
            $savePath =rtrim(app_path(),'/app').'public\qrcodes\\';

            /*以时间来命名上传的文件*/
            $str = date ( 'Ymdhis' ); 
            $file_name = $str . "." . $file_type;

            /*是否上传成功*/
            if (!move_uploaded_file( $tmp_file, $savePath.$file_name )) {
                return redirect()->back()->with('errors','上传失败');
            }
            //将该二维码存入数据库
            $qrcode=new qrcode();
            //$user=Sessoin::get('user');
            $coding='001';//$user->coding;
            $qrcode->path=$file_name;
            $qrcode->name=$name;
            $qrcode->coding=$coding;
            $qrcode->save();
            return redirect()->back()->with('errors','上传成功');

        }else{
            return redirect()->back()->with('errors','文件上传失败');
        }
    }

    public function update($id){
    	$qrcode=qrcode::find($id);
    	$data=array(
    		'qrcode'=>$qrcode,
    	);
    	return view('admin.qrcode.update',$data);
    }

    public function updateFun($id){
    	if (! empty ( $_FILES ['file_stu'] ['name'] )) {
            $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];

            //判断是不是照片格式
            if (!(strtolower ( $file_type ) == "png"||strtolower ( $file_type ) == "jpeg"||strtolower ( $file_type ) == "jpg"||strtolower ( $file_type ) == "gif"))              
            {
                return redirect()->back()->with('errors','不是照片文件,请重新上传');
            }

            /*设置上传路径*/
            $savePath =rtrim(app_path(),'/app').'public\qrcodes\\';

            /*以时间来命名上传的文件*/
            $str = date ( 'Ymdhis' ); 
            $file_name = $str . "." . $file_type;

            /*是否上传成功*/
            if (!move_uploaded_file( $tmp_file, $savePath.$file_name )) {
                return redirect()->back()->with('errors','上传失败');
            }
            //将新的验证码地址存入数据库，删除旧的验证码
            $qrcode=qrcode::find($id);
            $oldPath=$qrcode->path;
            $qrcode->path=$file_name;
            $qrcode->save();
            if(!@unlink($savePath.$oldPath)){
                return redirect()->back()->with('errors','文件删除失败');
            };
            return redirect()->back()->with('errors','更改成功');
        }else{
            return redirect()->back()->with('errors','文件上传失败');
        }
    	
    }

    public function search(){
    	$qrcodes=DB::table('qrcodes')->orderBy('coding')
    		->first();
		$coding=$qrcodes->coding;
		$inputCode=Request::input('coding');
		if (!is_null($inputCode)) {
			$coding=$inputCode;
		}
		$qrcodes=DB::table('qrcodes')->where('coding','=',$coding)
			->get();
		if (count($qrcodes)==0) {
			return redirect()->back()->with('errors','没有搜索结果');
		}
		$data=array(
			'qrcodes'=>$qrcodes,
		);  
    	return view('admin.qrcode.search',$data);
    }

}
