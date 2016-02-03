<?
include_once('PS.class.php');
class PSComepay extends PS{
	public $id_payment;
	public $ext_id_payment;
	public $date;
	public $secret;
	public $account;
	public $service;
	
	public $sum;
	public $operation;
	
	public $ext_info;
	
	public $error_list=array(
		0=>'Все ок',
		500=>'Неверный номер абонента',
		501=>'Недопустимый параметр',
		502=>'Платёж отклонён',
		503=>'Сервис недоступен',
		504=>'Абонент не найден',
		506=>'Неверная дата операции',
		508=>'Неверный формат сообщения',
		509=>'Превышено время ожидания',
		511=>'Недостаточно средств',
		513=>'Номер не принадлежит тестовой ёмкости',
		516=>'Дублирование платежа',
		517=>'Домашний оператор не принимает платеж',
		518=>'Лицевой счет абонента не доступен',
		534=>'Счёт абонента не активен или заблокирован',
		535=>'Прием платежа запрещен, обратитесь к оператору',
		541=>'Данная услуга не подключена',
		542=>'Сумма оплаты должна быть больше или равна сумме счета',
		546=>'Неверный тип услуги',
		599=>'Другая ошибка Поставщика',
		801=>'Реестр не загружен',
		802=>'Реестр обрабатывается',
		803=>'Реестр обработан с ошибкой',
		804=>'Реестр обработан, есть расхождения',
		805=>'Ошибка при загрузке списка расхождений',
	);
	public $error=0;
	public $fatal=false;
	
	function checkSignature($data){
		$md5=$data['md5'];
		unset($data['md5']);
		$data['secret']=$this->secret;
		return strtoupper($md5)==strtoupper(md5(http_build_query($data)));
	}
	
	function __construct($config=array()){
		if($config){
			foreach ($config as $name=>$value){
				$this->$name=$value;
			}
		}
	}
	
	function response($params=array()){
		foreach ($params as $key=>$val) {
			$this->$key=$val;
		}
		$res='<?xml version="1.0" encoding="utf-8" ?>';
		$res.='<response>';
		$res.='<operation>'.$this->operation.'</operation>';
		if($this->id_payment)$res.='<id_payment>'.$this->id_payment.'</id_payment>';
		if($this->ext_id_payment)$res.='<ext-id_payment>'.$this->ext_id_payment.'</ext-id_payment>';
		if($this->date)$res.='<date>'.$this->date.'</date>';
		$res.='<account>'.$this->account.'</account>';
		if($this->sum)$res.='<sum>'.$this->sum.'</sum>';
		if($this->ext_info){
			$res.='<ext-info>';
			foreach ($this->ext_info as $tag=>$val) {
				$res.='<'.$tag.'>'.$val.'</'.$tag.'>';
			}
			$res.='</ext-info>';
		}
		$res.='<result '.($this->fatal?'fatal="true"':'fatal="false"').'>'.$this->error.'</result>';
		$res.='</response>';
		return $res;//iconv('cp1251','utf8',$res);
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