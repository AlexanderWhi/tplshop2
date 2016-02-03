<?
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__).'/php_errors.log');

include("../../config.php");
include("../../core/function.php");

set_time_limit(1000);

include("../../core/lib/SQL.class.php");
include_once '../../modules/catsrv/catsrv.properties.php';
include("../../modules/catsrv/LibCatsrv.class.php");

chdir(ROOT);
$ST=new SQL(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

$r=$ST->select("SELECT UPPER(name) AS name,value FROM sc_config WHERE name LIKE 'SHOP_SRV%'");
while ($r->next()) {
	if($r->get('value')){
		$CONFIG[$r->get('name')]=$r->get('value');
	}
}
echo LibCatsrv::export1c();
//LibCatsrv::makeYandexYML();
?>