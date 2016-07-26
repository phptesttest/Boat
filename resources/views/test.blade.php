<!DOCTYPE html>
<html>
<head>
	<title>test</title>
<script type="text/javascript" src="{{ asset('/js/jquery-1.8.2.min.js')}}"></script>
</head>
<body>
<button id="test">测试</button>

<script type="text/javascript">
	$(function(){
		//phoneTest
		/*$("#test").click(function(){
			$.ajax({
					type:"POST",
					url:"{{ asset('/weixin/phoneTest')}}",
					dataType:"html",
					data:{ "phoneNub":"15218190853"},
					success: function(msgs){
						alert(msgs);
					}
				});
		});*/

		//validate
		/*$("#test").click(function(){
			$.ajax({
					type:"POST",
					url:"{{ asset('/weixin/validate')}}",
					dataType:"html",
					data:{ "phoneNub":"15218190853","code":"6666"},
					success: function(msgs){
						alert(msgs);
					}
				});
		});*/

		//getLists
		$("#test").click(function(){
			$.ajax({
					type:"POST",
					url:"{{ asset('/weixin/getLists')}}",
					dataType:"html",
					data:{ "phoneNub":"18826139825","type":"1"},
					success: function(msgs){
						alert(msgs);
					}
				});
		});
	})
</script>
</body>

</html>