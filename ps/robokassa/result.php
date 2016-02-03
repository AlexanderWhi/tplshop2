<?
$result=print_r($_GET,true)."\n";
$result.=print_r($_POST,true)."\n";
$result.=print_r($_SERVER,true)."\n";

file_put_contents("log.txt",date("Y-m-d H:i:s"),FILE_APPEND);
file_put_contents("log.txt",$result,FILE_APPEND);

require_once('../config.php');
require_once('../core/lib/SQL.class.php');
require_once('../core/lib/PSRobokassa.class.php');
require_once('../core/lib/Mail.class.php');

//Лезем в базу, смотрим алиасы
$ST=new SQL();

$ST->connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

$r=$ST->select("SELECT UPPER(name) AS name,value FROM sc_config");
while ($r->next()) {
	if($r->get('value')){
		$CONFIG[$r->get('name')]=$r->get('value');
	}
}


$rs=$ST->select("SELECT * FROM sc_pay_system WHERE name='robokassa'");
if($rs->next() && $_GET){
	$ps=new PSRobokassa(unserialize($rs->get('config')));
	
	$ps->OutSum=$_GET['OutSum'];
	$ps->InvId=$_GET['InvId'];	
	
	$params=array();
	foreach ($_GET as $key=>$val){
		if(substr($key,0,3)=='Shp'){
			$params[substr($key,3)]=$val;
		}
	}
	
	$ps->params=$params;
	if($ps->checkSignature($_GET['SignatureValue'])){//Данные прошли проверку
		//if($ps->params['Type']=='balance'){
			
			$rs=$ST->select("SELECT * FROM sc_income 
				WHERE 
					userid=".intval($ps->params['UserId'])."
					AND pay_id=".intval($ps->InvId)."
					AND type='robokassa'");
			if($rs->next()){
				//перевод уже был, всё ок
				echo 'OK'.$_GET['InvId'];
				exit;
			}else{
				if($ps->params['Type']=='balance'){//пополним баланс
					$ST->update('sc_users',array('balance=balance+'.floatval($ps->OutSum)),"u_id=".intval($ps->params['UserId']));
					$rs=$ST->execute("SELECT balance FROM sc_users WHERE u_id=".intval($ps->params['UserId']));
					if($rs->next()){
						$ST->insert('sc_income',array(
							'userid'=>intval($ps->params['UserId']),
							'sum'=>floatval($ps->OutSum),
							'balance'=>floatval($rs->getFloat('balance')),
							'type'=>'robokassa',
							'description'=>'Приход с робокассы',
							'pay_id'=>intval($ps->InvId),
							'pay_string'=>$_SERVER['QUERY_STRING']
						));
					}
				}
				//если указан номер заявки
				if(isset($ps->params['OrderId'])){
					
					$rs=$ST->select("SELECT * FROM sc_users WHERE u_id=".intval($ps->params['UserId']));
					if($rs->next()){
						$user=$rs->getRow();
						
//						$rs=$ST->execute("SELECT * FROM sc_cc_order WHERE userid=".intval($ps->params['UserId'])." AND id=".intval($ps->params['OrderId']));
						$rs=$ST->select("SELECT * FROM sc_shop_order WHERE userid=".intval($ps->params['UserId'])." AND id=".intval($ps->params['OrderId']));

						if($rs->next() && floatval($ps->OutSum)>=$rs->getInt('total_price')){

//							$ST->update('sc_users',array('balance=balance-'.$rs->getInt('summ')),"u_id=".intval($ps->params['UserId']));
							$ST->update('sc_shop_order',array('pay_time'=>date('Y-m-d H:i:s'),'pay_status'=>'ok'),'id='.intval($ps->params['OrderId']));
							
							//Оплата заказа
								$ST->insert('sc_income',array(
									'userid'=>intval($ps->params['UserId']),
									'sum'=>floatval($ps->OutSum),
									'type'=>'robokassa',
									'description'=>"Оплата заказа {$ps->params['OrderId']}",
									'pay_id'=>intval($ps->InvId),
									'pay_string'=>$_SERVER['QUERY_STRING']
								));
							
							
							
							/*Уведомление*/
							$mail=new Mail();
							$mail->sendTemplateMail($CONFIG['MAIL'],'notice_admin_user_buy',array('USER'=>$user['login'],'COUNT'=>$rs->getInt('count')));
							$mail->sendTemplateMail($user['mail'],'notice_user_buy',$rs->getRow());
						}
					}	
				}
	
			echo 'OK'.$_GET['InvId'];
		}
	}
}
?>