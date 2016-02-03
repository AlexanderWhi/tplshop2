<?
require_once('../config.php');
require_once('../core/lib/SQL.class.php');
require_once('../core/lib/PSPayonline.class.php');

file_put_contents('log.txt',date("Y-m-d H:i:s")." {$_SERVER['REQUEST_URI']}\r\n",FILE_APPEND);
//exit;


$ST=new SQL();
$ST->connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

$rs=$ST->select("SELECT * FROM sc_pay_system WHERE name='payonline'");
if($rs->next() && $_GET){
	$ps=new PSPayonline(unserialize($rs->get('config')));
	
	$ps->OrderId=$_GET['OrderId'];
	$ps->Amount=$_GET['Amount'];
	$ps->Currency=$_GET['Currency'];
	$ps->TransactionID=$_GET['TransactionID'];
	$ps->DateTime=$_GET['DateTime'];
	
	if(isset($_GET['ValidUntil'])){
		$ps->ValidUntil=$_GET['ValidUntil'];
	}
	if($ps->checkSignature($_GET['SecurityKey']) && isset($_GET['UserId']) &&  isset($_GET['OrderNum']) ){
		$ST->update('sc_shop_order',array('pay_status'=>'paid','pay_time'=>date('Y-m-d H:i:s'),'pay_system'=>'payonline'),"id='".intval($_GET['OrderNum'])."'");//OrderNum доп параметр
	}
}
?>