<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PHPExcel_IOFactory; 
use Excel;
use App\frominfo;
use Redirect;
use Session;
use PHPExcel_Shared_Date;

class common extends Model
{
    //将excel文件导入数据库
    public function importExcel($filename){
		$filename=rtrim(app_path(),'/app')."public\upload\\".$filename;
		//自动获取文件的类型提供给phpexcel用
		//$fileType=PHPExcel_IOFactory::identify($filename);
		//获取文件读取操作对象
		//$objReader=PHPExcel_IOFactory::createReader($fileType);
		//加载文件
		//$objPHPExcel=$objReader->load($filename);
		
		Excel::load($filename, function($objPHPExcel) {
        	/*$data = $reader->all();
        	dd($data);*/
        	foreach($objPHPExcel->getWorksheetIterator() as $sheet){//循环取sheet
				foreach($sheet->getRowIterator() as $row){//逐行处理
						if($row->getRowIndex()<2){
							continue;
						}
						$frominfo=new frominfo();
						$j=1;
						foreach($row->getCellIterator() as $cell){//逐列读取
								$data=$cell->getValue();//获取单元格数据
								switch ($j) {
									case 1:
										$frominfo->orderNub=$data;
										break;
									case 2:
										$frominfo->fromNub=$data;
										break;
									case 3:
										$data=PHPExcel_Shared_Date::ExcelToPHP($data);
										$data=date("Y-m-d",$data);
										$frominfo->time=$data;
										break;
									case 4:
										$frominfo->number=$data;
										break;		
								}							
								$j++;
								$frominfo->save();
						}
				}
			}
    	});		
    }
    public function checkLogin(){
		if (!Session::get('admin')) {
			return 0;
		}
		return 1;
	}
}
