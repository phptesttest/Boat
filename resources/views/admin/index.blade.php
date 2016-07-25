@extends('admin/nav')
@section('headder')
	.panel{
		border:2px solid #ccc;
		box-shadow:3px 3px #cccc;
	}
@endsection
@section('content')
<?php 

    echo Session::get('admin');

 ?>
	<div class="container">
    <div class="col-xs-12 col-sm-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">管理员信息</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>亲爱的管理员</td>
                        <td>欢迎你！！</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>xx天</td>
                    </tr>
                    
                   
                </table>
            </div>
        </div>
    </div>
   
</div>
@endsection
               