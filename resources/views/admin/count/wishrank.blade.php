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
                        <td>双方称呼</td>
                        <td>祝福等级</td>
                        <td>航行状态</td>
                        <td>航行距离</td>
                        <td>操作</td>
                    </tr>
                    <tr>
                        <td>111222</td>
                        <td>asv</td>
                        <td>sfasf</td>
                        <td>sffs</td>
                        <td>fafa</td>
                        <td><a href=""><button class="btn btn-info">查看</button></a></td>
                    </tr>
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
                    <td>双方称呼</td>
                    <td>祝福等级</td>
                    <td>航行状态</td>
                    <td>航行距离</td>
                    <td>操作</td>
                </tr>
                <tr>
                    <td>111222</td>
                    <td>asv</td>
                    <td>sfasf</td>
                    <td>sffs</td>
                    <td>fafa</td>
                    <td><a href=""><button class="btn btn-info">查看</button></a></td>
                </tr>


            </table>
        </div>
    </div>
</div>
</div>

@endsection
