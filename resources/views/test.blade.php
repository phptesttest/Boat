<!DOCTYPE html>
<html>
<head>
	<title>test</title>
<script type="text/javascript" src="{{ asset('/js/jquery-1.8.2.min.js')}}"></script>
</head>
<body>
<button id="test">测试</button>
<div id="show"></div>
<script type="text/javascript">
	$(function(){
		//phoneTest
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/phoneTest",
					dataType:"html",
					data:{ "phoneNub":"18826139825",'type':'1'},
					success: function(msgs){
						alert(msgs);
					}
				});
		});*/

		//validate
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.hoomdo.com/weixin/vadliate",
					dataType:"html",
					data:{ "phoneNub":"18826139825","code":"6983","type":"1"},
					success: function(msgs){
						alert(msgs);
					}
				});
		});
*/

		//getLists
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/getLists",
					dataType:"html",
					data:{ "phoneNub":"18826139825","type":"2"},
					success: function(msgs){
						$("#show").html(msgs);
					}
				});
		});
*/
		//getDetail
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/getDetail",
					dataType:"html",
					data:{"wishId":"25"},
					success: function(msgs){
						alert(msgs);
					}
				});
		});
*/
		//获取祝福排行
		//link
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/link",
					dataType:"html",
					//data:{"type":"friend"},
					success: function(msgs){
						$("#show").html(msgs);
					}
				});
		});
*/
		//发起祝福
		//createWish
		$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/createWish",
					dataType:"html",
					data:{"phoneNub":"13556228528",'toNub':'13068890743','type':'2','fromname':'xiaoxiang','toname':'xiaozhu','contentType':'2','orderNub':'1225511','number':'2','content':'HO0YenUYqbEDY8YrYUuBE_qvzMla-WRlRvCb5Rv7IlAKZIQXhLH1FQqYlHPoMDPq','photo':'d9kn7tJUOS9M2m5JY-tg5cc6KBlRN4MHQGBBnNKE0tKKN6oClOtMue0s4tXmqaSF,d9kn7tJUOS9M2m5JY-tg5cc6KBlRN4MHQGBBnNKE0tKKN6oClOtMue0s4tXmqaSF,d9kn7tJUOS9M2m5JY-tg5cc6KBlRN4MHQGBBnNKE0tKKN6oClOtMue0s4tXmqaSF,d9kn7tJUOS9M2m5JY-tg5cc6KBlRN4MHQGBBnNKE0tKKN6oClOtMue0s4tXmqaSF'},
					success: function(msgs){
						$("#show").html(msgs);
					}
				});
		});
		//签名
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/getSignPackage",
					dataType:"html",
					data:{"url":"http://moongame.mamac.cn/weixin/test"},
					success: function(msgs){
						$("#show").html(msgs);
					}
				});
		});*/
		//用户点赞，处理船状态的改变
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/comeFun",
					dataType:"html",
					data:{"wishId":"7"},
					success: function(msgs){
						$("#show").html(msgs);
					}
				});
		});*/
		//小船起航
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.hoomdo.com/weixin/sail",
					dataType:"html",
					data:{"wishId":"27",'state':'1','isopen':'1','isphotoopen':'1'},
					success: function(msgs){
						alert(msgs);
					}
				});
		});*/
		//判断用户是否可以点赞
		/*$("#test").click(function(){
			$.ajax({
					type:"get",
					url:"http://moongame.mamac.cn/weixin/isCome",
					dataType:"html",
					data:{"openid":"o3sPbwS-3-lzu5nKrrNTZg"},
					success: function(msgs){
						alert(msgs);
					}
				});
		});*/

	})
</script>
</body>

</html>