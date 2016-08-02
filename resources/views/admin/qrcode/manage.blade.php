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
                <h3 class="panel-title">上传二维码</h3>
            </div>
            <div class="panel-body">
            @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>                            
                            <li>{{ $errors }}</li>
                        </ul>
                    </div>
                @endif
            <table class="table">
                    <form method="post" action="{{ asset('admin/qrcode/upload')}}" enctype="multipart/form-data" onsubmit="return check();">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <tr class="form-group">
                            <td class='control-label' >二维码：</td>
                            <td><input  type="file" name="file_stu" /></td>
                        </tr>
                        <tr class="form-group">
                            <td class='control-label' >名称：</td>
                            <td><input id="name" type="text" name="name" /></td>
                        </tr>
                        <tr class="form-group">
                            <td colspan="2"><input type="submit" value="上传" class="btn btn-info" /></td>
                        </tr>
                    </form>
                </table>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">信息列表</h3>
            </div>
            <div class="panel-body">
                <table class="table" style="text-align:center">
                    <tr style="text-align:center">
                        <th width="40%">二维码照片</th>
                        <th width="20%">名称</th>
                        <th width="40%">操作</th>
                    </tr>
                   <?php if (count($qrcodes)==0) { ?>
                    <p>没有记录！</p>
                    <?php }else{ ?> 
                    <?php foreach ($qrcodes as $qrcode) { ?>
                        <tr>
                            <td style="height:120px;"><img style="width:100px;height:100px;" src="{{ asset('/qrcodes')}}/{{ $qrcode->path}}"></td>
                            <td>{{ $qrcode->name}}</td>
                            <td>
                            	<a href="{{ asset('/admin/qrcode/update')}}/<?php echo $qrcode->id; ?>">更换</a>
                            	<a href="{{ asset('/admin/qrcode/delete')}}/<?php echo $qrcode->id; ?>">删除</a>
                            </td>
                        </tr>              
                    <?php } }?>                               
                </table>

            </div>           
    </div>
</div>
<script type="text/javascript">
function check(){
	var value=$("#name").val();
	if (value==null||value=="") {
		alert('名称不能为空');
		return false;
	}
	else{
		return true;
	}
}
</script>
@endsection