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
                <h3 class="panel-title">筛选的条件</h3>
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
                        <th>订单编号</th>
                        <th>获赠方手机号码</th>
                        <th>获得祝福时间</th>
                        <th>祝福类型</th>
                        <th>航行状态</th>
                        <th>查看详情</th>
                    </tr>
                    <?php if (count($data)==0) { ?>
                    <p>没有记录！</p>
                    <?php }else{ ?>                   
                    <?php foreach ($data as $dd) { ?>
                        <tr>
                            <td>{{ $dd->orderNub}}</td>
                            <td>{{ $dd->toNub}}</td>
                            <td>{{ $dd->time}}</td>
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
                            <td>
                            <?php 
                                switch ($dd->type) {
                                    case '0':
                                        echo "待起航";
                                        break;
                                    
                                    case '1':
                                        echo "航行中";
                                        break;
                                    case '2':
                                        echo "已进水";
                                        break;
                                    case '3':
                                        echo "快侧翻";
                                        break;
                                    case '5':
                                        echo "已侧翻";
                                        break;
                                }
                             ?>
                            </td>
                            <td><button>查看详情</button></td>
                        </tr>              
                    <?php } } ?>
                </table>
                <input type="" name="" class="btn btn-info" value="导出以上数据">
            </div>
            
    </div>
    

</div>
@endsection
