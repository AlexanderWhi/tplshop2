<?php
include_once("core/component/Component.class.php");
class Guestbook extends Component {
	
	function actDefault(){
		global $ST;
		
		$pg=new Page(10);
		
		$data=array();
		$rs=$ST->select("SELECT field_value,value_desc FROM sc_enum WHERE field_name='fb_score' ORDER BY position DESC");
		while ($rs->next()) {
			$data['score'][]=$rs->getRow();
		}
		
		$rs=$ST->select("SELECT COUNT(*) AS c FROM sc_guestbook WHERE status=1");
		while ($rs->next()) {
			$pg->all=$rs->getInt('c');
		}
		$data['rs']=$ST->select("SELECT * FROM sc_guestbook WHERE status=1 ORDER BY time LIMIT {$pg->getBegin()},{$pg->per}")->toArray();
		
		$data['pg']=$pg;
		
		$this->display($data,dirname(__FILE__).'/guestbook.tpl.php');
	}
	
	function actSend(){
		global $ST,$post;
		$error=array();
		
		if(!trim($post->get('name'))){
			$error['name']='Введите ФИО';
		}
		if(!trim($post->get('comment'))){
			$error['comment']='Введите сообщение';
		}
		
		if(defined('IMG_SECURITY') && (!isset($_SESSION[IMG_SECURITY]) || ($post->get('capture')!=$_SESSION[IMG_SECURITY]))){
			$error['capture']="Введите правильный код!";
		}
		if(empty($error)){
			$data=array(
				'mail'=>$post->get('mail'),
				'phone'=>$post->get('phone'),
				'name'=>$post->get('name'),
				'theme'=>$post->get('theme'),
				'comment'=>$post->get('comment'),
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'browser'=>$_SERVER['HTTP_USER_AGENT'],
				
		
				'order_num'=>$post->get('order_num'),
			
				'score'=>$post->get('score'),
			);
			$ST->insert('sc_guestbook',$data);

			$this->sendTemplateMail($this->cfg('mail'),'notice_guestbook',$data);
			$this->noticeICQ($this->cfg('ICQ'),'Новое сообщение на сайте в разделе '.$this->mod_name);
		}
		echo printJSON(array('err'=>$error));
		exit;
	}
}
?>