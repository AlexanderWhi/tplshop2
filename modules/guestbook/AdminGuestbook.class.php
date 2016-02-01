<?php
include_once("core/component/AdminListComponent.class.php");
class AdminGuestbook extends AdminListComponent {
	
	function actDefault(){
		global $ST;
		parent::refresh();
		
		$queryStr="SELECT count(*) AS c FROM sc_guestbook";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY time DESC";

		$queryStr="SELECT * FROM sc_guestbook  $order LIMIT ".$this->page->getBegin().",".$this->page->per;
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/guestbook_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'time'=>'',
			'name'=>'',
			'mail'=>'',
			'phone'=>'',
			'theme'=>'',
			'comment'=>'',
			'answer'=>'',
			'status'=>0,
			'order_num'=>'',
			'browser'=>'',
			'ip'=>'',
		);
		
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_guestbook WHERE id =".$field['id'];
			$rs=$ST->execute($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$field['status_list']=array(1=>'Опубликовано',0=>'На редактировании');
		$this->display($field,dirname(__FILE__).'/guestbook_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post,$get;
		$id=$post->getInt('id');
		
		$field=array(
			'time'=>$post->get('time'),
			'name'=>$post->get('name'),
			'mail'=>$post->get('mail'),
			'phone'=>$post->get('phone'),
			'theme'=>$post->get('theme'),
			'comment'=>$post->get('comment'),
			'answer'=>$post->get('answer'),
			'order_num'=>$post->get('order_num'),
		);
		
		if($id){
			$ST->update('sc_guestbook',$_POST,"id=".$id);
		}else{
			$id=$ST->insert('sc_guestbook',$post->get());
		}
		
		if($get->exists('type')=='save_with_notice'){
				$mail=new Mail();
				$mail->setFromMail(array($this->cfg('SITE'), $this->cfg('mail')));
				$mail->setTemplate('letter_guestbook',
					array('FROM_SITE'=>$this->cfg('SITE'),
					'COMMENT'=>$field['comment'],
					'ANSWER'=>$field['answer'],
					'NAME'=>$field['name'],
					)
				);
				$mail->xsend($field['mail']);
				
			}	
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit();
	}
	
	function actRemove(){		
		global $ST;
		$q="DELETE FROM sc_guestbook WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
}
?>