<?php
include_once 'core/component/AdminComponent.class.php';
class Query extends AdminComponent {
	protected $mod_name='Выполнить запрос';
	protected $mod_title='Выполнить запрос';
	
	function actDefault(){		
		global $ST;
		
		$this->display(array(),dirname(__FILE__).'/query.tpl.php');
	}

	function actExec(){
		global $ST,$post;
		
		$q=str_replace('`','',$post->get('query'));
		
		$res=$ST->exec($q);
		
		echo printJSON(array('msg'=>print_r($res,true)));exit;
	}
}
?>