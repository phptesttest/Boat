@extends('admin/nav')
@section('headder')
.panel{
border:2px solid #ccc;
box-shadow:3px 3px #cccc;
}
h4{
text-align:center;
}
@endsection
@section('content')

<h4>以下的排行是根据航行的距离由大到小进行排序</h4>
<div class="container">
    <div class="col-xs-12 col-sm-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">友谊的小船排行统计</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>订单编号</td>
                        <td>赠送方称呼</td>
                        <td>获赠方称呼</td>
                        <td>祝福等级</td>
                        <td>航行状态</td>
                        <td>航行距离</td>
                        <td>操作</td>
                    </tr>
                    @if(count($friends)==0)
                        <p>暂时还没有数据</p>
                    @else
                    <?php foreach ($friends as $friend) { ?>
                    <tr>
                        <td>{{ $friend->orderNub}}</td>
                        <td>{{ $friend->fromname}}</td>
                        <td>{{ $friend->toname}}</td>
                        <td>
                            <?php 
                                switch ($friend->level) {
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
                        <td>
                            <?php 
                                switch ($friend->state) {
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
                        <td>{{ $friend->distance}}</td>
                        <td><a href="{{ asset('/admin/receiver/pagelist')}}/{{ $friend->id}}">查看详情</a></td>
                    </tr>
                    <?php } ?>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">爱情的巨轮排行统计</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>订单编号</td>
                        <td>赠送方称呼</td>
                        <td>获赠方称呼</td>
                        <td>祝福等级</td>
                        <td>航行状态</td>
                        <td>航行距离</td>
                        <td>操作</td>
                    </tr>
                    @if(count($loves)==0)
                        <p>暂时还没有数据</p>
                    @else
                    <?php foreach ($loves as $love) { ?>
                    <tr>
                        <td>{{ $love->orderNub}}</td>
                        <td>{{ $love->fromname}}</td>
                        <td>{{ $love->toname}}</td>
                        <td>
                            <?php 
                                switch ($love->level) {
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
                        <td>
                            <?php 
                                switch ($love->state) {
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
                        <td>{{ $love->distance}}</td>
                        <td><a href="{{ asset('/admin/receiver/pagelist')}}/{{ $love->id}}">查看详情</a></td>
                    </tr>
                    <?php } ?>
                    @endif
                </table>
        </div>
    </div>
</div>
</div>

@endsection
