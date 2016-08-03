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
                    <h3 class="panel-title">新增账号</h3>
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
                        <select name="role" id="role" onchange="gradeChange()">
                            <option  value="0" >营销人员</option>
                            <option  value="1" selected="selected" >超级管理员</option>
                        </select>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="name">用户账号</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group" id="chanelcode" style="display:none;">
                        <label for="coding">渠道编码</label>
                        <input type="text" class="form-control" id="coding" name="coding">
                    </div>
                    <div class="form-group">
                        <label for="password">用户密码</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="repassword">确认密码</label>
                        <input type="password" class="form-control" id="repassword" name="repassword">
                    </div>
                    <div class="form-group">
                        <label for="note">备注</label>
                        <input type="text" class="form-control" id="note" name="note">
                    </div>
                    <input type="submit" class="btn btn-info" value="新增">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var obj = document.getElementById('role');
        var code = document.getElementById('chanelcode');
       function gradeChange(){
           var grade = obj.options[obj.selectedIndex].value;
           if(grade==0){
               code.style.display = 'block';
           }else{
               code.style.display = 'none';
           }
       }
    </script>
@endsection
