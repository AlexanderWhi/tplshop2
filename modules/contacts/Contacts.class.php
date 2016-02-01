<?php
include_once("core/component/Component.class.php");
class Contacts extends Component {
	
	function actDefault(){
		global $ST,$get;
//		$contacts=$this->enum('contacts');
//		$all=array_keys($contacts);
//		$cur=$all[0];
//		if($t=$this->getURIIntVal('contacts')){
//			$cur=$t;
//		}
		$type=$this->getType();
		$data=array(
			'addrList'=>$ST->select("SELECT * FROM sc_contacts_points ORDER BY id desc")->toArray(),
			'uri'=>$get->get('url')?$get->get('url'):$this->getUri(),
			'text'=>$get->get('text')?$get->get('text'):'',
			'type'=>$type,
		);
				
		if($tpl=$this->getTpl($type.'.tpl.php')){
			$this->display($data,$tpl);
		}else{
			$this->display($data,$this->getTpl('contacts.tpl.php'));
		}
		
		
	}
	function getType(){
		return trim($this->getURI(),'/');
	}
	
	function actSend(){
		global $ST,$post;
		$error=array();
		
		if(!trim($post->get('name'))){
			$error['name']='Введите ФИО';
		}
		if($post->get('type')=='call'){
			if(!trim($post->get('phone'))){
				$error['phone']='Введите телефон';
			}			
			if(!trim($post->get('comment'))){
				$error['comment']='Введите вопрос';
			}
			
		}elseif(in_array($post->get('type'),array('ask'))){
			if(!$post->isMail('mail')){
				$error['mail']='Введите e-mail корректно';
			}
			
			if(!trim($post->get('comment'))){
				$error['comment']='Введите сообщение';
			}
			
//			if(!trim($post->get('phone'))){
//				$error['phone']='Введите телефон';
//			}
			
//			if(!trim($post->get('adress'))){
//				$error['adress']='Введите адрес';
//			}
			
		}elseif(in_array($post->get('type'),array('contacts'))){
			if(!$post->isMail('mail')){
				$error['mail']='Введите e-mail корректно';
			}
//			if(!trim($post->get('phone'))){
//				$error['phone']='Введите телефон';
//			}
						
//			if(!trim($post->get('adress'))){
//				$error['adress']='Введите адрес';
//			}
			
		}else{
			if(!trim($post->get('comment'))){
				$error['comment']='Введите сообщение';
			}
		}
		
		
		
		if(!$this->checkCapture($post->get('capture'),$post->get('type'))){
			$error['capture']="Введите правильный код!";
		}
		
		if(empty($error)){
			$data=array(
				'mail'=>$post->get('mail'),
				'phone'=>$post->get('phone'),
				'name'=>$post->get('name'),
				'comment'=>$post->get('comment'),
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'browser'=>$_SERVER['HTTP_USER_AGENT'],
				'status'=>1,
			);
			
			
			if($post->exists('type')){
				$data['type']=$post->get('type');
			}
			if($post->exists('url')){
				$data['url']=$post->get('url');
			}
			
			if($data['type']=='feedback'){
				$data['status']=0;
			}
			
			$type=$data['type'];		
			
			if($snd=$this->getTpl("{$type}.snd.php")){
				$data['comment']=$this->render($post->getMap(),$snd);
			}
			
			
			$ST->insert('sc_contacts',$data);
			
			if($post->exists('url')){
				$data['fullurl']="{$_SERVER['HTTP_HOST']}".$post->get('url');
			}

//			$mail_contacts=$this->enum('mail_contacts',$this->getRegion());
			$mail_contacts='';
			
			if($data['comment']){
				$data['comment']="<pre>{$data['comment']}</pre>";
			}
			if($post->get('type')=='call'){
				$this->sendTemplateMail($this->cfg('MAIL_CONTACTS').'; '.$mail_contacts,'notice_contacts_call',$data);
			}else{
				$this->sendTemplateMail($this->cfg('MAIL_CONTACTS').'; '.$mail_contacts,'notice_contacts',$data);	
			}
			
			
			$this->noticeICQ($this->cfg('ICQ'),'Новое сообщение на сайте');
		}
		echo printJSON(array('err'=>$error));exit;
	}
	
	function actMapSave(){
		$this->setPageTitle('Карта зон покрытия услуг');
		$data=array(
			'addrList'=>$this->enum('addr_list'),
		);
		$this->display($data,dirname(__FILE__).'/map.tpl.php');
	}
	function actMap(){
		global $ST;
		$this->setPageTitle('Карта зон покрытия услуг');
		$data=array(
			'addrList'=>$ST->select("SELECT * FROM sc_contacts_points")->toArray(),
		);
		$this->display($data,dirname(__FILE__).'/map.tpl.php');
	}
	function actAddr(){
		global $ST,$get;
		$data=array(
			'addrList'=>$ST->select("SELECT * FROM sc_contacts_points")->toArray(),
		);
		if($get->get('type')=='alone'){
			$this->display($data,dirname(__FILE__).'/addr.tpl.php');
		}else{
			$this->display($data,dirname(__FILE__).'/addr2.tpl.php');
		}
		
	}
}
?>