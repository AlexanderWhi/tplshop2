<?php
class Cookie{
	static function set($name,$value){
		setcookie ($name,$_COOKIE[$name]=$value,time()+(60*60*24*180),"/","",0);
	}
	static function remove($name){
		setcookie($name,null,0,"/","",0);
	}
	static function get($name){
		return isset($_COOKIE[$name])?$_COOKIE[$name]:null;
	}
}
?>