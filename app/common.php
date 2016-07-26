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
    //送礼端将数据库数据导出excel
    public function giveExportExcel($res){
    	header("content-type:text/html;charset:utf8");
    	Excel::create('data',function($excel) use ($res){
			$sheet=$excel->Sheet('数据表',function($sheet) use ($res){
				$sheet->setOrientation('landscape');
				$j=3;
				$sheet->row(1, array(
				     '','','','','中秋活动送礼端导出数据'
				));
				$sheet->row(2, array(
				     '','','订单编号','送礼方手机号码','下单时间','购买数量','祝福类型'
				));
				foreach($res as $key => $value) {
					$type="";
					switch ($value->type) {
						case '0':
							$type="未发送祝福";
							break;
						case '1':
							$type="友谊的小船";
							break;
						case '2':
							$type="爱情的巨轮";
							break;
					}
					$sheet->row($j, array(
					     '','',$value->orderNub,$value->fromNub,$value->time,$value->number,$type
					));
					$j++;
				}
					
			});
			
		})->export('xlsx');
    }

    //将获赠端数据导出到excel
    public function receiveExportExcel($res){
    	header("content-type:text/html;charset:utf8");
    	Excel::create('data',function($excel) use ($res){
			$sheet=$excel->Sheet('数据表',function($sheet) use ($res){
				$sheet->setOrientation('landscape');
				$j=3;
				$sheet->row(1, array(
				     '','','','','中秋活动获赠端导出数据'
				));
				$sheet->row(2, array(
				     '','','订单编号','获赠方手机号码','获得祝福时间','祝福类型','航行状态'
				));
				foreach($res as $key => $value) {
					$type="";
					$state="";
					switch ($value->type) {
						case '0':
							$type="未发送祝福";
							break;
						case '1':
							$type="友谊的小船";
							break;
						case '2':
							$type="爱情的巨轮";
							break;
					}
					switch ($value->state) {
						case '0':
							$state="待起航";
							break;
						case '1':
							$state="航行中";
							break;
						case '2':
							$state="已进水";
							break;
						case '3':
							$state="快翻侧";
							break;
						case '5':
							$state="已翻侧";
							break;
					}
					$sheet->row($j, array(
					     '','',$value->orderNub,$value->toNub,$value->time,$type,$state
					));
					$j++;
				}
					
			});
			
		})->export('xlsx');
    }

    //检验用户是否登录
    public function checkLogin(){
		if (!Session::get('admin')) {
			return 0;
		}
		return 1;
	}
}
