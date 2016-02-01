<?php
include_once("core/component/Component.class.php");
class Shopnote extends Component {
	
	function __construct(){
		$this->setCommonCont();
	}
	
	function actDefault(){
		$data=array();
		$data['rs']=$this->getUserNote();
		
		$this->display($data,dirname(__FILE__).'/shopnote.tpl.php');
	}

	function actMakeBasket(){
		global $post;
		foreach ($post->getArray('count_list') as $id=>$count) {
			if(in_array($id,$post->getArray('item'))){
				$this->addBasket($id,$count);
			}
		}
		header('Location: /catalog/basket/');exit;
	}
	
	function actSearch(){
		global $ST,$post;
		$q="SELECT * FROM sc_shop_item WHERE in_stock>-1 AND name LIKE '%".SQL::slashes($post->get('search'))."%'";
		
		$data['rs']=$ST->select($q)->toArray();
		
		
		echo $this->render($data,dirname(__FILE__).'/search_result.tpl.php');
	}
	
	
	function actAddToList(){
		global $ST,$post;
		$data['rs']=array();
		
		if($count=$post->getArray('count_list')){
			$q="SELECT * FROM sc_shop_item WHERE id IN(".implode(',',array_keys($count)).")";
			$rs=$ST->select($q);
			while ($rs->next()) {
				$data['rs'][$rs->getInt('id')]=$rs->getRow();
				$data['rs'][$rs->getInt('id')]['count']=$count[$rs->getInt('id')];
			}
		}
		if($count=$post->getArray('count')){
			$q="SELECT * FROM sc_shop_item WHERE id IN(".implode(',',array_keys($count)).")";
			$rs=$ST->select($q);
			while ($rs->next()) {
				$data['rs'][$rs->getInt('id')]=$rs->getRow();
				$data['rs'][$rs->getInt('id')]['count']=$count[$rs->getInt('id')];
			}
			
		}
		echo $this->render($data,dirname(__FILE__).'/list_result.tpl.php');
	}
	
	function actSaveList(){
		global $ST,$post;
		
		$q="SELECT * FROM sc_shop_note WHERE name='".SQL::slashes($post->get('name'))."' AND userid={$this->getUserId()}";
		$rs=$ST->select($q);
		if($rs->next()){
			$noteid=$rs->getInt('id');
		}else{
			$noteid=$ST->insert('sc_shop_note',array('name'=>$post->get('name'),'userid'=>$this->getUserId()));
		}
		$ST->delete('sc_shop_note_item',"noteid=$noteid");
		foreach ($post->getArray('count_list') as $itemid=>$count) {
			$ST->insert('sc_shop_note_item',array(
				'itemid'=>$itemid,
				'count'=>$count,
				'noteid'=>$noteid,
			));
		}
		$data['rs']=$this->getUserNote();
		echo $this->render($data,dirname(__FILE__).'/list.tpl.php');
	}
	function getUserNote(){
		global $ST;
		return $ST->select("SELECT * FROM sc_shop_note WHERE userid={$this->getUserId()}")->toArray();
	}
	function actGetList(){
		global $ST,$post;
		
		$q="SELECT * FROM sc_shop_item,sc_shop_note_item ni WHERE id=ni.itemid AND EXISTS(SELECT id FROM sc_shop_note WHERE name='".SQL::slashes($post->get('name'))."' AND id=ni.noteid) ";
		$data['rs']=$ST->select($q)->toArray();
		
		echo $this->render($data,dirname(__FILE__).'/list_result.tpl.php');
	}
	
	function actRemoveList(){
		global $ST,$post;
		$rs=$ST->select("SELECT * FROM sc_shop_note WHERE name='".SQL::slashes($post->get('name'))."' AND userid={$this->getUserId()}");
		if($rs->next()){
			$ST->delete('sc_shop_note_item',"noteid={$rs->get('id')}");
			$ST->delete('sc_shop_note',"id={$rs->get('id')}");
			
		}
		$data['rs']=$this->getUserNote();
		echo $this->render($data,dirname(__FILE__).'/list.tpl.php');
	}
}
?>