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
                <h3 class="panel-title">祝福详情页面</h3>
            </div>
           <!--  7.祝福详情页面：信息包括订单编号、获赠方手机号码、获得祝福时间、祝福类型、双方称呼、祝福等级、航行状态、航行距离、祝福信息及其公开状态； -->
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>订单编号</td>
                        <td>11111</td>
                    </tr>
                    <tr>
                        <td>获赠方手机号码</td>
                        <td>1212231</td>
                    </tr>     
                    <tr>
                        <td>获得祝福时间</td>
                        <td>1212231</td>
                    </tr>     
                    <tr>
                        <td>祝福类型</td>
                        <td>1212231</td>
                    </tr>  
                    <tr>
                        <td>送礼方姓名</td>
                        <td>1212231</td>
                    </tr>   
                    <tr>
                        <td>获赠方姓名</td>
                        <td>1212231</td>
                    </tr>   
                    <tr>
                        <td>祝福等级</td>
                        <td>1212231</td>
                    </tr>   
                     <tr>
                        <td>航行状态</td>
                        <td>1212231</td>
                    </tr>
                    <tr>
                        <td>航行距离</td>
                        <td>1212231</td>
                    </tr>
                </table>
            <label>是否将你的信息公开：</label>
            <input type="radio" name="wishset" value="0" checked="checked" /><a href="">不公开</a>
            <input type="radio" name="wishset" value="1" /><a href=''>公开</a>
            <label>ps:(默认下信息不公开)</label>
            <input type="button" name="setwish" value="设定" class="btn btn-info">
            </div>
        </div>

    </div>
</div>
@endsection
               