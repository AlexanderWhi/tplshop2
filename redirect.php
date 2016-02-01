<?
if ($_GET['url'] && $_GET['r'] && $_GET['k'] && $_GET['p']){	
	include_once("config.php");
	include('session.php');
	include_once("core/function.php");
	$ST=new SQL();
	$ST->connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);	
	if($_GET['k']==md5(session_id().$_GET['r'].$_GET['url'])){
		$ST->update('sc_advertising',array('click=click+1'),"id=".intval($_GET['p']));
	}
	header('Location: '.$_GET['url']);
}
?>