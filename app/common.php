<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PHPExcel_IOFactory; 
use Excel;
use App\frominfo;
use App\token;
use App\come;
use App\sort;
use Redirect;
use Session;
use DB;
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
										$frominfo->coding=sprintf("%03d",$data);
										$frominfo->orderNub=$this->createOrderNub($data);
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

    //根据渠道编码生成订单编号
    public function createOrderNub($coding){
    	$coding=sprintf("%03d",$coding);
    	$orderNub="";
    	$codes=DB::table('sorts')->where('coding','=',$coding)
    		->get();
    	if (count($codes)==0) {
    		$newSort=new sort();
    		$newSort->coding=$coding;
    		$number=$newSort->number+1;
    		$number=sprintf("%07d",$number);
    		$newSort->number=$number;
    		$orderNub=$coding.$number;
    		$newSort->save();
    	}else{
    		$code=sort::find($codes[0]->id);
    		$number=$code->number+1;
    		$number=sprintf("%07d",$number);
    		$code->number=$number;
    		$orderNub=$coding.$number;
    		$code->save();
    	}
    	return $orderNub;
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



	//从微信服务器下载用户上传的照片到本地服务器，并返回存储路径
	public function downloadImage($medieId){
		$token=$this->getToken();
		$url='http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$medieId.'';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);    
		curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		$media = array_merge(array('mediaBody' => $package), $httpinfo);
		//求出文件格式
		preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
		$fileExt = $extmatches[1];
		$filename = time().rand(100,999).".{$fileExt}";
		$dirname =rtrim(app_path(),'/app').'/public/images/';
		if(!file_exists($dirname)){
		    mkdir($dirname,0777,true);
		}
		file_put_contents($dirname.$filename,$media['mediaBody']);
		return $filename;
	}

	//从微信服务器下载用户上传的语音到本地服务器，并返回存储路径
	public function downloadVoice($medieId){
		$token=$this->getToken();
		$url='http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$medieId.'';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);    
		curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		$media = array_merge(array('mediaBody' => $package), $httpinfo);
		//求出文件格式
		preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
		$fileExt = $extmatches[1];
		$filename = time().rand(100,999).".{$fileExt}";
		$dirname =rtrim(app_path(),'/app').'/public/voices/';
		if(!file_exists($dirname)){
		    mkdir($dirname,0777,true);
		}
		file_put_contents($dirname.$filename,$media['mediaBody']);
		return $filename;
	}

	//获取access_token
	public function getToken(){
		date_default_timezone_set('PRC');
		$tokens=DB::table('tokens')->orderBy('time','desc')->get();
		$token="";
		if (count($tokens)==0) {
			$openid='wx079deb492ee1955c';
			$secret='41ca99a03cf2b5f4a2a91d5df2cfb39b';
			$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$openid.'&secret='.$secret;
			$tokenObj=json_decode(file_get_contents($url));
			$token=$tokenObj->access_token;
			$tokenObj=new token();			
			$tokenObj->token=$token;
			$tokenObj->time=time();
			$tokenObj->save();
		}
		else{
			$id=$tokens[0]->id;
			$tokenObj=token::find($id);
			if ((time()-$tokenObj->time)>90*60) {
				$openid='wx079deb492ee1955c';
				$secret='41ca99a03cf2b5f4a2a91d5df2cfb39b';
				$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$openid.'&secret='.$secret;
				$tokenO=json_decode(file_get_contents($url));
				$token=$tokenO->access_token;
				$tokenObj->token=$token;
				$tokenObj->time=time();
				$tokenObj->save();
			}
			else{
				$token=$tokenObj->token;
			}
		}
		return $token;
	}

	//获取jsApiTicket
	public function getJsApiTicket(){
		/*$accessToken ='8U8WUjmq3ILmY0g5_DmLv2emEGfJrT03tea0Hor-NvE9-r_TdiMXnTvrtfZdeT7zUxxxbU7ZmXHP7TyOWDJEaKgNIw9kD7dLvlSGap3HYDTmSnHR13z-aTPp3E0RvkHlNDAeAJAHXU';
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken;
        $res = json_decode($this->httpGet($url));
        $ticket = $res->ticket;*/
		date_default_timezone_set('PRC');
		$tickets=DB::table('tickets')->orderBy('time','desc')->get();
		$ticket="";
		if (count($tickets)==0) {
			$common=new common();
	        $accessToken = $common->getToken();
	        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken;
	        $res = json_decode($this->httpGet($url));
	        $ticket = $res->ticket;
	        $newTicket=new ticket();
	        $newTicket->tiket=$ticket;
	        $newTicket->time=time();
	        $newTicket->save();
		}
		else{
			$id=$tickets[0]->id;
			$ticketObj=ticket::find($id);
			if ((time()-$ticketObj->time)>90*60) {
				$common=new common();
		        $accessToken = $common->getToken();
		        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken;
		        $res = json_decode($this->httpGet($url));
		        $ticket = $res->ticket;
		        $ticketObj->tiket=$ticket;
		        $ticketObj->time=time();
		        $ticketObj->save();
			}
			else{
				$ticket=$ticketObj->tiket;
			}
		}
		
       return $ticket;
	}
	private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

	//判断该用户是否有点赞机会用户点赞
	//errcode 为1表示不可以点赞，errcode为0表示可以点赞
	public function isCome($openid){
		date_default_timezone_set('PRC');
		$res=DB::table('comes')->where('openid','=',$openid)->orderBy('day','desc')->get();
		$errcode="";
		if (count($res)==0) {
			$new=new come();
			$new->openid=$openid;
			$new->day=date("Y-m-d");
			$new->save();
			$errcode=0;
		}
		else{
			if ($res[0]->day!=date("Y-m-d")) {
				$update=come::find($res[0]->id);
				$update->day=date("Y-m-d");
				$update->save();
				$errcode=0;
			}
			else{
				$errcode=1;
			}
		}
		return $errcode;
	}

	//处理船的状态的改变
	public function changeState($wishId){
		date_default_timezone_set('PRC');
		$wish=wish::find($wishId);
		$updateStamp=$wish->updated;
		//根据船当前状态和没有点赞的天数改变船的状态
		switch ($wish->state) {
			case '1':
				if ((time()-$updateStamp)>15*24*3600) {
					$wish->state=5;
				}
				else if ((time()-$updateStamp)>7*24*3600) {
					$wish->state=3;
				}
				else if ((time()-$updateStamp)>3*24*3600) {
					$wish->state=2;
				}
				break;
			
			case '2':
				if ((time()-$updateStamp)>15*24*3600) {
					$wish->state=5;
				}
				else if ((time()-$updateStamp)>7*24*3600) {
					$wish->state=3;
				}
				break;

			case '3':
				if ((time()-$updateStamp)>15*24*3600) {
					$wish->state=5;
				}
				break;
		}
		$state=$wish->state;
		$wish->save();
		return $state;
	}

	//改变船的等级
	public function changeLevel($wishId,$distance,$type){
		$number=0;
		$level=1;
		if ($type==1) {//友谊的小船
			$number=$distance;		
		}
		if ($type==2) {//爱情的巨轮
			$number=$distance/10;
		}
		if ($number>=1314) {
			$wish=wish::find($wishId);
			$wish->level=5;
			$wish->save();
			$level=5;
		}
		else if ($number>=888) {
			$wish=wish::find($wishId);
			$wish->level=4;
			$wish->save();
			$level=4;
		}
		else if ($number>=666) {
			$wish=wish::find($wishId);
			$wish->level=3;
			$wish->save();
			$level=3;
		}
		else if ($number>=233) {
			$wish=wish::find($wishId);
			$wish->level=2;
			$wish->save();
			$level=2;
		}
		return $level;
	}
	//判断点赞数是否可以修复船的状态，并处理
	public function isFix($comes,$wishId){
		date_default_timezone_set('PRC');
		if ($comes>=50) {
			$wish=wish::find($wishId);
			$state=$wish->state;
			switch ($state) {
				case '2':
					$wish->state=1;
					$wish->save();
					break;
				
				case '3':
					$wish->state=2;
					$wish->save();
					break;
			}
		}
	}

}
