@extends('admin/nav')
@section('headder')
.panel{
border:2px solid #ccc;
box-shadow:3px 3px #cccc;
}
.selectpicker{
	background:red;
}
@endsection
@section('content')
<div class="container">
	<div class="col-xs-12 col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">信息搜索</h3>
			</div>
			<form class="form-horizontal" role="form" method="POST" action="{{ asset('/admin/receiver/search') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="panel-body">
				@if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>                            
                            <li>{{ $errors }}</li>
                        </ul>
                    </div>
                @endif
				<table class="table">
					<tr>
					<td><lable>请选择你所搜的方式</lable>
					<select name="type">
						<option value="1" selected="selected">订单编号</option>
						<option value="2">送礼方手机号码</option>
					</select>
					</td>
					<td><input type="text" name="ordernum"></td>
					</tr>
					<tr>
					<td><input type='submit' class="btn btn-info"  value='确认搜索'></td>
					</tr>
				</table>
			</div>
			</form>
		</div>


	</div>


</div>
@endsection
