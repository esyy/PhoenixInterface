<?php
/**
 * 火凤凰订票选座
 * @Author esyy  
 * @version 1.6
 * @var data 2016/7/30
 */
class PhoenixInterface
{
    /*************测试商户和密匙***********/
	/*
	private $wsdl='http://##################mx?WSDL'; 
	//private $userId = '#############';//授权码
	//private $userPass = '#################';	
	private $pa = array(
			'userId' => '##############',
			'userPass' => '###################'
	);
	*/

	/*private $wsdl='http://#########################mx?WSDL';
	private $userId = '#################';//授权码
	private $userPass = '#################';
	private $pa = array(
			'userId' => '###################',
			'userPass' => '###################'
	);*/
	
	private $wsdl='http://2######################smx?WSDL'; 
	private $userId = '#############';//授权码
	private $userPass = '#################';
	
	private $pa = array(
			'userId' => 'W################',
			'userPass' => '####################'
	);
	
	public function cinema($param=false){
		$param = $this->pa;
		$data = $this->getData('qryCinema',$param,'qryCinemaResult');
		//echo '返回数据:<br />';
		//var_dump($data);
		$res = $data['cinema'];
		
		foreach($res as $key => $val){
			if(is_array($val)){
				foreach($val as $ke=>$va){
					if($ke == '@attributes'){
						foreach($va as $k => $v){
							$cinema[$key][$k] = $v;
						}
					}else{
						foreach($va as $hk=>$hall){
							//echo '数组hall';
							//echo '<pre>';
							//print_r($hall);
							foreach($hall['@attributes'] as $hkey=>$hval){
								//echo 'hk='.$hk.'===hkey='.$hkey.'===hval='.$hval.'<br />';
								$cinema[$key]['hall'][$hk][$hkey] = $hval;
								
							}
						}
					}
				}
			}
			
		}
		//echo '<pre>';print_r($cinema);return;
		return $cinema;
	}
	/**
	 * 获取座位信息
	 * @param string $param
	 * @return unknown
	 */
	public function seat($param=false){
		$param = array_merge($this->pa,$param);
		$data = $this->getData('qrySeat',$param,'qrySeatResult');
		//防止对方服务器异常
		if(!$data['effective']['section']){
			$data = $this->getData('qrySeat',$param,'qrySeatResult');
		}
		if(!$data['effective']['section']){
			$data = $this->getData('qrySeat',$param,'qrySeatResult');
		}
		if(!$data['effective']['section']){
			$data = $this->getData('qrySeat',$param,'qrySeatResult');
		}
		if(isset($data['effective']['section'])){
			$seatarr = $data['effective']['section'];
		}else{
			$cur = time();
			foreach($data['effective'] as $v){
				if(strtotime($v['@attributes']['date'])<$cur)$seatarr = $v['section'];
			}
		}
		//echo '<pre>';print_r($data);exit;
		foreach($seatarr as $key => $val){
			//多个section分区
			if(is_numeric($key)){
				foreach($val as $secattr => $secval){
						if($secattr == '@attributes'){
							foreach($secval as $att => $ava){
								//$seat['section'][$sec][$att] = $ava;
								//$seat_info['section'][$sec][$att] = $ava;
								$seat_info['section'][$secval['id']][$att] = $ava;
							}
							$sid = $secval['id'];
						}else{
							//seatInfo
							foreach($secval as $seatkey => $seatinfo){

								foreach($seatinfo as $k=>$info){
									if($info['loveInd'] == '0'){
										$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['type'] = 1;
									}else{
										$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['type'] = 2;
									}
									
									if($info['damagedFlg'] == 'N'){
										$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['status'] = 1;
									}else{
										$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['status'] = 0;
									} 
										$seat_info['section'][$sid]['row'][$info['rowNum']] = $info['rowId'];
										$seat_info['section'][$sid]['col'][$info['columnNum']] = $info['columnId'];
										$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['loveInd'] = $info['loveInd'];
										$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['id'] = $sid.'_'.$info['rowId'].'_'.$info['columnId'];
										$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['col'] = $info['columnId'];
								}
							}
						}
					}
			}else{
				if($key == '@attributes'){
					foreach($val as $att => $ava){
						$seat_info['section'][$val['id']][$att] = $ava;
					}
					$sid = $val['id'];
				}else{
					//seat
					foreach($val as $seatkey => $seatinfo){
						foreach($seatinfo as $k=>$info){
							//echo 'info='; var_dump($info).'<hr />'; 
					
							if($info['loveInd'] == '0'){
								$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['type'] = 1;
							}else{
								$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['type'] = 2;
							}
							
							if($info['damagedFlg'] == 'N'){
								$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['status'] = 1;
							}else{
								$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['status'] = 0;
							}
								//echo 'rowNum='.$info['rowNum'].'******rowId='.$info['rowId'].'<hr />';
								//echo $sid.'<hr />';
								$seat_info['section'][$sid]['row'][$info['rowNum']] = $info['rowId'];
								$seat_info['section'][$sid]['col'][$info['columnNum']] = $info['columnId'];
								$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['loveInd'] = $info['loveInd'];
								$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['id'] = $sid.'_'.$info['rowId'].'_'.$info['columnId'];
								$seat_info['seat'][$sid][$info['rowNum']][$info['columnNum']]['col'] = $info['columnId'];
								//print_r($seat_info);echo '<hr />';
						}
						
					}
				}
			}
		}
		return $seat_info;
	}
	
