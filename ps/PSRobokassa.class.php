<?
include_once('PS.class.php');
class PSRobokassa extends PS{
	public $MrchLogin='';
	public $MrchPass1='';
	public $MrchPass2;
	public $OutSum='';
	public $InvId='';
	public $Desc='';
	public $Email='';
	
	public $test='';
	private $Culture='ru';
	public $IncCurrLabel='PCR';
	private $Encoding='windows-1251';
	
	
	private $psName='Робокасса';
	private $psDesc='Оплатить с помощью электронных денег РОБОКАССА';
	
	function getPsName(){		return $this->psName;	}
	function getPsDesc(){		return $this->psDesc;	}
	
	public $config=array(
		'MrchLogin','MrchPass1','MrchPass2','IncCurrLabel','test'
	);

	public $params=array();
	
	function checkSignature($hash){
		$str=$this->OutSum.":".$this->InvId.":".$this->MrchPass2;
//		$str=$this->MrchLogin.":".$this->OutSum.":".$this->InvId.":".$this->MrchPass1;
		$params=$this->params;
		if($params && ksort($params)){
			foreach ($params as $key=>$val){
				$str.=":Shp".$key."=".$val;
			}
		}
		return $hash==strtoupper(md5($str));
	}
	
	
	private $url='https://merchant.roboxchange.com/Index.aspx';
	private $url_test='http://test.robokassa.ru/Index.aspx';
	
	
	function getSignatureValue(){
		$str=$this->MrchLogin.":".$this->OutSum.":".$this->InvId.":".$this->MrchPass1;
		$params=$this->params;
		if($params && ksort($params)){
			foreach ($params as $key=>$val){
				$str.=":Shp".$key."=".$val;
			}
		}
		return strtoupper(md5($str));
//		$SignatureValue=strtoupper(md5("$MrchLogin:$OutSum:$InvId:$MrchPass1:Shp_demo=$Shp_demo:Shp_item=$Shp_item"));
	}
	
	function getAddr(){
		if($this->test){
			return $this->url_test;
		}
		return $this->url;
	}
	
	function getUrl(){

		$result=$this->getAddr().'?'.
			"MrchLogin=".$this->MrchLogin.'&'.
			"OutSum=".$this->OutSum.'&'.
			"InvId=".$this->InvId.'&'.
			"Desc=".$this->Desc.'&'.
			"Culture=".$this->Culture;
			
			if($this->Email){$result.="&Email=".$this->Email;}
			if($this->IncCurrLabel){$result.="&IncCurrLabel=".$this->IncCurrLabel;}
			
			$result.="&Encoding=".$this->Encoding.
			"&SignatureValue=".$this->getSignatureValue();
			$params=$this->params;
			if($params){
				foreach ($params as $key=>$val){
					$result.="&Shp".$key."=".$val;
				}
			}
		return $result;		
	}
	
	function setEmail($val){
		$this->Email=$val;
	}
	function setSumm($val){
		$this->OutSum=$val;
	}
	function setOrderNum($val){
		$this->params['OrderId']=$val;
	}
	function setType($val){
		$this->params['Type']=$val;
	}
	function setUserId($val){
		$this->params['UserId']=$val;
	}
	function setPhone($val){
		
	}
	function setDesc($val){
		$this->Desc=$val;
	}
	public $disabled=false;
}
?>