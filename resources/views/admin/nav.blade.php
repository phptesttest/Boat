
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>友谊的小船</title>

    <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('/js/WdatePicker.js')}}"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <!-- Fonts -->
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style>
    body {
    padding-top: 50px;
    padding-bottom: 40px;
    color: black;
    }
    .navbar-nav li a{
        color:black;
    }
    .navbar{
        background-color: #FFF;
    }
    .dropdown-menu{
        background-color: #FFF;
    }
    @yield('headder')
    </style>
</head>
<body id="app-layout" style='padding-top:70px;'>
    <nav class="navbar navbar-default navbar navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" >后台管理系统</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">送礼端模块<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/admin/import">excel导入</a></li>
                        <li><a href="/admin/giver/search">送礼信息搜索</a></li>
                        <li><a href="/admin/giver/list">送礼信息列表/信息帅选/导出</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">获赠端模块<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/admin/receiver/search">获赠信息搜索</a></li>
                        <li><a href="/admin/receiver/list">获赠信息列表/信息筛选/导出</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">排行榜<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/admin/count/wishrank">祝福排行统计</a></li>
                    </ul>
                </li>
                <li><a href="/admin/logout">退出</a></li>
            </ul>
        </div>
    </div>
</nav>
    @yield('content')

</body>
</html>


