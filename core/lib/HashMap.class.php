<?php
class HashMap {
	
	private $map=array();
	
	function getHtml($key){return isset($this->map[$key])?htmlspecialchars($this->map[$key]):null;}
	
	function isMail($key){return isset($this->map[$key])?!(empty($this->map[$key])||!check_mail($this->map[$key])):null;}
	
	
	function dConv($key){
		if(isset($this->map[$key])){
			$this->map[$key]=preg_replace('/^(\d{2})\.(\d{2})\.(\d{4})/','\3-\2-\1',$this->map[$key]);
		}
	}
	function upload($key,$to,$newName=null){
			
		$uploadfile = $to ."/". $_FILES[$key]['name'];
		if($newName!==null){
			$uploadfile = $to ."/". $newName;
		}

		return move_uploaded_file($_FILES[$key]['tmp_name'], $uploadfile);
	}
		
	function getArray($key){
		if( isset($this->map[$key])){
			
			if(is_array($this->map[$key])){
				return $this->map[$key];
			}else{
				return array($this->map[$key]);
			}
		}
		return array();
	}
	
	/**
	 * @param string $key
	 * @return integer
	 */
	function getString($key){
		if(isset($this->map[$key])){
			if(is_array($this->map[$key])){
				return '';
			}else{
				return strval($this->map[$key]);
			}
		}
		
	}
	/**
	 * @param string $key
	 * @return integer
	 */
	function getInt($key){return isset($this->map[$key])?intval($this->map[$key]):0;}
	/**
	 * @param string $key
	 * @return float
	 */
	function getFloat($key){return isset($this->map[$key])?floatval(str_replace(",",".",$this->map[$key])):0;}
	/**
	 * @param string $key
	 * @return string
	 */
	function getNum($key){return isset($this->map[$key])?preg_replace('/\D/','',$this->map[$key]):0;}
	
	function getDate($key){return isset($this->map[$key])?date('Y-m-d',strtotime($this->map[$key])):null;}
		
	
	function remove($key){
		$value=null;
		if($this->exists($key)){
			$value=$this->map[$key];
			unset($this->map[$key]);
		}
		return $value;
	}
	/**
	 * @param string $name
	 * @return boolean
	 */
	function exists($name){
		return isset($this->map[$name]);
	}
	function getURLEncString(){
		$output=array();
		if(count($this->map)>0){
			foreach ($this->map AS $key=>$value){
				$output[]=$key."=".urlencode($value);
			}
			return "?".join("&",$output);
		}else{return "";}
	}
	function set($key,$val=null){
		$this->map[$key]=$val;
	}
	function get($key=null){
		if($key){
			return isset($this->map[$key])?$this->map[$key]:null;
		}else{
			return $this->map;
		}
		
	}
	function __construct($array=null){
		if($array!==null)$this->map=$array;
	}
	/**
	 * @return string
	 */
	function getURLString(){
		$output=array();
		if(count($this->map)>0){
			foreach ($this->map AS $key=>$value){
				$output[]=$key."=".urlencode($value);
			}
			return "?".join("&",$output);
		}else{return "";}
	}

	function replace($name,$value){
		$newArgumentList=clone($this);
		$newArgumentList->setArgument($name,$value);
		return $newArgumentList;	
	}
	
	function getMap(){
		return $this->map;
	}
}
?>