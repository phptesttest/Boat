@extends('admin/nav')
@section('headder')
    .panel{
    border:2px solid #ccc;
    box-shadow:3px 3px #cccc;
    }
@endsection
@section('content')
    <div class="container">
        <div class="col-xs-12 col-sm-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">欢迎来到用户管理列表</h3>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ $errors }}</li>
                            </ul>
                        </div>
                    @endif
                    <table class="table">
                       <tr>
                           <th>账号</th>
                           <th>角色</th>
                           <th>渠道编码</th>
                           <th colspan="3">操作</th>
                       </tr>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            @if( $user->role )
                                <td>超级管理员</td>
                            @else
                                <td>营销人员</td>
                            @endif
                            <td>
                                <a href="/admin/useredit/{{$user->id}}}"><input type="button" class="btn btn-info" value="修改"></a>
                                @if($user->isable)
                                    <a href="/admin/userdisable/{{$user->id}}"><input type="button" class="btn btn-warning" value="禁用"></a>
                                @else
                                    <a href="/admin/userenable/{{$user->id}}"><input type="button" class="btn btn-primary" value="启用"></a>
                                @endif
                                <a href="/admin/userdelete/{{$user->id}}" onclick="javascript:if(confirm('确定要删除此信息吗？')){alert('删除成功！');return true;}return false;"><input type="button" class="btn btn-danger" value="删除"></a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    <a href="/admin/useradd"><input type="button" class="btn btn-info" value="增加用户"> </a>
                </div>

            </div>
        </div>

    </div>
@endsection
