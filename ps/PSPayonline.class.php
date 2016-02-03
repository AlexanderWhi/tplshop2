<?
class PSPayonline{
	
	private $psName='PAYONLINE';
	private $psDesc='Оплатить с помощью электронных денег PAYONLINE';
	
	public $test='';
	
	function getPsName(){		return $this->psName;	}
	function getPsDesc(){		return $this->psDesc;	}
	
	public $config=array(
		'MerchantId','PrivateSecurityKey','ReturnUrl','Currency','test'
	);
	
	public $MerchantId='';//Идентификатор, который вы получаете при активации. Обязательный параметр.
	
	public $SecurityKey='';//Открытый ключ, подтверждающий целостность параметров запроса. Обязательный параметр.
	public $PrivateSecurityKey='';
	
	public $Amount='';//Конечная сумма заказа. Обязательный параметр.
	
	public $OrderId='';//Идентификатор заказа в системе ТСП. Обязательный параметр.
	
//	public $Desc='';
//	public $Culture='ru';
	public $Currency='RUB';//Валюта заказа. Обязательный параметр.
//	public $Encoding='windows-1251';
	
	public $TransactionID='';
	public $DateTime='';
	
//	public $ReturnUrl='';//URL страницы возврата
	
	
	public $ValidUntil='';//Срок «оплатить до», временная зона UTC (GMT+0). Необязательный параметр.
	public $ReturnUrl='';//Абсолютный URL адрес, на который будет отправлен плательщик после завершения платежа. Адрес может включать в себя любые параметры. Необязательный параметр.

	public $params=array();
	
	function checkSignature($hash){
		$str="DateTime={$this->DateTime}&TransactionID={$this->TransactionID}&OrderId={$this->OrderId}&Amount={$this->Amount}&Currency={$this->Currency}&PrivateSecurityKey={$this->PrivateSecurityKey}";
//		$str="MerchantId={$this->MerchantId}&OrderId={$this->OrderId}&Amount={$this->Amount}&Currency={$this->Currency}".($this->ValidUntil?"&ValidUntil={$this->ValidUntil}":'')."&PrivateSecurityKey={$this->PrivateSecurityKey}";
		
//		md5("MerchantId=12345&OrderId=56789&Amount=9.99&Currency=USD&ValidUntil=2010-01-29 16:10:00&PrivateSecurityKey=3844908d-4c2a-42e1-9be0-91bb5d068d22");
		return $hash==md5($str);
	}
	
	
	private $url='https://secure.payonlinesystem.com/ru/payment/select/';
	
	
	function getSignatureValue(){
		$str="MerchantId={$this->MerchantId}&OrderId={$this->OrderId}&Amount={$this->Amount}&Currency={$this->Currency}&PrivateSecurityKey={$this->PrivateSecurityKey}".$this->OutSum.":".$this->InvId.":".$this->MrchPass1;
		return md5($str);
	}
	
	function getUrl(){

		$baseQuery = "MerchantId={$this->MerchantId}&OrderId={$this->OrderId}&Amount=".number_format($this->Amount, 2, '.','')."&Currency={$this->Currency}";

		$queryWithSecurityKey = "{$baseQuery}&PrivateSecurityKey={$this->PrivateSecurityKey}";

		$hash = md5($queryWithSecurityKey);

		$clientQuery = $baseQuery."&SecurityKey=".$hash.($this->ReturnUrl?"&ReturnUrl=".urlencode("http://".$_SERVER['HTTP_HOST'].$this->ReturnUrl):'');

		foreach ($this->params as $key=>$val){
			$clientQuery.="&{$key}={$val}";
		}
		
		$paymentFormAddress = "{$this->url}?".$clientQuery;
		return $paymentFormAddress;		
	}
	
	function getForm($params=array()){
		foreach ($params as $key=>$val) {
			$this->$key=$val;
		}
		//w.qiwi.ru/setInetBill.do?from=6334&to=9161234567&summ=2.01&com=test 
		$out='<div>';
		$out.='<h6>Оплатить с помощью PAYONLINE</h6>';
		
		$out.='<img alt="" src="http://www.payonlinesystem.ru/rel/img/logos/mc_brand_065_gif.gif" />
		<img alt="" src="http://www.payonlinesystem.ru/rel/img/logos/visa1_103x65_a.gif" /><br>
        К оплате на нашем сайте принимаются все типы банковских карт Visa и MasterCard. 
        Безопасность Ваших платежей обеспечивает процессинговая компания PayOnline System, 
        прошедшая международную сертификацию. 
        Работа с платежной формой PayOnlineSystem производится по шифрованному каналу передачи данных, 
        с использованием шифрования SSL.<br />';
		$out.='<a href="'.$this->getUrl().'"><strong>Оплатить</strong></a>';
		$out.='</div>';
		return $out;
	}
	public $disabled=true;
}
?>