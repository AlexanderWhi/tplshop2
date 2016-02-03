<?
include_once('PS.class.php');
class PSPaymaster extends PS{
	public $LMI_MERCHANT_ID='';//������������� ����� � ������� PayMaster.
	public $KEY='';
	public $LMI_PAYMENT_AMOUNT='';//����� �������, ������� �������� ������ �������� �� ����������. ����� ������ ���� ������ ����, ������� ����� ���������� ������. 
	public $LMI_PAYMENT_NO='';//� ���� ���� �������� ������ ����� �����
	public $LMI_PAYMENT_DESC='';//�������� ������ ��� ������. ����������� ���������.
	public $LMI_PAYMENT_DESC_BASE64='';//�������� ������ ��� ������. ����������� ���������.
	public $LMI_PAYER_PHONE_NUMBER='';//����� �������� ����������
	public $LMI_PAYER_EMAIL='';//E-mail ����������.
	
	public $LMI_SIM_MODE='0';
	
	public $LMI_CURRENCY='RUB';//������������� ������ �������.
	
	public $LMI_SUCCESS_URL='';//���� ������������, �� ��� �������� ������� ������������ ����� ��������� �� ���������� URL .
	public $LMI_FAILURE_URL='';//���� ������������, �� ��� ������ ������� ������������ ����� ��������� �� ���������� URL.
		
	public $config=array(
		'LMI_MERCHANT_ID','KEY','LMI_SIM_MODE'
	);

	public $params=array();
	
	function checkSignature($data){
		$str=$data['LMI_MERCHANT_ID'].";".$data['LMI_PAYMENT_NO'].";".$data['LMI_SYS_PAYMENT_ID'].";";
		$str.=$data['LMI_SYS_PAYMENT_DATE'].";".$data['LMI_PAYMENT_AMOUNT'].";".$data['LMI_CURRENCY'].";";
		$str.=$data['LMI_PAID_AMOUNT'].";".$data['LMI_PAID_CURRENCY'].";".$data['LMI_PAYMENT_SYSTEM'].";".$data['LMI_SIM_MODE'].";";
		if(isset($data['LMI_PAYMENT_STATUS'])){
			$str.=$data['LMI_PAYMENT_STATUS'].";";
		}
		$str.=$this->KEY;
		
		return $data['LMI_HASH']==base64_encode(md5($str, true));
	}
	
	
	private $url='https://paymaster.ru/Payment/Init';
	
	function getSignatureValue(){
		return '';
	}
	
	function getAddr(){
		return $this->url;
	}
	
	function getUrl(){

		$result=$this->getAddr().'?'.
			"LMI_MERCHANT_ID=".$this->LMI_MERCHANT_ID.'&'.
			"LMI_PAYMENT_AMOUNT=".$this->LMI_PAYMENT_AMOUNT.'&'.
			"LMI_PAYMENT_NO=".$this->LMI_PAYMENT_NO.'&'.
//			"LMI_PAYMENT_DESC=".$this->LMI_PAYMENT_DESC.'&'.
			"LMI_PAYMENT_DESC_BASE64=".base64_encode(iconv('cp1251','utf-8',$this->LMI_PAYMENT_DESC)).'&'.
			"LMI_CURRENCY=".$this->LMI_CURRENCY;
			
			if($this->LMI_PAYER_EMAIL){$result.="&LMI_PAYER_EMAIL=".$this->LMI_PAYER_EMAIL;}
			if($this->LMI_PAYER_PHONE_NUMBER){$result.="&LMI_PAYER_EMAIL=".$this->LMI_PAYER_PHONE_NUMBER;}
			
			$params=$this->params;
			if($params){
				foreach ($params as $key=>$val){
					$result.="&LMI_".strtoupper($key)."=".$val;
				}
			}
		return $result;		
	}
	
	function setEmail($val){
		$this->LMI_PAYER_EMAIL=$val;
	}
	function setPhone($val){
		$this->LMI_PAYER_PHONE_NUMBER=$val;
	}
	function setSumm($val){
		$this->LMI_PAYMENT_AMOUNT=number_format($val,2,'.','');
	}
	function setOrderNum($val){
		$this->LMI_PAYMENT_NO=$val;
	}
	function setDesc($val){
		$this->LMI_PAYMENT_DESC=$val;
	}
	function setType($val){
		$this->params['Type']=$val;
	}
	function setUserId($val){
		$this->params['UserId']=$val;
	}
	public $disabled=false;
}
?>