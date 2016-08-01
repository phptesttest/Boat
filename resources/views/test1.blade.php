<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>jssdk</title>
</head>
<body>
  微信分享2
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-1.8.2.min.js')}}"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
   $.ajax({
          type:"get",
          url:"http://moongame.hoomdo.com/weixin/getSignPackage",
          dataType:"json",
          data:{"url":location.href.split('#')[0]},
          success: function(msgs){
            var signPackage=msgs.signPackage;
            wx.config({
              debug: true,
              appId: signPackage.appId,
              timestamp: signPackage.timestamp,
              nonceStr: signPackage.nonceStr,
              signature: signPackage.signature,
              jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'onMenuShareAppMessage',
              ]
              });
          }
        });
  
  wx.ready(function () {
    wx.onMenuShareAppMessage({
    title: '签名测试', // 分享标题
    desc: location.href.split('#')[0], // 分享描述
    link: 'http://baidu.com', // 分享链接
    imgUrl: 'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png', // 分享图标
    type: 'link', // 分享类型,music、video或link，不填默认为link
    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
    success: function () { 
        // 用户确认分享后执行的回调函数
        alert('分享成功');
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
    }
  });
  });
</script>
</html>
