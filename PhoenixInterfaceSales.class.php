<?php
/**
 * 火凤凰卖品接口
 * @Author esyy  
 * @version 1.5
 * @var data 2016/5/30
 */
class PhoenixInterfaceSales
{
	
	private $wsdl='http://2##############################OS.asmx'; 
	private $pa = array(
			'userId' => '#####################',
			'userPass' => '###################'
	);
	/**
	 * 商品大类接口
	 * @param string $param
	 * @return unknown
	 */
	public function qryPosGoodsClass($param=false){
		$data = $this->getData('qryPosGoodsClass',$param);
		return $data;
	}
	/**
	 * 卖品商品查询
	 * @param string $param
	 * @return unknown
	 */
	public function qryPosGoods($param=false){
		$data = $this->getData('qryPosGoods',$param);
		return $data;
	}
	/**
	 * 会员卡订货价格查询
	 * @param string $param
	 * @return unknown
	 */
	public function qryPosHoldPrice($param=false){
		$data = $this->getData('qryPosHoldPrice',$param);
		return $data;
	}
	/**
	 * 2.2.4令牌接口2.2.4
	 * @param string $param
	 * @return unknown
	 */
	public function getToken($param=false){
		$data = $this->getData('getToken',$param,1);
		return $data;
	}
	/**
	 * 会员卡卖品出货2.2.1
	 * @param string $param
	 * @return unknown
	 */
	public function fixPosMemberCardHold($param=false){
		$data = $this->getData('fixPosMemberCardHold',$param,1);
		return $data;
	}
	/**
	 * 会员卡卖品套餐出货2.2.7
	 * @param string $param
	 * @return unknown
	 */
	public function fixPosMemberCardPackageHold($param=false){
		$data = $this->getData('fixPosMemberCardPackageHold',$param);
		return $data;
	}
	/**
	 * 非会员订货接口2.2.5
	 * @param string $param
	 * @return unknown
	 */
	public function fixPosHold($param=false){
		$data = $this->getData('fixPosHold',$param,1);
		return $data;
	}
	/**
	 * 非会员套餐2.2.8
	 * @param string $param
	 * @return unknown
	 */
	public function fixPosPackageHold($param=false){
		$data = $this->getData('fixPosPackageHold',$param);
		return $data;
	}
	/**
	 * 卖品套餐明细查询接口2.2.3
	 * @param string $param
	 * @return unknown
	 */
	public function qryPosPackageDetails($param=false){
		$data = $this->getData('qryPosPackageDetails',$param);
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
	private function getData($method,$param=false,$checkValue=0){
		//接口方法
		if($param){
			$param = array_merge($this->pa,$param);
		}else{
			$param =$this->pa;
		}
		//生成请求参数 cid=1&format=xml&pid=10000
		$query =http_build_query($param);
		if($checkValue){ $param['checkValue'] =md5(http_build_query($param)); }
		$query =http_build_query($param);
		//echo $this->wsdl.'/'.$method.'?'.$query;
		@file_put_contents(dirname(__FILE__).'/PhoenixInterfaceSales_'.date('Ymd').'.log',date("Y-m-d H:i:s")."url:".$this->wsdl.'/'.$method.'?'.$query."\n\r",FILE_APPEND);
		$res = $this->https_request($this->wsdl.'/'.$method.'?'.$query);
		$res = str_ireplace('GBK','utf-8',$res);
		if(substr_count($res, "interface.ykse.com")>0){
			preg_match('#<string xmlns=\"http://interface.ykse.com/Pos\">(.*)<\/string>#i', $res, $result);
			$res=htmlspecialchars_decode($result[1]);
		}
	    $data = json_decode(json_encode(simplexml_load_string($res)),true);
		@file_put_contents(dirname(__FILE__).'/PhoenixInterfaceSales_'.date('Ymd').'.log',date("Y-m-d H:i:s")."_method:".$method."\r\n".var_export($param, true)."\r\n".var_export($data, true)."\n\r" .$str."\n\r",FILE_APPEND);
		return $data;
	}
	private function https_request($url, $data = null)
	{
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)) {
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}
}
