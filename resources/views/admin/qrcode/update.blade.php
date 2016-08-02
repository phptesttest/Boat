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
                <h3 class="panel-title">更换二维码</h3>
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
                    <form method="post" action="{{ asset('admin/qrcode/update')}}/{{ $qrcode->id}}" enctype="multipart/form-data" onsubmit="return check();">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <tr class="form-group">
                            <td class='control-label' >二维码：</td>
                            <td><img style="width:100px;height:100px;" src="{{ asset('/qrcodes')}}/{{ $qrcode->path}}"></td>
                        </tr>
                        <tr>
                            <td class='control-label' >请选择新的二维码：</td>
                            <td><input  type="file" name="file_stu" /></td>
                        </tr>
                        <tr class="form-group">
                            <td class='control-label' >名称：</td>
                            <td><input id="name" type="text" name="name" value="{{ $qrcode->name}}" /></td>
                        </tr>
                        <tr class="form-group">
                            <td colspan="2"><input type="submit" value="更改" class="btn btn-info" /></td>
                        </tr>
                    </form>
                </table>
            </div>
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