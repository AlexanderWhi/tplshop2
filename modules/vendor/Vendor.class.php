<?php
include_once 'core/component/Component.class.php';
class Vendor extends Component {
	
	protected $mod_name='Фермерство';
	protected $mod_title='Фермерство';
	
		
	function actVendorList(){
		$cat=LibCatalog::getInstance();
		$cat->setCity($this->getCity());
//		$proposal_cond=" AND i.id=p.itemid AND p.begin_date<='".now()."' AND p.end_date>='".now()."'";
		$proposal_cond=$cat->proposalCond();
		
		$propposal_exists=" AND EXISTS (SELECT p.id FROM sc_shop_item i,sc_shop_proposal p WHERE
				 vendor=v.u_id 
				 $proposal_cond
				
			)";
		
		$queryStr="SELECT  v.u_id, v.company, v.address, v.city,cc.c,gc.ci,v.avat,v.img_format  FROM sc_users v
			LEFT JOIN(SELECT COUNT(p.id) AS c,vendor FROM sc_shop_item i,sc_shop_proposal p WHERE 1=1 $proposal_cond GROUP BY vendor) AS cc ON cc.vendor=v.u_id
			
			LEFT JOIN (SELECT vendor,COUNT(i.id) AS ci FROM sc_shop_item i
				WHERE 1=1 GROUP BY vendor) as gc ON gc.vendor=v.u_id
				
			WHERE hide=0 AND type='vendor' {$cat->cityCond()}
			ORDER BY v.company" ;
		
