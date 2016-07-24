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
                <h3 class="panel-title">请一定的格式导入excel</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <form method="post" action="{{ asset('admin/import')}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                    <label class='control-label' >请导入Excel表：</label>
                    <input  type="file" name="file_stu" />
                    </div>
                    <div class="form-group">
                    <input type="submit"  value="导入" class="btn btn-info" />
                    </div>
                </form>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
