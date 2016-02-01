<?php
include_once("modules/contacts/Contacts.class.php");
class Comment extends Contacts {
	
	function actRemoveComment(){
		global $post,$ST;
		if($this->isAdmin()){
			$ST->update('sc_comment',array('status'=>0),"id=".$post->getInt('id'));
		}
		echo printJSON(array());exit;
	}
	function actShowComment(){
		global $post,$ST;
		if($this->isAdmin()){
			$ST->update('sc_comment',array('status'=>1),"id=".$post->getInt('id'));
		}
		echo printJSON(array());exit;
	}
	function actSendComment(){
		global $ST,$post;
		
		if($this->checkCapture($post->get('capture'),'gfb')){
			$d=array(
				'itemid'=>$post->getInt('itemid'),
				'comment'=>$post->get('comment'),
				'name'=>$post->get('name')?$post->get('name'):$this->getUser('name'),
				'mail'=>$post->get('mail')?$post->get('mail'):$this->getUser('mail'),
				'time'=>date('Y-m-d H:i:s'),
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'status'=>1,//по умолчанию одобрено
				'type'=>$post->get('type')?$post->get('type'):'goods',//по умолчанию товар
			);
			$id=$ST->insert("sc_comment",$d,'id');
			
			$d['fullurl']="{$_SERVER['HTTP_HOST']}".$this->getURI();
			
			
//			$mail_contacts=$this->enum('mail_contacts',$this->getRegion());
			$this->sendTemplateMail($this->cfg('MAIL_CONTACTS'),'notice_goods_comment',$d);
			
			$rait=$post->getArray('rait');
			foreach ($rait as $k=>$v) {
				$d=array(
					'commentid'=>(int)$id,
					'raitid'=>(int)$k,
					'rating'=>(int)$v,
				);
				
				$ST->insert('sc_comment_rait',$d,'raitid');
			}
			echo printJSON(array('res'=>'ok'));exit;
		}else{
			echo printJSON(array('err'=>'Введите правильный код'));exit;
		}		
	}
	
	function actDefault(){
		
		$page=new Page($this->cfg('PAGE_SIZE'));
				
		$condition="v.u_id=c.itemid AND c.type='vendor' AND c.status=1";

		$queryStr="SELECT COUNT(*) as c FROM sc_comment c,sc_users v  WHERE $condition";
		$rs=DB::select($queryStr);
		if($rs->next()){
			$page->all=$rs->getInt("c");
		}
		$data['pg']=$page;
		
		$data['rs']=DB::select("SELECT * FROM sc_comment c,sc_users v WHERE $condition ORDER BY time DESC")->toArray();
		$this->display($data,dirname(__FILE__)."/default.tpl.php");
	}
	
}
?>