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
				<h3 class="panel-title">信息搜索</h3>
			</div>
			<form class="form-horizontal" role="form" method="POST" action="{{ asset('/admin/giver/search') }}">
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
					<td><input type="text" name="msg"></td>
					</tr>
					<tr>
					<td><input type='submit' class="btn btn-info"  value='确认搜索'></td>
					</tr>
				</table>
			</div>
			</form>
		</div>

		<?php 

        	if (isset($data)) {
    	?>
        <div class="col-xs-12 col-sm-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">查询结果</h3>
                </div>
                <div class="panel-body">

                    @if(count($data)==0)

                      <p>没有搜索结果！</p>  

                    @else
                        <div class='table-responsive'>
                        <table class="table">

                            <tr>
                                <th>订单编号</th>
                                <th>送礼方手机号码</th>
                                <th>下单时间</th>
                                <th>购买数量</th>
                                <th>祝福类型</th>
                            </tr>
                            <?php foreach ($data as $dd) {
                            ?>
                            <tr>
                                <td>{{ $dd->orderNub}}</td>
                                <td>{{ $dd->fromNub}}</td>
                                <td>{{ $dd->time}}</td>
                                <td>{{ $dd->number}}</td>
                                <td>
                                <?php 
                                	switch ($dd->type) {
                                		case '0':
                                			echo "未发送祝福";
                                			break;
                                		
                                		case '1':
                                			echo "友谊的小船";
                                			break;
                            			case '2':
                            				echo "爱情的巨轮";
                            				break;


                                	}
                                 ?>
                                 </td>
                            </tr>
                            <?php } ?>

                        </table>
                        </div>
                    @endif
                    
                </div>
            </div>
        <div>
     <?php
            
        }
                 
    ?>


	</div>


</div>
@endsection
