<?
abstract class PS{
	
	private $psName='PayMaster';
	private $psDesc='Оплатить с помощью электронных денег PayMaster';
	
	function getPsName(){		return $this->psName;	}
	function getPsDesc(){		return $this->psDesc;	}
	
	public $config=array();

	function __construct($config=array()){
		if($config){
			if(!is_array($config)){
				$config=unserialize($config);
			}
			foreach ($config as $name=>$value){
				$this->$name=$value;
			}
		}
	}
	
	abstract function getUrl();
	
	abstract function setEmail($val);
	abstract function setPhone($val);
	
	abstract function setSumm($val);
	abstract function setOrderNum($val);
	abstract function setDesc($val);
	
	private static $ps; 
	/**
	 * @return PS
	 */
	static function getInstance(){
		if(empty(self::$ps)){
			if(!$paysystem=Cfg::get('PAYSYSTEM')){
				$paysystem='robokassa';
			}
			$rs=DB::select("SELECT * FROM sc_pay_system WHERE name='$paysystem'");
			if($rs->next()){
				$class_name="PS".ucfirst($paysystem);
				include_once("ps/{$class_name}.class.php");
				self::$ps=new $class_name($rs->get('config'));
			}
		}
		return self::$ps;
	}
	/**
	 * @return PS
	 */
	static function setData($order,$summ,$phone=null,$mail=null,$desc=null){
		self::getInstance()->setOrderNum($order);
		self::getInstance()->setSumm($summ);
		self::getInstance()->setEmail($mail);
		self::getInstance()->setPhone($phone);
		self::getInstance()->setDesc($desc);
		return self::getInstance();
	}
}
?>