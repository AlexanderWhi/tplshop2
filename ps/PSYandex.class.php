<?
include_once('PS.class.php');
class PSYandex extends PS{
	
	private $psName='Yandex';
	private $psDesc='Оплатить с помощью электронных денег Yandex';
	
	public $shopId='';//Идентификатор Контрагента, выдается Оператором.
	public $scid='';//Номер витрины Контрагента, выдается Оператором.
	public $KEY='';
	public $sum='';//Стоимость заказа. 
	public $shopArticleId='';//Идентификатор товара, выдается Оператором. Применяется, если Контрагент использует несколько платежных форм для разных товаров.
	public $orderNumber='';//Уникальный номер заказа в ИС Контрагента.
	
	public $cps_phone='';//Номер мобильного телефона плательщика.
	public $cps_email='';//Адрес электронной почты плательщика
	
	public $test='0';
	

	public $shopSuccessURL='';//Если присутствует, то при успешном платеже пользователь будет отправлен по указанному URL .
	public $shopFailURL='';//Если присутствует, то при отмене платежа пользователь будет отправлен по указанному URL.
		
	public $config=array(
		'shopId','scid','test'
	);

	public $params=array();
	
	function checkSignature($data){
		return true;
	}
	
	
	private $url='https://money.yandex.ru/eshop.xml';
	
	function getSignatureValue(){
		return '';
	}
	
	function getAddr(){
		return $this->url;
	}
	
	function getUrl(){
		$result=$this->getAddr().'?'.
			"shopId=".$this->shopId.'&'.
			"scid=".$this->scid.'&'.
			"customerNumber=".$this->cps_phone.'&'.
			"sum=".$this->sum.'&'.
			"orderNumber=".$this->orderNumber;
			
			if($this->cps_phone){$result.="&cps_phone=".$this->cps_phone;}
			if($this->cps_email){$result.="&cps_email=".$this->cps_email;}
			
			$result.="&shopSuccessURL=".urlencode("http://{$_SERVER['HTTP_HOST']}/catalog/success/?id={$this->orderNumber}");
			$result.="&shopFailURL=".urlencode("http://{$_SERVER['HTTP_HOST']}/catalog/success/?id={$this->orderNumber}");
			//https://farmcosmetica.ru/?action=PaymentSuccess
		//https://money.yandex.ru/eshop.xml?shopId=30131&scid=20930&customerNumber=89049831661&sum=368.30&orderNumber=28&cps_phone=89049831661&shopSuccessURL=http%3A%2F%2Ffarmcosmetica.ru%2Fcatalog%2Fsuccess%2F%3Fid%3D28&shopFailURL=http%3A%2F%2Ffarmcosmetica.ru%2Fcatalog%2Fsuccess%2F%3Fid%3D28	
		//https://money.yandex.ru/eshop.xml?shopId=30131&scid=20930&customerNumber=89049831661&sum=1&orderNumber=1&cps_phone=89049831661&shopSuccessURL=http%3A%2F%2Ffarmcosmetica.ru%2Fcatalog%2Fsuccess%2F%3Fid%3D28&shopFailURL=http%3A%2F%2Ffarmcosmetica.ru%2Fcatalog%2Fsuccess%2F%3Fid%3D28	
		return $result;		
	}
	function getForm(){
		return;
		$html="<form action='{$this->getAddr()}'>";
		$html.="<input type='hidden' name='shopId' value='{$this->shopId}'>";
		$html.="<input type='hidden' name='scid' value='{$this->scid}'>";
		$html.="<input type='hidden' name='customerNumber' value='{$this->cps_phone}'>";
//		$html.="<input type='hidden' name='sum' value='{$this->sum}'>";
		$html.="<input type='hidden' name='sum' value='1'>";
		$html.="<input type='hidden' name='orderNumber' value='t{$this->orderNumber}'>";
		if($this->cps_phone){$html.="<input type='hidden' name='cps_phone' value='{$this->cps_phone}'>";}
		if($this->cps_email){$html.="<input type='hidden' name='cps_email' value='{$this->cps_email}'>";}
//		$html.="<input type='hidden' name='shopSuccessURL' value='http://{$_SERVER['HTTP_HOST']}/catalog/success/?id={$this->orderNumber}'>";
//		$html.="<input type='hidden' name='shopSuccessURL' value='http://farmcosmetica.ru/catalog/success/?id={$this->orderNumber}'>";
//		$html.="<input type='hidden' name='shopFailURL' value='http://farmcosmetica.ru/catalog/success/?id={$this->orderNumber}'>";
		
		$html.="<input type='hidden' name='successURL' value='http://farmcosmetica.ru/catalog/success/?id={$this->orderNumber}'>";
		$html.="<input type='hidden' name='failURL' value='http://farmcosmetica.ru/catalog/success/?id={$this->orderNumber}'>";

//		$html.="<input type='hidden' name='shopFailURL' value='http://{$_SERVER['HTTP_HOST']}/catalog/success/?id={$this->orderNumber}'>";
		$html.="<input type='submit' value='Отплатить'>";
		$html.="</form>";
		
		return $html;
	}
	
	function setEmail($val){
		$this->cps_email=$val;
	}
	function setPhone($val){
		$this->cps_phone=$val;
	}
	function setSumm($val){
		$this->sum=number_format($val,2,'.','');
	}
	function setOrderNum($val){
		$this->orderNumber=$val;
	}
	function setDesc($val){
		
	}
}
?>