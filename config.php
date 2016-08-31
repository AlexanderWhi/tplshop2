<?php
date_default_timezone_set("Asia/Yekaterinburg");
ini_set ("default_charset","windows-1251");//or set php_value default_charset UTF-8 in .htaccess
//setlocale(LC_ALL, "ru_RU.CP1251");
//setlocale(LC_NUMERIC, "ru_RU.CP1251");
//ini_set ('upload_max_filesize',$CONFIG['UPLOAD_MAX_FILESIZE']);
error_reporting(E_ALL | E_STRICT);
//error_reporting(E_ALL & ~E_NOTICE);
// проверяет значение опции display_errors
if (ini_get('display_errors') != 1) { 
// включает вывод ошибок вместе с результатом работы скрипта
    ini_set('display_errors', 1); 
}
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__).'/log/php_errors.log');
//Получение пути директории с приложениями
define('COOKIE_EXP',3600*24*180+time());
define("ROOT",dirname(__FILE__));
//Определим константы для соединения к базе данных 
define("DB_HOST","localhost");
define("DB_LOGIN","root");
define("DB_PASSWORD","");
define("DB_BASE","tpl_shop");


if(empty($_SERVER['HTTP_HOST'])){
	$_SERVER['HTTP_HOST']='stomat-shop.loc';
}

define("HTTP_HOST",@$_SERVER['HTTP_HOST']);
define("FROM_MAIL","admin@".HTTP_HOST);
define("FROM_SITE",HTTP_HOST);
define('IMG_SECURITY','_IMG_SECURITY');
global $CONFIG,$CORE;
$CONFIG=array(
	'COPYRIGHTS'=>'© Copyrights co-in.ru 2010-2015 г.',
	'TITLE'=>' - ',
	'THEME'=>'standart',
	'DB'=>array(
		'host'=>DB_HOST,
		'login'=>DB_LOGIN,
		'password'=>DB_PASSWORD,
		'base'=>DB_BASE,
		'driver'=>'mysql',
	),
);

$CONFIG['SITE']=HTTP_HOST;
$CONFIG['MAIL']='admin@'.HTTP_HOST;
$CONFIG['ROBOT_MAIL']='robot@'.HTTP_HOST;
$CONFIG['PAGE_SIZE']=30;
$CONFIG['PAGE_SIZE_SELECT']='30,50,100,500';
$CONFIG['UPLOAD_MAX_FILESIZE']=1024*1024*10;
$CONFIG['CATALOG_PATH']='/storage/catalog';
$CONFIG['PARTNERS_PATH']='/storage/partner';
$CONFIG['NEWS_IMAGE_PATH']='/storage/news';
$CONFIG['ADVERTISING_PATH']='/storage/adv';
$CONFIG['GALLERY_PATH']='/storage/gallery';
$CONFIG['AVATAR_PATH']='/storage/avatar';
$CONFIG['NO_IMG']='/storage/img/no_img.png';
$CONFIG['SHOP_IS_MULTY_CAT']=0;
$CONFIG['SITE_LOG']=1;
$CONFIG['VERSION']='3.80';
$CONFIG['ADMIN_COPYRIGHTS']="Управление сайтом v.{$CONFIG['VERSION']} bisnes © 2010-2015 г.";

global $_CURLOPT;
//$_CURLOPT['PROXY']="";
//$_CURLOPT['PROXYUSERPWD']="";

$CORE=array('config','paysystem','modules','mail_template','files','users','content','mailtpl','adv','enum','query');
$local = ROOT . '/local-config.php';

if (file_exists($local)) {
    require $local;
}