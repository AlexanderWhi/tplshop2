<?
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__).'/php_errors.log');

include("../../config.php");
include("../../core/function.php");

set_time_limit(1000);


//chdir(ROOT);

Cfg::add($CONFIG);
$r=DB::select("SELECT UPPER(name) AS name,value FROM sc_config");
while ($r->next()) {
	if($r->get('value')){
		$CONFIG[$r->get('name')]=$r->get('value');
	}
}
Cfg::add($CONFIG);

$last_time_fname='catalog_subscribe.txt';

$last_date=now();
if(file_exists($last_time_fname)){
	$last_date=file_get_contents($last_time_fname);
}
$id=strtotime($last_date);

$q="SELECT * FROM sc_shop_item i,sc_shop_proposal p,sc_users v WHERE p.itemid=i.id AND i.vendor=v.u_id AND p.create_time>'$last_date'";
$rs=DB::select($q)->toArray();
if(!$rs){
	exit;
}

ob_start();
include(dirname(__FILE__)."/catalog_subscribe.tpl.php");
$content= ob_get_contents(); 
ob_end_clean();

$type='catalog';

$mail=new Mail();
//	$mail->setFromMail($this->getConfig('mail'));
$mail->setFromMail(array(Cfg::get('SITE'), Cfg::get('mail')));		
$mail->setTemplate('letter_catalog',array('FROM_SITE'=>Cfg::get('SITE'),'CONTENT'=>$content));

$q="SELECT distinct mail,id FROM sc_subscribe WHERE type LIKE '%".$type."%' 
				AND NOT EXISTS(SELECT mailid FROM sc_news_sendlog WHERE id=mailid AND newsid=$id)";
$rs=DB::select($q);
$n=0;
while ($rs->next()) {
	if(check_mail($m=trim($rs->get('mail')))){
		$key='http://'.Cfg::get('SITE').'/cabinet/unsubscribe/?key='.md5($rs->get('mail').$type.'unsubscribe').'&type='.$type.'&mail='.$rs->get('mail');
		$key='<a href="'.$key.'">'.$key.'</a>';
		$mail->xsend($m,array('UNSUBSCRIBE'=>$key));
		DB::insert('sc_news_sendlog',array('mailid'=>$rs->get('id'),'newsid'=>$id));
		$n++;
	}else{
		DB::delete('sc_subscribe',"mail='".SQL::slashes($rs->get('mail'))."'");
	}
}
$rs=DB::select("SELECT MAX(create_time) AS m FROM sc_shop_proposal");
if($rs->next()){
	file_put_contents($last_time_fname,$rs->get('m'));
}
?>