	public function show($param=false){
		$param = array_merge($this->pa,$param);
		$data = $this->getData('qryShow',$param,'qryShowResult');
	}
	
	public function ticket($param=false){
		$param = array_merge($this->pa, $param);
		//print_r($param);
		$data = $this->getData('qryTicket',$param,'qryTicketResult');
		//防止网络超时,失败请求3次
		if($data['@attributes']['message']=='' && !isset($data['section'])){
			$data = $this->getData('qryTicket',$param,'qryTicketResult');
		}
		if($data['@attributes']['message']=='' && !isset($data['section'])){
			$data = $this->getData('qryTicket',$param,'qryTicketResult');
		}
	    //echo 'notseat:<pre>';print_r($param);print_r($data);
		if($data['section']){
			foreach($data as $key => $val){
				if($key == '@attributes'){
					foreach($val as $infokey=>$info){
							$cinema[$infokey] = $info;
					}
				}else{
					//section
					foreach($val as $seckey=>$section){
						if($seckey == '@attributes'){
							$sid = $section['id'];
						}elseif($seckey == 'seat'){
						        if(isset($section['@attributes'])){
								     $cinema['notseat']['section'][$sid][$section['@attributes']['rowId']][$section['@attributes']['columnId']] = 1;
								}else{
									foreach($section as $row => $rinfo){
										$cinema['notseat']['section'][$sid][$rinfo['@attributes']['rowId']][$rinfo['@attributes']['columnId']] = 1;
									}
								}
						}
					}
				}
			}
		}
	    //echo '不可选座位最终结果:<br />';echo '<pre>';print_r($cinema);exit;
		return $cinema;
	}
	
	//锁座
	public function lockseat($param=false){

		$param = array_merge($this->pa,$param);
		if(substr_count($this->wsdl,'http://2########################OM.asmx')>0){
		    $param['lockMinuteTime']=15;
		    $set_method='webLockSeat';
			$result_method='webLockSeatResult';
		}else{
			$set_method='lockSeat';
			$result_method='lockSeatResult';
		}
		$check = http_build_query($param);
		$param['checkValue'] = md5($check);
        $data = $this->getData($set_method,$param,$result_method);
		//echo '<pre>';print_r($data);
		return $data;
	}
	
