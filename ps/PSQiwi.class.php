<?
include_once('PS.class.php');
class PSQiwi extends PS{
	public $t_id;
	public $password;
	public $to_account;
	public $txn_id;
	public $amount;
	public $comment;
	public $error_list=array();
	
	public $error=-1;
	
	
	function checkPassword($hash){
		return $hash==strtoupper(md5($this->txn_id.strtoupper(md5($this->password))));
	}
	
//	function PSQiwi($config=array(),$error_list=array()){
//		if($config){
//			foreach ($config as $name=>$value){
//				$this->$name=$value;
//			}
//		}
//		if($error_list){
//			$this->error_list=$error_list;
//		}
//	}
	
	private $url='http://ishop.qiwi.ru/xmlcp';
	
	
	private function sendData($data){
		$headers[]="Content-type: application/x-www-form-urlencoded";
		$headers[]="Content-Length: " . strlen($data) . "";
		$ch = curl_init();
		
	
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
//		curl_setopt($ch, CURLOPT_CAINFO, "/home/site.ru/data/signer/WebMoneyCA.crt");
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		
		return $result;
	}
	
	function createBill(){
		$request='<?xml version="1.0" encoding="windows-1251"?>
<request>
    <protocol-version>4.00</protocol-version>
    <request-type>30</request-type>
    <extra name="password">'.$this->password.'</extra>
    <terminal-id>'.$this->t_id.'</terminal-id>
    <extra name="comment">'.$this->comment.'</extra>
    <extra name="to-account">'.$this->to_account.'</extra>
    <extra name="amount">'.$this->amount.'</extra>
    <extra name="txn-id">'.$this->txn_id.'</extra>
    <extra name="ALARM_SMS">0</extra>
    <extra name="ACCEPT_CALL">0</extra>
    <extra name="ltime">60</extra>
</request>
';
		
//		$opts = array('http' =>
//			array(
//			'method' => 'POST',
//			'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
//			."Content-Length: " . strlen($request) . "\r\n",
//			'content' => $request
//			)
//		);
//		//Создаем stream context
//		$context = stream_context_create($opts);
//		//Отправляем запрос серверу. В качестве 3го параметра указываем созданный stream context
//		$result = file_get_contents($this->url, FALSE, $context);
		$result=$this->sendData($request);
		

		preg_match('|>([0-9]+)<|',$result,$res);
		if(isset($res[1])){
			$this->error=intval($res[1]);
		}
		return $result;
	}

	function getErrorByCode($code=null){
		if($code===null){
			$code=$this->error;
		}
		return isset($this->error_list[$code])?$this->error_list[$code]:'Неизвестная ошибка';
	}
	
	function getUrl(){}
	function setEmail($mail){}
	function setPhone($phone){}
	function setSumm($smm){}
	function setOrderNum($order_num){}
	function setDesc($desc){}
	public $disabled=true;
}
?>