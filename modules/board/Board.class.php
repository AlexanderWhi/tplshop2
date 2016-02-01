<?php
include_once('core/component/Component.class.php');
class Board extends Component {
	protected $mod_name='Объявления';
	protected $mod_title='Объявления';
	
	function actDefault(){
		
		
		$data=array(
			'category'=>0
		);		
		$page=new Page($this->cfg('PAGE_SIZE'));
				
		$condition="b.status=1 AND date_to>='".date('Y-m-d')."'";
		if($c=$this->getUriIntVal('catalog')){
			$condition.=" AND b.category=$c";
			$rs=DB::select("SELECT * FROM sc_shop_catalog WHERE id=$c");
			if($rs->next()){
				$this->setPageTitle($rs->get('name'));
			}
			$data['category']=$c;
		}
		if($this->getUriIntVal('my')){
			$condition.=" AND b.userid={$this->getUserId()}";
		}
		

		$queryStr="SELECT COUNT(*) as c FROM sc_shop_board b WHERE $condition";
		$rs=DB::select($queryStr);
		if($rs->next()){
			$page->all=$rs->getInt("c");
		}
		
		$order=" ORDER BY time DESC";
		$queryStr="SELECT b.*,u.name,c.name AS c_name,c.id AS c_id FROM sc_shop_board b
			LEFT JOIN sc_users u ON u.u_id=b.userid 
			LEFT JOIN sc_shop_catalog c ON c.id=b.category 
		WHERE $condition $order LIMIT ".$page->getBegin().",".$page->per;
		$rs=DB::select($queryStr)->toArray();
		
		$data['rs']=$rs;
		$data['pg']=$page;
		
		
		$data['catalog_tree']=LibCatalog::getInstance()->getCatalogTree();
		$data['date_to']=date('Y-m-d',time()+3600*24*30);
		$this->setCommonCont();

		$this->display($data,dirname(__FILE__).'/board.tpl.php');
		
	}
	
	function actSend(){
		global $post;
		$error=array();
		
		if(!$this->getUserId()){
			$error['userid']='Необходимо выпонить вход, или зарегистрироваться';
		}
		
		if(!trim($post->get('description'))){
			$error['description']='Введите описание';
		}
		if(!trim($post->get('text'))){
			$error['text']='Введите текст';
		}
		

		if(empty($error)){
			$data=array(
				'userid'=>$this->getUserId(),
				'text'=>$post->get('text'),
				'description'=>$post->get('description'),
				'category'=>$post->getInt('category'),
				'time'=>now(),
				'date_to'=>dte($post->get('date_to'),DTE_FORMAT_SQL),
				'status'=>0,
			);
			
			DB::insert('sc_shop_board',$data);	
	
			$this->sendTemplateMail($this->cfg('MAIL'),'notice_board',$data);	
			
//			$this->noticeICQ($this->cfg('ICQ'),'Новое объявление на сайте на сайте');
		}else{
			echo printJSON(array('err'=>$error));exit;
		}
		echo printJSON(array('msg'=>'ok'));exit;
	}
}
?>