	//解锁
	public function unlock($param=false){

		$param = array_merge($this->pa,$param);
		$check = http_build_query($param);
	
		//echo 'check='.$check.'<hr />';
		//echo md5($check).'<hr />';
		$param['checkValue'] = md5($check);
		if(substr_count($this->wsdl,'http://#######################OM.asmx')>0){
		    $data = $this->getData('webUnlockSeat',$param,'webUnlockSeatResult');
		}else{
            $data = $this->getData('unlockSeat',$param,'unlockSeatResult');
		}
		//echo '<pre>';
		//print_r($data);
		return $data;
	}
	
	//确认订单
	public function fixorder($param=false){

		$param = array_merge($this->pa,$param);
		$check = http_build_query($param);
	
		
		$param['checkValue'] = md5($check);
    
        $data = $this->getData('fixOrder',$param,'fixOrderResult');
		//防止网络超时,多次请求
		if(!$data['confirmationId']){
			$data = $this->getData('fixOrder',$param,'fixOrderResult');
		}
		if(!$data['confirmationId']){
			$data = $this->getData('fixOrder',$param,'fixOrderResult');
		}
		if(!$data['confirmationId']){
			$data = $this->getData('fixOrder',$param,'fixOrderResult');
		}
		if(!$data['confirmationId']){
			$data = $this->getData('fixOrder',$param,'fixOrderResult');
		}
		if(!$data['confirmationId']){
			$data = $this->getData('fixOrder',$param,'fixOrderResult');
		}
		if(!$data['confirmationId']){
			$data = $this->getData('fixOrder',$param,'fixOrderResult');
		}
		//echo '<pre>';
		//print_r($data);
		return $data;
	}
	//混合支付方式购票接口
	public function fixMixOrder($param=false){

		$param = array_merge($this->pa,$param);
		$check = http_build_query($param);
	
		$param['checkValue'] = md5($check);
    
        $data = $this->getData('fixMixOrder',$param,'fixMixOrderResult');
		//echo '<pre>';
		//print_r($data);
		return $data;
	}
	//订单查询
	public function qryorder($param=false){

		$param = array_merge($this->pa,$param);
		$check = http_build_query($param);
	
		
		$param['checkValue'] = md5($check);
         $data = $this->getData('qryOrder',$param,'qryOrderResult');
       
		//echo '<pre>';
		//print_r($data);
		return $data;
	}
	/**
	* ------------------------------------------------------
	* 请求地址获取返回数据
	* @param str method 地址方法
	* @param array param 传入参数
	* @param array result 返回数据对象中包含有效信息的属性名
	* @return 
	* ------------------------------------------------------
	*/
	private function getData($method,$param=false,$result){
		$wsdl = $this->wsdl;
        //实例化对象
        $client=new SoapClient($wsdl);
		//接口方法
        $res = $client->$method($param); 
	    //var_dump($res);
		$str = $res->$result;
		$str = str_ireplace('GBK','utf-8',$str);
		$xml=simplexml_load_string($str);
		//file_put_contents(dirname(__FILE__).'/'.$method.date('Ymd').'.log',date("Y-m-d H:i:s")."\r\n".$method.'result:'.$str,FILE_APPEND);
	    $data = json_decode(json_encode($xml),true);
		
		$LOG_DIR= PATH_SEPARATOR==';'?'########\openapi\\':'/app/###########';
		@file_put_contents($LOG_DIR.'PhoenixInterface_'.date('Ymd').'.log',date("Y-m-d H:i:s")."_method:".$method."\r\n".var_export($param, true)."\r\n".var_export($data, true)."\n\r" .$str."\n\r",FILE_APPEND);
		if(substr_count($this->wsdl,'http://####################Sender_For_BOM.asmx')>0){
		   if($method=='qrySeat'){$data =$data['seatPlan'];}
		   if($method=='qryTicket'){$data =$data['showSeats'];}
		   if($method=='webLockSeat'){$data =$data['seatLock'];}
		}
		return $data;
	}
}
