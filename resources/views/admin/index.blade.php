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
                <h3 class="panel-title">管理员信息</h3>
            </div>
            <div class="panel-body">
                <h3>亲爱的管理员{{ Session::get('user')->name }}
                    欢迎你！！</h3>
            </div>
        </div>
    </div>
   
</div>
@endsection
               