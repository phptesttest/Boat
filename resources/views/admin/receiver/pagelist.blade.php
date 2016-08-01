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
                        <td>{{ $detail->orderNub}}</td>
                    </tr>
                    <tr>
                        <td>获赠方手机号码</td>
                        <td>{{ $detail->toNub}}</td>
                    </tr>     
                    <tr>
                        <td>获得祝福时间</td>
                        <td>{{ $detail->time}}</td>
                    </tr>     
                    <tr>
                        <td>祝福类型</td>
                        <td>
                            <?php
                                if ($detail->type==1) {
                                    echo "友谊的小船";
                                }
                                if ($detail->type==2) {
                                    echo "爱情的巨轮";
                                }
                            ?>
                        </td>
                    </tr>  
                    <tr>
                        <td>赠送方称呼</td>
                        <td>{{ $detail->fromname}}</td>
                    </tr>   
                    <tr>
                        <td>获赠方称呼</td>
                        <td>{{ $detail->toname}}</td>
                    </tr>   
                    <tr>
                        <td>祝福等级</td>
                        <td>
                            <?php 
                                switch ($detail->level) {
                                    case '1':
                                        echo "第一形态";
                                        break;                                    
                                    case '2':
                                        echo "第二形态";
                                        break;
                                    case '3':
                                        echo "第三形态";
                                        break;
                                    case '4':
                                        echo "第四形态";
                                        break;
                                    case '5':
                                        echo "终极形态";
                                        break;
                                }
                            ?>
                        </td>
                    </tr>   
                     <tr>
                        <td>航行状态</td>
                        <td>
                            <?php 
                                switch ($detail->state) {
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
                                        echo "快翻侧";
                                        break;
                                    case '5':
                                        echo "已翻侧";
                                        break;
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>航行距离</td>
                        <td>{{ $detail->distance}}</td>
                    </tr>
                    <tr>
                        <td>文字内容</td>
                        <td>{{ $detail->text}}</td>
                    </tr>
                    <tr>
                        <td>照片</td>
                        <td><img style="width:100px;height:100px;" src="{{ asset('/images')}}/{{ $photo1}}"><img style="width:100px;height:100px;" src="{{ asset('/images')}}/{{ $photo2}}"><img style="width:100px;height:100px;" src="{{ asset('/images')}}/{{ $photo3}}"><img style="width:100px;height:100px;" src="{{ asset('/images')}}/{{ $photo4}}"></td>
                    </tr>
                    <tr>
                        <td>语音</td>
                        <td></td>
                    </tr>
                </table>
            <label>设置信息公开：</label>
            <form class="form-horizontal" role="form" method="POST" action="{{ asset('/admin/receiver/wishset') }}/{{ $detail->id}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="radio" name="wishset" value="0" <?php if ($detail->isopen==0) { echo "checked";} ?> />不公开
            <input type="radio" name="wishset" value="1" <?php if ($detail->isopen==1) { echo "checked";} ?> />公开
            <input type="submit" value="设定" class="btn btn-info">
            </form>
            </div>
        </div>

    </div>
</div>
@endsection
               