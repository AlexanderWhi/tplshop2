<?php
include_once("modules/contacts/Contacts.class.php");
class Feedback extends Contacts {
	
	function actDefault(){
		global $ST;
		$pg=new Page($this->cfg('PAGE_SIZE'));
		$data=array(
			'rs'=>array(),
			'pg'=>&$pg,
		);
		$condition="type='feedback' AND status=1";
		$queryStr="SELECT COUNT(*) as c FROM sc_feedback WHERE $condition";
		$rs=$ST->select($queryStr);
		if($rs->next()){
			$pg->all=$rs->getInt("c");
		}
		
		$rs=$ST->select("SELECT * FROM sc_feedback WHERE $condition ORDER BY id DESC LIMIT ".$pg->getBegin().",".$pg->per);
		while ($rs->next()) {
			$data['rs'][]=$rs->getRow();
			
		}
		$data['FB_CAN_SEND_MSG']=$this->cfg('FB_CAN_SEND_MSG')=='true';
		$this->setCommonCont();
		$this->display($data,dirname(__FILE__).'/feedback.tpl.php');
	}
	
	function actSend(){
		global $post;
		$error=array();
		
		if(!trim($post->get('name'))){
			$error['name']='������� ���';
		}
		
		if(!trim($post->get('comment'))){
			$error['comment']='������� ���������';
		}

		if(!$this->checkCapture($post->get('capture'),$post->get('type'))){
			$error['capture']="������� ���������� ���!";
		}

		
		if(empty($error)){
			$data=array(
				'mail'=>$post->get('mail'),
				'phone'=>$post->get('city'),
				'name'=>$post->get('name'),
				'comment'=>$post->get('comment'),
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'browser'=>$_SERVER['HTTP_USER_AGENT'],
				'status'=>0,
			);
			
//			if(isset($_FILES['file']) && $_FILES['file']['name'] && isImg($_FILES['file']['name']) && filesize($_FILES['file']['tmp_name'])<1024*1024*2){
//				$name=md5_file($_FILES['file']['tmp_name']).'.'.file_ext($_FILES['file']['name']);
//				$path='storage/img/'.$name;
//				move_uploaded_file($_FILES['file']['tmp_name'],$path);
//				$data['mail']="/{$path}";
//			}
			
			if($post->exists('type')){
				$data['type']=$post->get('type');
			}
			DB::insert('sc_feedback',$data);
			
			if($post->exists('url')){
				$data['fullurl']="{$_SERVER['HTTP_HOST']}".$post->get('url');
			}
//			$mail_contacts=$this->enum('mail_contacts',$this->getRegion());
			$mail_contacts='';
			
			$this->sendTemplateMail($this->cfg('MAIL_CONTACTS').'; '.$mail_contacts,'notice_feedback',$data);	
			
			$this->noticeICQ($this->cfg('ICQ'),'����� ��������� �� �����');
			echo printJSONP(array('msg'=>'OK'));exit;
		}else{
			echo printJSONP(array('err'=>$error));exit;
		}
		
	}
	
	function actFix(){
		global $post;
		
		$id=DB::insert('sc_feedback',array(
			'theme'=>'������ �� '.$_SERVER['HTTP_REFERER'],
			'comment'=>$post->get('text'),
			'type'=>'fix',
			'ip'=>$_SERVER['REMOTE_ADDR'],
			'browser'=>$_SERVER['HTTP_USER_AGENT'],
			'time'=>now(),
			));
			$html='<!DOCTYPE html>'."\n";
			$html.='<html>'.$post->get('html').'</html>';
			
			$file='/fb/report/'.str_pad($id,10,'0',STR_PAD_LEFT).'.html';
			
			file_put_contents(ROOT.$file,$html);
			DB::update('sc_feedback',array('file'=>$file),"id=$id");
			echo '���������� �� �������!';exit;
	}
	
	
	function actSendPublic(){
		global $post;
		$error=array();
		
		if(!trim($post->get('name'))){
			$error['name']='������� ���';
		}
		
		if(!trim($post->get('comment'))){
			$error['comment']='������� ���������';
		}

		if(!$this->checkCapture($post->get('capture'),$post->get('type'))){
			$error['capture']="������� ���������� ���!";
		}

		
		if(empty($error)){
			$data=array(
//				'mail'=>$post->get('mail'),
//				'phone'=>$post->get('city'),
				'name'=>$post->get('name'),
				'comment'=>$post->get('comment'),
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'browser'=>$_SERVER['HTTP_USER_AGENT'],
				'file'=>$post->get('url'),
				'status'=>0,
			);
			if($post->getInt('author')){
				$data['comment']='� �����'."\n".$data['comment'];
			}
			if(isset($_FILES['file']) && $_FILES['file']['name'] && isDoc($_FILES['file']['name']) && filesize($_FILES['file']['tmp_name'])<1024*1024*10){
				$name=md5_file($_FILES['file']['tmp_name']).'.'.file_ext($_FILES['file']['name']);
				$path='storage/files/'.$name;
				move_uploaded_file($_FILES['file']['tmp_name'],$path);
				$data['file']="/{$path}";
			}
			
			if($post->exists('type')){
				$data['type']=$post->get('type');
			}
			DB::insert('sc_feedback',$data);
			
			$mail_contacts='';
			$this->sendTemplateMail($this->cfg('MAIL_CONTACTS').'; '.$mail_contacts,'notice_feedback',$data);	
			
			$this->noticeICQ($this->cfg('ICQ'),'����� ��������� �� �����');
			echo printJSONP(array('msg'=>'OK'));exit;
		}else{
			echo printJSONP(array('err'=>$error));exit;
		}
		
	}
	
}
?>