<?
//http://www.buketmigom.ru/paymaster/result.php
//http://www.buketmigom.ru/catalog/success/


/*
Array
(
    [LMI_MERCHANT_ID] => c90f4342-4ce0-4718-b416-6fe47af9673b
    [LMI_PAYMENT_SYSTEM] => 3
    [LMI_CURRENCY] => RUB
    [LMI_PAYMENT_AMOUNT] => 1290.00
    [LMI_PAYMENT_NO] => 20
    [LMI_PAYMENT_DESC] => РџРѕРєСѓРїРєР° С‚РѕРІР°СЂР°
    [LMI_SYS_PAYMENT_DATE] => 2014-08-29T14:25:43
    [LMI_SYS_PAYMENT_ID] => 19847001
    [LMI_PAID_AMOUNT] => 1290.00
    [LMI_PAID_CURRENCY] => RUB
    [LMI_SIM_MODE] => 0
    [LMI_PAYER_IDENTIFIER] => 351775724336
    [LMI_PAYMENT_METHOD] => Test
    [LMI_HASH] => 8X+kCfAYHsRD67ZojcBgtQ==
)

*/

//$_POST=array(
//'LMI_MERCHANT_ID' => 'c90f4342-4ce0-4718-b416-6fe47af9673b',
//    'LMI_PAYMENT_SYSTEM' => '3',
//    'LMI_CURRENCY' => 'RUB',
//    'LMI_PAYMENT_AMOUNT' => '1290.00',
//    'LMI_PAYMENT_NO' => '20',
//    'LMI_PAYMENT_DESC' => 'РџРѕРєСѓРїРєР° С‚РѕРІР°СЂР°',
//    'LMI_SYS_PAYMENT_DATE' => '2014-08-29T14:25:43',
//    'LMI_SYS_PAYMENT_ID' => '19847001',
//    'LMI_PAID_AMOUNT' => '1290.00',
//    'LMI_PAID_CURRENCY' => 'RUB',
//    'LMI_SIM_MODE' => 0,
//    'LMI_PAYER_IDENTIFIER' => '351775724336',
//    'LMI_PAYMENT_METHOD' => 'Test',
//    'LMI_HASH' => '8X+kCfAYHsRD67ZojcBgtQ==',
//
//);


//URL-адрес	Протокол	Метод	Результат	Тип	Получено	Затрачено	Инициатор	Ожидание??	Начало??	Запрос??	Ответ??	Чтение из кэша??	Интервал??
//  /Payment/Init?LMI_MERCHANT_ID=c90f4342-4ce0-4718-b416-6fe47af9673b&LMI_PAYMENT_AMOUNT=1755.00&LMI_PAYMENT_NO=14&LMI_PAYMENT_DESC=Покупка товара&LMI_PAYMENT_DESC_BASE64=0J/QvtC60YPQv9C60LAg0YLQvtCy0LDRgNCw&LMI_CURRENCY=RUB	HTTPS	GET	302	text/html	415 Б	0.67 с	навигация	0	0	671	0	0	1217

$result=print_r($_GET,true)."\n";
$result.=print_r($_POST,true)."\n";
//$result.=print_r($_SERVER,true)."\n";

file_put_contents("log.txt",date("Y-m-d H:i:s"),FILE_APPEND);
file_put_contents("log.txt",$result,FILE_APPEND);

//echo "ok";exit;


require_once('../config.php');
require_once('../core/lib/SQL.class.php');
require_once('../core/lib/PSPaymaster.class.php');
require_once('../core/lib/Mail.class.php');

//Лезем в базу, смотрим алиасы
$ST=new SQL(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);


$r=$ST->select("SELECT UPPER(name) AS name,value FROM sc_config");
while ($r->next()) {
	if($r->get('value')){
		$CONFIG[$r->get('name')]=$r->get('value');
	}
}


$rs=$ST->select("SELECT * FROM sc_pay_system WHERE name='paymaster'");
if($rs->next() && $_GET){
	$ps=new PSPaymaster(unserialize($rs->get('config')));
	
	if($ps->checkSignature($_POST)){//Данные прошли проверку
		
			
			$rs=$ST->select("SELECT * FROM sc_income 
				WHERE 
					pay_id=".intval($_POST['LMI_PAYMENT_NO'])."
					AND type='paymaster'");
			if($rs->next()){
				//перевод уже был, всё ок
				echo 'OK'.$_POST['LMI_PAYMENT_NO'];
				exit;
			}else{
				
				//если указан номер заявки
				if(isset($_POST['LMI_PAYMENT_NO'])){
					
					$rs=$ST->select("SELECT * FROM sc_shop_order WHERE id=".intval($_POST['LMI_PAYMENT_NO']));
					if($rs->next()){
						if(floatval($_POST['LMI_PAYMENT_AMOUNT'])==$rs->getFloat('total_price')){
							$ST->update('sc_shop_order',array('pay_time'=>date('Y-m-d H:i:s'),'pay_status'=>'1'),'id='.intval($_POST['LMI_PAYMENT_NO']));
							
							//Оплата заказа
								$ST->insert('sc_income',array(
									'userid'=>$rs->getInt('userid'),
									'sum'=>floatval($_POST['LMI_PAYMENT_AMOUNT']),
									'type'=>'paymaster',
									'description'=>"Оплата заказа {$_POST['LMI_PAYMENT_NO']}",
									'pay_id'=>intval($_POST['LMI_PAYMENT_NO']),
									'pay_string'=>http_build_query($_POST),
								));
							
							
							
							/*Уведомление*/
							$mail=new Mail();
							$mail->sendTemplateMail($CONFIG['MAIL'],'notice_admin_user_buy',$rs->getRow());
//							$mail->sendTemplateMail($user['mail'],'notice_user_buy',$rs->getRow());
						}
					}	
				}
	
			echo 'OK'.$_POST['LMI_PAYMENT_NO'];
		}
	}
}
?>