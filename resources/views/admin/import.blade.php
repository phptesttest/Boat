<!DOCTYPE html>
<html>
<head>
	<title>php导入Excel</title>
</head>
<body>
<form method="post" action="{{ asset('admin/import')}}" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
         <h3>导入Excel表：</h3><input  type="file" name="file_stu" />

           <input type="submit"  value="导入" />
</form>

</body>
</html>