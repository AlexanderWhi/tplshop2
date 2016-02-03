<?
require_once('../../config.php');
require_once('../../web-inf/class/lib/SQL.class.php');
require_once('../../web-inf/class/lib/PSComepay.class.php');

/* примеры запросов
https://service.provider.ru/comepay?operation=check&account=1234567890&sum=1
*/

$q="operation=check&account=210111&sum=2140&secret=123";
$q="operation=check&account=241205&sum=3270&secret=123";
$md5=strtoupper(md5($q));
$q="operation=check&account=241205&sum=3270&md5=$md5";
//
//$q="operation=payment&id_payment=1&account=210111&sum=2140&date=20110120165612&secret=123";
//$md5=strtoupper(md5($q));
//
/*
operation=check&account=210111&sum=2140&secret=123&md5=4FA37F773D18163AC8092F943C447D5E
operation=payment&id_payment=1&account=210111&sum=2140&date=20110120165612&md5=026707C3DC6D75530E30651775B25D86
*/

file_put_contents('log.txt',date("Y-m-d H:i:s")." {$_SERVER['REQUEST_URI']}\r\n",FILE_APPEND);
//exit;


$ST=new SQL();
$ST->connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

$rs=$ST->execute("SELECT * FROM sc_pay_system WHERE name='comepay'");
if($rs->next() && $_GET){
	$ps=new PSComepay(unserialize($rs->get('config')));
	$ps->operation=$_GET['operation'];
	$ps->account=$_GET['account'];//order_num
	$ps->sum=isset($_GET['sum'])?$_GET['sum']:0;
	if($ps->checkSignature($_GET)){//запрос корректный, идём дальше
			
			$rs=$ST->select("SELECT order_num,total_price,id,pay_summ FROM sc_shop_order WHERE order_num='{$ps->account}' AND total_price>pay_summ");
			if($rs->next()){
				if($ps->sum && $ps->sum<$rs->get('total_price') && false){//Условие не рассматривается
					$ps->error=542;//Недостаточно средств
					echo $ps->response();exit;
				}else{
					if($ps->operation=='check'){
						$ext_info['sum']=$rs->get('total_price')-$rs->get('pay_summ');
						echo $ps->response(array('ext_info'=>$ext_info,'sum'=>0));exit;
					}elseif ($ps->operation=='payment' && !empty($_GET['id_payment'])){
						$ps->id_payment=$_GET['id_payment'];
						$ps->date=$_GET['date'];
						//1. Смотрим, был ли платёж
						$rs1=$ST->select("SELECT * FROM sc_pay_income WHERE ext_id={$ps->id_payment}");
						if($rs1->next()){
							$ps->ext_id_payment=$rs1->get('id');
							$ps->date=date('YmdHis',strtotime($rs1->get('date')));
							$ps->error=516;//Дублирование платежа
							$ps->fatal=true;
							echo $ps->response();exit;
						}else{
							$ps->ext_id_payment=$ST->insert('sc_pay_income',
								array('ext_id'=>$ps->id_payment,
								'order_id'=>$rs->get('id'),
								'date'=>$date=date('Y-m-d H:i:s'),//preg_replace('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/','\1-\2-\3 \4:\5:\6',$ps->date),//@todo наша дата
								'sum'=>$ps->sum,
								'operator'=>'comepay',
							));
							$ST->update('sc_shop_order',array('pay_system'=>'comepay','pay_time'=>date('Y-m-d H:i:s'),'pay_status'=>'paid','pay_summ=pay_summ+'.$ps->sum),"id=".$rs->get('id'));
							$ps->date=date('YmdHis',strtotime($date));
							$ps->fatal=true;
							echo $ps->response();exit;//Ура! Заплатили!
						}
					}
				}
			}else{
				$ps->error=500;//Неверный номер абонента
				$ps->fatal=true;
				echo $ps->response();exit;
			}
	}else{
		$ps->error=508;//Неверный формат сообщения
		$ps->fatal=true;
		echo $ps->response();exit;
	}
	$ps->error=508;//Неверный формат сообщения
	$ps->fatal=true;
	echo $ps->response();exit;
		
	$ps->TransactionID=$_GET['TransactionID'];
	$ps->DateTime=$_GET['DateTime'];
	
	if(isset($_GET['ValidUntil'])){
		$ps->ValidUntil=$_GET['ValidUntil'];
	}
	if($ps->checkSignature($_GET) && isset($_GET['UserId']) &&  isset($_GET['OrderNum']) ){
		$ST->update('sc_shop_order',array('pay_status'=>'paid','pay_time=NOW()','pay_system'=>'payonline'),"id='".intval($_GET['OrderNum'])."'");//OrderNum доп параметр
	}
}
?>