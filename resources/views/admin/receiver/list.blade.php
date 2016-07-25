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
                <h3 class="panel-title">帅选的条件</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>获得祝福的时间</td>
                        <td><input type="radio" name='time' name="" checked="checked"><a href=''>全部时间</a></td>
                        <td><input type="radio" name="time"><a href=''>24小时之内</a></td>
                    </tr>
                    <tr>
                        <td>祝福的类型</td>
                        <td><input type="radio" name="wish" checked="checked"><a href=''>全部</a></td>
                        <td><input type="radio" name="wish"><a href=''>友谊的小船</a></td>
                        <td><input type="radio" name="wish"><a href=''>爱情的巨轮</a></td>
                        <td><input type="radio" name="wish"></ins><a href=''>未发送祝福</a></td>
                    </tr>
                    <tr>
                        <td>航行状态</td>
                        <td><input type="radio" name="staus" checked="checked"><a href=''>全部</a></td>
                        <td><input type="radio" name="staus"><a href=''>待起航</a></td>
                        <td><input type="radio" name="staus"><a href=''>航行中</a></td>
                        <td><input type="radio" name="staus"><a href=''>已进水</a></td>
                        <td><input type="radio" name="staus"><a href=''>已半沉</a></td>
                    </tr>
                    <tr>
                        <td colspan="4"><input type="" name="" class="btn btn-info" value="确认帅选"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">信息列表</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                                <td>
                                <p><lable>订单编号 <span><a href="">12366</a></span></lablel>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable>获得祝福的时间 <span><a href="">125566</a></span></lablel>
                                <lable>祝福类型 <span><a href="">1234</a></span></lablel>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable>购买数量 <span><a href="">1234466</a></span></lablel>
                                <strong>获赠方</strong>手机号码<span><a href="">32121212 </a></span>
                                <strong>航行状态</strong><span><a href="">待起航/航行中/已进水/已半沉</a></span>
                                <strong><span><a href="/admin/pagelist">查看祝福详情>>> </a></span></strong></p>
                                </td>
                    </tr>
                     <tr>
                                <td>
                                <p><lable>订单编号 <span><a href="">12366</a></span></lablel>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable>获得祝福的时间 <span><a href="">125566</a></span></lablel>
                                <lable>祝福类型 <span><a href="">1234</a></span></lablel>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable>购买数量 <span><a href="">1234466</a></span></lablel>
                                <strong>获赠方</strong>手机号码<span><a href="">32121212 </a></span>
                                <strong>航行状态</strong><span><a href="">待起航/航行中/已进水/已半沉</a></span>
                                <strong><span><a href="/admin/pagelist">查看祝福详情>>> </a></span></strong></p>
                                </td>   
                    </tr>
                </table>
                <input type="" name="" class="btn btn-info" value="导出以上数据">
            </div>
            
    </div>
    

</div>
@endsection
