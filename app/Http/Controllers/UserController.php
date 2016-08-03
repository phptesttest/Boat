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

class UserCOntroller extends Controller
{
    //检验用户是否登录
    public function checkLogin(){
        if (!Session::get('user')) {
            return redirec('/');
        }
    }

    public function  userList(){
        $this->checkLogin();
        $user = new User();
        $users = $user->getUser();
        return view('admin.User.userlist')->with('users',$users);
    }

    public  function  userAdd(){
        $this->checkLogin();
        return view('admin.User.useradd');
    }
    public  function userAddDeal(){
        $username = Request::input('name');
        $coding = Request::input('coding');
        $password = Request::input('password');
        $repassword = Request::input('repassword');
        $role = Request::input('role');
        $note = Request::input('note');
        if($username==""| $password==""| $repassword==""){
            return redirect()->back()->with('errors','输入不能为空');
        }
        if(!$role){
            if($coding==""){
                return redirect()->back()->with('errors','渠道编码不能为空');
            }
        }
        $result = DB::table('users')->where( 'name','=',$username )->get();
        if(count($result)){
            return redirect()->back()->with('errors','该用户名已经存在,请重新输入');
        }else{
            if($password != $repassword ){
                return redirect()->back()->with('errors','两次输入的密码不一致');
            }else{
                $user = new User();
                $user->name = $username;
                $user->password = $password;
                $user->role = $role;
                $user->coding = $coding;
                $user->note = $note;
                $user->save();
                return redirect()->back()->with('errors','用户新增成功');
            }
        }
    }

    public function  userEdit($id=null){
        $this->checkLogin();
        if($id != null){
            $users = User::find($id);
            return view('admin.User.useredit')->with('users',$users);
        }else{
            return redirect()->back()->with('errors','该用户不存在');
        }
    }
    public function  userEditDeal($id=null){
        if($id != null){
            $username = Request::input('name');
            $oldpassword = Request::input('oldpassword');
            $newpassword = Request::input('newpassword');
            $repassword = Request::input('repassword');
            $coding = Request::input('coding');
            $note = Request::input('note');

            if($username==""| $oldpassword==""| $newpassword==""| $repassword==""){
                return redirect()->back()->with('errors','修改信息不能为空');
            }
            $user = User::find($id);
            if(!$user->role){
                if($coding==""){
                    return redirect()->back()->with('errors','渠道编码不能为空');
                }
            }
            if($oldpassword != $user->password ){
                return redirect()->back()->with('errors','原密码不正确');
            }
            if( $newpassword != $repassword ){
                return redirect()->back()->with('errors','新输入的两次密码不一致');
            }
            $user->name = $username;
            $user->password = $newpassword;
            $user->coding = $coding;
            $user->note = $note;
            $user->save();
            return redirect('/admin/userlist')->with('errors','账号修改成功');
        }else{
            return redirect()->back()->with('errors','该用户不存在');
        }
    }

    public function  userDisable($id){
        $this->checkLogin();
        if($id !=null ){
            $user = User::find($id);
            $user->isable = 0 ;
            $user->save();
            return redirect()->back()->with('errors','禁用成功');
        }else{
            return redirect()->back()->with('errors','该用户不存在');
        }
    }

    public function userEnable($id){
        $this->checkLogin();
        if($id !=null ){
            $user = User::find($id);
            $user->isable = 1 ;
            $user->save();
            return redirect()->back()->with('errors','启用成功');
        }else{
            return redirect()->back()->with('errors','该用户不存在');
        }
    }

    public function  userDelete($id){
        $this->checkLogin();
        if($id !=null){
            $user = User::find($id);
            $user->delete();
            return redirect()->back()->with('errors','删除成功');
        }else{
            return redirect()->back()->with('errors','该用户不存在');
        }
    }


}