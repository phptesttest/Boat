<?
if (!function_exists('checkLogin')) {
	public fucntion checkLogin(){
		if (!Session::get('admin')) {
			return redirect('/');
		}
	}
}

?>