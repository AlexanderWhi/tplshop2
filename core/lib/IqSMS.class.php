<?class IqSMS{
	
	public $login='';
	public $password='';
	
	
	function __construct($login,$password){
		$this->login=$login;
		$this->password=$password;
	}
	
//	const URL="http://api_login:api_password@gate.iqsms.ru/send/?phone=%2B71234567890&text=test";
	const URL="gate.iqsms.ru";
	
	function send($ph,$msg){
		//фиксим телефон
		$ph=preg_replace('/\D/','',$ph);//убираем не цыфры
		$ph=preg_replace('/^[7,8]/','',$ph);//убираем не цыфры
		$ph='+7'.$ph;
		
		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_PROXY, "10.61.38.132:8080");
//		curl_setopt($ch, CURLOPT_PROXYUSERPWD, "EKT\\leonov-aa:");

		$url="http://{$this->login}:{$this->password}@".IqSMS::URL."/send/?phone=".urlencode($ph)."&text=".urlencode(iconv('cp1251','utf-8',$msg));
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);

		curl_close($ch);
		
		return $result;
	}
}
//$sms=new IqSMS('','');
//$sms->send('8 904 983 16 60','Привееееееее');
?>