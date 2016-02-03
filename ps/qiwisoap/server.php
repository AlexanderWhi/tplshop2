<?php
include('../../config.php');
include('../../web-inf/class/lib/SQL.class.php');
include('../../web-inf/class/lib/PSQiwi.class.php');
global $ST;
$ST=new SQL();
$ST->connect(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);

 $s = new SoapServer('IShopClientWS.wsdl', array('classmap' => array('tns:updateBill' => 'Param', 'tns:updateBillResponse' => 'Response')));
 $s->setClass('Server');
 $s->handle();
 class Response {
  public $updateBillResult;
 }

 class Param {
  public $login;
  public $password;
  public $txn;      
  public $status;
 }

 class Server {
  function updateBill($param) {
  	global $ST;
  	
  	$rs=$ST->execute("SELECT * FROM sc_pay_system WHERE name='qiwi'");
		if($rs->next()){
			$config=@unserialize($rs->get('config'));
			$ps=new PSQiwi($config);
			$ps->txn_id=$param->txn;

			if($ps->t_id==$param->login && $ps->checkPassword($param->password)){
				if($param->status==60){//прошёл
					$ST->update('sc_shop_order',array('pay_status'=>'paid','pay_time=NOW()','pay_system'=>'qiwi'),"id='".$ps->txn_id."'");
				}elseif($param->status==150 || $param->status==161){
					$ST->update('sc_shop_order',array('pay_status'=>'canceled'),"id='".$ps->txn_id."'");
				}
				/**
				 * @todo Вероятно здесь должно быть уведомление
				 */
			}
		}
		file_put_contents('../../phpdump.txt',date('Y_m_d_H_i_s')."{$param->login}, {$param->password}, {$param->txn}, {$_SERVER['REMOTE_ADDR']}\r\n",FILE_APPEND);
	$temp = new Response();
	$temp->updateBillResult = -1;
	return $temp;
  }
 }
?>
