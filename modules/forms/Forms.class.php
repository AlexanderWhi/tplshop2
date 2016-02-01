<?php
include_once("core/component/Component.class.php");
class Forms extends Component {
	
	function actDefault(){
		
		$this->display(array('form'=>trim($this->mod_alias,'/')),dirname(__FILE__).'/forms.tpl.php');
	}
	
	function actSend(){
		global $ST,$post;
		$error=array();
				
//		if($this->checkCapture($post->get('capture'))){
//			$error['capture']="Введите правильный код!";
//		}
		$form=trim($this->mod_alias,'/');
		if(empty($error)){
			$data=array(
				'data'=>serialize($post->get()),
				'form'=>$form
			);
			
			$ST->insert('sc_forms',$data);

			
			$data['html']=$data['data'];
			unset($data['data']);
			$data['html']=$this->render(unserialize($data['html']),dirname(__FILE__).'/'.$data['form'].'_view.tpl.php');
			
			$this->sendTemplateMail($this->cfg('MAIL_CONTACTS'),'forms_'.$form,$data);
//			$this->noticeICQ($this->cfg('ICQ'),'Новое сообщение на сайте');
		}
		echo printJSON(array('err'=>$error));exit;
	}
}
?>