		$data['vendorList']=DB::select($queryStr)->toArray();
		$this->setCommonCont();
		$this->display($data,dirname(__FILE__).'/vendor_list.tpl.php');
	}
	
	function actDefault(){
		if($id=$this->getURIIntVal('vendor')){
			return $this->actView($id);	
		}
		return $this->actVendorList();
//		return $this->actSignup();
	}

	function getCat($vendor){
		
		$cat=LibCatalog::getInstance();
		$cat->noCity=true;
		
//		$q="SELECT i.*, v.company, v.address, v.city,p.*,s,c,r FROM 
//			sc_shop_item i
//			LEFT JOIN(SELECT COUNT(DISTINCT commentid) AS c,AVG(rating) AS r,itemid  FROM sc_comment,sc_comment_rait r WHERE commentid=id  AND TRIM(comment)<>'' AND status=1 AND type IN('','goods') GROUP BY itemid) AS rait ON rait.itemid=i.id
//			,sc_users v
//			,sc_shop_proposal p 
//			LEFT JOIN(SELECT SUM(oi.count) s,itemid FROM sc_shop_order_item oi,sc_shop_order o WHERE orderid=o.id AND pay_status=1 GROUP BY itemid) AS cc ON cc.itemid=p.id
//			WHERE i.vendor=v.u_id AND p.itemid=i.id AND i.vendor=$vendor  ORDER BY sort DESC";
		
		$q="{$cat->select()} AND i.vendor=$vendor  ORDER BY sort DESC";
		return DB::select($q)->toArray();
	}
	function getProposal($vendor){
		
		$cat=LibCatalog::getInstance();
		$cat->noCity=true;
		
		$q="{$cat->selectProposal()} AND i.vendor=$vendor  ORDER BY sort DESC";
		return DB::select($q)->toArray();
	}

	function actView($id){
		$rs=DB::select("SELECT * FROM sc_users u
				LEFT JOIN sc_users_vendor v ON v.vendor_id=u.u_id
			
			WHERE u_id=$id AND type='vendor' ");
			if($rs->next()){
				$data=$rs->getRow();
				
				$data['gallery_list']=DB::select("SELECT * FROM sc_gallery WHERE id IN (SELECT child FROM sc_relation WHERE type='vend_gal' AND parent=$id)")->toArray();
				
				$data['img_list']=array();
				if($data['images']){
					$data['img_list']=explode(',',$data['images']);
				}
				
				$data['goods']=$this->getCat($id);
				$data['proposal']=$this->getProposal($id);
				
				
			$comment=array();
			$data['comment']=&$comment;
			
			$cond=" itemid={$id}  AND type='vendor'";
			if(!$this->isAdmin()){
				$cond.=" AND status=1";	
			}
			
			$q="SELECT * FROM sc_comment WHERE $cond AND TRIM(comment)<>'' ORDER BY id LIMIT 20";
			$rs=DB::select($q);
			while ($rs->next()) {
				$comment[$rs->getInt('id')]=$rs->getRow();
			}
			
				
			$ip=$_SERVER['REMOTE_ADDR'];
			if($ip=='127.0.0.1'){
				$ip=time();
			}
			
			
			$can_comment=true;
			$data['can_comment']=&$can_comment;
			$data['itemid']=$id;
			$data['type']='vendor';
			$q="SELECT * FROM sc_comment WHERE $cond AND ip='{$ip}' AND DATE(time)='".date('Y-m-d')."'";
			$rs=DB::select($q);
			if ($rs->next()) {
				$can_comment=false;
			}
							
				$this->setPageTitle($data['company']);
//				$this->setCommonCont();
				$this->setContainer('common_container_3.tpl.php');
//				$this->tplContainer="core/tpl/www/main_container.tpl.php";
				$this->display($data,dirname(__FILE__).'/vendor.tpl.php');
			}
	}
	
	
	function actSignup(){
		
		global $post;
		$data=array(
			'mail'=>$post->get('login'),
			'password'=>$post->get('password'),
		);
		$this->mod_name='Регистрация';
		$this->mod_title='Регистрация';
		$this->setCommonCont();
		
		$this->display($data,dirname(__FILE__).'/signup.tpl.php');
	}		
	function actSign(){
		global $ST,$post;
		
		if(!$err=$this->checkAll($post)){
			$field=array(
				"login"=>$post->get('mail'),
				"name" =>$post->get('name'),
				"address" =>$post->get('address'),
				"city" =>$post->get('city'),
				"phone" =>$post->get('phone'),
				"mail" =>$post->get('mail'),
				"type" =>'vendor',
				"hide" =>1,
				);
				
		
			$password=substr(md5(time()),0,6);
			
			$field[]="password=MD5('".$password."')";
          	$id=DB::insert('sc_users',$field,'u_id');
          	
          	
          	$ext_data=array(
          		'vendor_id'=>$id,
          		'info'=>$post->get('info'),
          		'comment'=>$post->get('comment'),
          	);
          	DB::insert('sc_users_vendor',$ext_data,'u_id');
          	
          	my_session_start();
          	
          	$_SESSION['_USER']['u_id']=intval($id);
          	//уведомление о регистрации
	        $this->sendTemplateMail($field['mail'],'notice_new_user',
	        	array('FROM_SITE'=>FROM_SITE,'LOGIN'=>$field['login'],'PASSWORD'=>$password)
	        );          	
          	//уведомление о регистрации админу
			$this->sendTemplateMail($this->cfg('MAIL'),'notice_new_user4admin',
				array('FROM_SITE'=>FROM_SITE,'LOGIN'=>$field['login'],'name'=>$field['name'])
	        );
	        $this->noticeICQ($this->cfg('ICQ'),'Новый пользователь на сайте');
          	//Добавим в подписку
//          	$rs=$ST->select("SELECT * FROM sc_subscribe WHERE mail='{$this->field['mail']}'");
//          	if(!$rs->next()){
//          		$ST->insert('sc_subscribe',array('mail'=>$this->field['mail'],'type'=>'news send'));
//          	}
          	
			echo printJSON(array('status'=>'ok'));exit;
		}else{
			echo printJSON(array('error'=>$err));exit;
		}	
	}
	
	function checkAll($args){
		//Очистим ошибки
		$err=array();

		if($e=LibJoin::checkMail($args->get('mail'))){
			$err['mail']=$e;
		}
		
		if(!trim($args->get('name' ))){
			$err['name']="Введите ФИО";
		}
		if(!trim($args->get('company' ))){
			$err['company']="Введите организацию";
		}
		if(!trim($args->get('phone' ))){
			$err['phone']="Введите телефон";
		}
	

		if(!$this->checkCapture($args->get('capture'))){
			$err['capture']="Введите правильный код";
          
		}
		return $err;
	}
	function isVendor(){
		return $this->getUser('type')=='vendor';
	}
	function isAuth(){
		if($this->isAdmin() || $this->isVendor()){
			return true;
		}
		return false;
	}
	function actOrder(){
		if($this->isAuth()){
			$cond=' AND pay_status=1';
			if($this->isVendor()){
				$cond.=" AND i.vendor={$this->getUserId()}";
			}
			$q="SELECT *,i.id AS i_id,p.id AS p_id,inc.time,oi.id FROM sc_shop_order_item oi
			LEFT JOIN sc_income AS inc ON inc.pay_id=oi.id AND inc.type='orderitem'
			,sc_shop_order o,sc_shop_item i,sc_shop_proposal p WHERE
				p.itemid=i.id AND oi.itemid=p.id AND oi.orderid=o.id $cond";
			$rs=DB::select($q)->toArray();
			
			$data=array(
				'rs'=>$rs
			);
			
			$this->setCommonCont();
			$this->setPageTitle('Заказы');
			$this->display($data,dirname(__FILE__).'/order.tpl.php');
		}
	}
	function actConfirm(){
		global $get;
		if($this->isAuth()){
			$rs=DB::select("SELECT *,oi.id FROM sc_shop_order_item oi
				
			,sc_shop_order o,sc_users u WHERE u.u_id=o.userid AND o.id=oi.orderid AND oi.id={$get->getInt('id')}");
			if($rs->next()){
				$rs1=DB::select("SELECT * FROM sc_income WHERE type='orderitem' AND pay_id={$get->getInt('id')}");
				if(!$rs1->next()){
					$sum=round($rs->getFloat('price')*$rs->getFloat('count'),2);
					$ballance=$rs->getFloat('balance')-$sum;
					
					$d=array(
						'balance'=>$ballance,
						'sum'=>$sum,
						'type'=>'orderitem',
						'userid'=>$rs->get('userid'),
						'description'=>'Выдача товара',
						'pay_id'=>$get->getInt('id'),
					);
					DB::insert('sc_income',$d);
					
					$d=array(
						'balance'=>$ballance
					);
					DB::update('sc_users',$d,"u_id={$rs->getInt('userid')}");
					echo printJSON(array('msg'=>'Успешно'));exit;
				}
			}
		}
		echo printJSON(array('msg'=>'Ошибка'));exit;
	}
}
?>