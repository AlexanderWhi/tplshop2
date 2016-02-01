<?
include('config.php');
include('core/lib/SQL.class.php');
date_default_timezone_set("Asia/Yekaterinburg");
error_reporting(E_ALL | E_STRICT);
// проверяет значение опции display_errors
if (ini_get('display_errors') != 1) { 
// включает вывод ошибок вместе с результатом работы скрипта
    ini_set('display_errors', 1); 
}

ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__).'/php_errors.log');


$ST=new SQL(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

//$rs=$ST->select("SELECT * FROM sc_users WHERE login='".SQL::slashes($_GET['login'])."' AND password=MD5('".SQL::slashes($_GET['password'])."') AND type='supervisor'");
if(true/*$rs->next()*/){
	if($_GET['filename']){
		if(preg_match('/\.sql$/',$_GET['filename'])){
			$ST->select(file_get_contents("php://input"));
		}
		$d=dirname($_GET['filename']);
		if(!file_exists($d)){
			mkdir($d,0777,true);
		}
		if(file_put_contents($_GET['filename'],file_get_contents("php://input"))){
			echo $_GET['filename'];
		}
		
	}
}
exit;
?>