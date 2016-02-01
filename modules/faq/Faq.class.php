<?php
include_once('core/component/Component.class.php');
class Faq extends Component {
	
	protected $rs=array();
	
	protected $type='faq';
	
	function getType(){
		$type='faq';
		if(preg_match('|^/([^/]+)|',$this->getURI(),$res)){
			$type=$res[1];
		}
		return $type;
	}
	
	function actDefault(){
		global $ST;
		if(preg_match('|^/([^/]+)|',$this->getURI(),$res)){
			$this->type=$this->getType();
		}
				
		$page=new Page($this->cfg('PAGE_SIZE'));
				
		$condition="state='public' AND type='".$this->type."'";
		

		$queryStr="SELECT COUNT(*) as c FROM sc_faq WHERE $condition";
		$rs=$ST->select($queryStr);
		if($rs->next()){
			$page->all=$rs->getInt("c");
		}
		
		$order=" ORDER BY pos DESC,time";
		$queryStr="SELECT * FROM sc_faq WHERE $condition $order LIMIT ".$page->getBegin().",".$page->per;
		$rs=$ST->select($queryStr)->toArray();
		
		$data=array('rs'=>$rs,'pg'=>$page);
		$data['theme_list']=$this->enum("{$this->type}_theme");
		$this->setCommonCont();

		if(file_exists(dirname(__FILE__).'/'.$this->type.'.tpl.php')){
			$this->display($data,dirname(__FILE__).'/'.$this->type.'.tpl.php');
		}else{
			$this->display($data,dirname(__FILE__).'/faq.tpl.php');
		}
	}
	
	function actSend(){
		global $ST,$post;
		$error=array();
		
		if(!trim($post->get('name'))){
			$error['name']='Введите ФИО';
		}
		
		if(!trim($post->get('question'))){
			$error['question']='Введите вопрос';
		}
		

		
		if(!$this->checkCapture($post->get('capture'),$this->getType())){
			$error['capture']="Введите правильный код!";
		}
		

		if(empty($error)){
			$data=array(
				'mail'=>$post->get('mail'),
				'phone'=>$post->get('phone'),
				'name'=>$post->get('name'),
				'question'=>$post->get('question'),
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'browser'=>$_SERVER['HTTP_USER_AGENT'],
				'state'=>'edit',
			);
			
			$data['type']=$this->getType();
			if($post->exists('theme')){
				$data['theme']=$post->get('theme');
			}
			
			
			$ST->insert('sc_faq',$data);
			$mail_contacts=$this->enum('mail_contacts',$this->getRegion());
			
	
			$this->sendTemplateMail($this->cfg('MAIL_FAQ').'; '.$mail_contacts,'notice_faq',$data);	
			
			$this->noticeICQ($this->cfg('ICQ'),'Новое сообщение на сайте');
		}
		echo printJSON(array('err'=>$error));
		exit;
	}
}
?>