<?
$last_start=null;
if(file_exists(dirname(__FILE__).'/bonus_last_start.txt')){
	$last_start=file_get_contents(dirname(__FILE__).'/bonus_last_start.txt');
}
if(date('Y-m-d',strtotime($last_start))==date('Y-m-d')){
	exit;
}

ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__).'/php_errors_bonus.log');
include(dirname(__FILE__)."/../../config.php");
include(dirname(__FILE__)."/../../core/function.php");


set_time_limit(1000);

include_once(dirname(__FILE__)."/../../core/lib/SQL.class.php");
include_once(dirname(__FILE__)."/../../modules/shop/ShopBonus.class.php");

$ST=new SQL(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

$r=$ST->select("SELECT UPPER(name) AS name,value FROM sc_config ");
while ($r->next()) {
	if($r->get('value')){
		$CONFIG[$r->get('name')]=$r->get('value');
	}
}



$q="SELECT * FROM sc_shop_order WHERE userid>0 AND order_status=3 AND stop_time BETWEEN '".date('Y-m-d',strtotime('-1 day'))."' AND '".date('Y-m-d')."'";
$rs=$ST->select($q);
while ($rs->next()) {
	$percent=ShopBonus::getBonusPercent($rs->getInt('userid'));
	
	$bonus=round($rs->getInt('price')/20)*20/100*$percent*10;
	
	$rs1=$ST->select("SELECT * FROM sc_users WHERE u_id={$rs->getInt('userid')}");
	if($rs1->next()){
		$inc=array(
			'userid'=>$rs->getInt('userid'),
			'sum'=>$bonus,
			'balance'=>$bonus+$rs1->getInt('bonus'),
			'type'=>'bonus',
			'description'=>'Начисление бонуса',
			'time'=>date('Y-m-d H:i:s'),
		
		);
		$ST->insert('sc_income',$inc);
		$ST->update('sc_users',array('bonus'=>$bonus+$rs1->getInt('bonus')),"u_id={$rs->getInt('userid')}");
	}
	
}

file_put_contents(dirname(__FILE__).'/bonus_last_start_log.txt',date('Y-m-d H:i:s')."\n",FILE_APPEND);
file_put_contents(dirname(__FILE__).'/bonus_last_start.txt',date('Y-m-d H:i:s'));
?>