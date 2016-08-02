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
                    <h3 class="panel-title">账号编辑</h3>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-info">
                            <ul>
                                <li>{{ $errors }}</li>
                            </ul>
                        </div>
                    @endif
                    <form method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <p>请选择角色</p>
                        <div>
                            <select name="role" disabled="disabled">
                                @if($users->role)
                                    <option value="1">超级管理员</option>
                                @else
                                    <option value="0">营销人员</option>
                                @endif
                            </select>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="name">用户账号</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$users->name}}">
                        </div>
                        <div class="form-group">
                            <label for="coding">渠道编码</label>
                            <input type="text" class="form-control" id="coding" name="coding" value="{{$users->coding}}">
                        </div>
                        <div class="form-group">
                            <label for="password">原密码</label>
                            <input type="password" class="form-control" id="password" name="oldpassword">
                        </div>
                        <div class="form-group">
                            <label for="password">新密码</label>
                            <input type="password" class="form-control" id="password" name="newpassword">
                        </div>
                        <div class="form-group">
                            <label for="repassword">确认新密码</label>
                            <input type="password" class="form-control" id="repassword" name="repassword">
                        </div>
                        <div class="form-group">
                            <label for="note">备注</label>
                            <input type="text" class="form-control" id="note" name="note" value="{{$users->note}}">
                        </div>
                        <input type="submit" class="btn btn-info" value="修改">

                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
