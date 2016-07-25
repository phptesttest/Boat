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
            <form class="form-horizontal" role="form" method="POST" action="{{ asset('/admin/giver/list') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table">
                    <tr>
                        <td>下单时间</td>
                        <td><input type="radio" name='time' checked="checked" value="0">全部时间</td>
                        <td><input type="radio" name="time" value="1">24小时之内</td>
                    </tr>
                    <tr>
                        <td>祝福的类型</td>
                        <td><input type="radio" name="wish" checked="checked" value="4">全部</td>
                        <td><input type="radio" name="wish" value="2">友谊的小船</td>
                        <td><input type="radio" name="wish" value="3">爱情的巨轮</td>
                        <td><input type="radio" name="wish" value="1">未发起祝福</td>
                    </tr>
                    <tr>
                        <td colspan="4"><input type="submit" class="btn btn-info" value="确认筛选"></td>
                    </tr>
                </table>
            </form>
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
                        <th>送礼方手机号码</th>
                        <th>下单时间</th>
                        <th>购买数量</th>
                        <th>祝福类型</th>
                    </tr>
                    <?php if (count($data)==0) { ?>
                    <p>没有记录！</p>
                    <?php }else{ ?> 
                    <?php foreach ($data as $dd) { ?>
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
                    <?php } }?>                   
                </table>
                <input type="" name="" class="btn btn-info" value="导出以上数据">
            </div>           
    </div>
</div>
@endsection
