@extends('admin/nav')
@section('headder')
.panel{
border:2px solid #ccc;
box-shadow:3px 3px #cccc;
}
@endsection
@section('content')
<div class="container">
	<div class="col-xs-12 col-sm-6">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">信息搜索</h3>
			</div>
			<div class="panel-body">
				<table class="table">
					<tr>
					<td>请输入订单编号</td>
					<td><input type="text" name="ordernum"></td>
					</tr>
					<tr>
					<td>请输入获赠方手机号码</td>
					<td><input type="text" name="photonum"></td>
					</tr>
					<tr>
					<td><input type='' class="btn btn-info"  value='确认搜索'></td>
					</tr>
				</table>
			</div>
		</div>


	</div>


</div>
@endsection
