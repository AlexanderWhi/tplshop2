<?
ini_set('memory_limit', '64M');
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__).'/php_errors_imp.log');
include(dirname(__FILE__)."/../../config.php");
include(dirname(__FILE__)."/../../core/function.php");
file_put_contents(dirname(__FILE__).'/last_start.txt',date('Y-m-d H:i;s')."\n",FILE_APPEND);
?>
Импорт каталога
<?
set_time_limit(1000);

include(dirname(__FILE__)."/../../core/lib/SQL.class.php");
include_once dirname(__FILE__).'/../../modules/catsrv/catsrv.properties.php';
include(dirname(__FILE__)."/../../modules/catsrv/LibCatsrv.class.php");

chdir(ROOT);
$ST=new SQL(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

$r=$ST->select("SELECT UPPER(name) AS name,value FROM sc_config WHERE name LIKE 'SHOP_%'");
while ($r->next()) {
	if($r->get('value')){
		$CONFIG[$r->get('name')]=$r->get('value');
	}
}


//echo LibCatsrv::import1c();
//LibCatsrv::makeYandexYML();


 $res=LibCatsrv::import('import',11);
 echo print_r($res,true);
file_put_contents('import.log',"GET-".print_r($res,true),FILE_APPEND);//33fecdef-0baa-11e2-bc36-00241d3e9178/7858
?>