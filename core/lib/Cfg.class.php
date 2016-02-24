<?php
class Cfg{
	private static $cfg=array();
	private function __construct(){
		
	}
	static function get($key){
		return isset(self::$cfg[$key])?self::$cfg[$key]:null;
	}
	static function add($cfg){
		self::$cfg+=$cfg;
	}
}