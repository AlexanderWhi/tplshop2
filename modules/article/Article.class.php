<?php
include_once('modules/news/News.class.php');
class Article extends News {
	
	function actDefault(){
		global $ST;
		
		if($id=$this->getURIIntVal($this->getType())){
			$this->actView();
			return;
		}		
		$page=new Page($this->cfg('PAGE_SIZE'));
//		$page=new Page(2);
				
		$condition="state='public' AND type='".$this->getType()."'";
		if($c=$this->getURIIntVal('category')){
			$condition.=" AND category=$c";
		}

		$queryStr="SELECT COUNT(*) as c FROM sc_news WHERE $condition";
		$rs=$ST->select($queryStr);
		if($rs->next()){
			$page->all=$rs->getInt("c");
		}
				
		$order=" ORDER BY date DESC,position DESC";
		if($this->getType()=='article'){
			$order=" ORDER BY position DESC, view DESC";
		}

		$queryStr="SELECT *,value_desc AS category_desc FROM sc_news n
		LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='".$this->getType()."_category') AS c ON c.field_value=n.category
		WHERE $condition $order LIMIT ".$page->getBegin().",".$page->per;
		$rs=$ST->select($queryStr)->toArray();
		
		$data=array('rs'=>$rs,'pg'=>$page);
		
		$data['category_list']=$this->enum("{$this->getType()}_category");
		$this->setCommonCont();
		if($tpl=$this->getTpl($this->getType().'.tpl.php')){
			$this->display($data,$tpl);
		}else{
			$this->display($data,$this->getTpl('news.tpl.php'));
		}
	
	}
	
	function getRelaionArticle($id,$type='public'){
		global $ST;
		return $ST->select("SELECT n.*,value_desc AS category_desc FROM sc_news n
		LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='".$type."_category') AS c ON c.field_value=n.category
		
		WHERE id IN(SELECT child FROM sc_relation WHERE type='$type' AND parent=$id)")->toArray();
	}
	
	function actView(){
		global $ST;
		
		$id=$this->getURIIntVal('view');
		if(!$id=$this->getURIIntVal($this->getType())){
			$id=$this->getURIIntVal('view');
		}
		$ST->update('sc_news',array('view=view+1'),"id=".$id);
		$rs=$ST->select("SELECT n.*,value_desc AS category_desc,g.name AS g_name,g.description AS g_description FROM sc_news n
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='{$this->getType()}_category') AS c ON c.field_value=n.category
			LEFT JOIN (SELECT id,name,description FROM sc_gallery WHERE type='staff') AS g ON g.id=n.gallery
		WHERE n.id=".$id);
		if($rs->next()){
			$data=$rs->getRow();
			$this->setPageTitle($rs->get('title'));
				
			$data['rel']=$this->getRelaionArticle($id);
					
//			$data['gallery_img_list']=array();
//			if($data['gallery']){
//				$rs=$ST->select("SELECT * FROM sc_gallery WHERE id={$data['gallery']}");
//				if($rs->next() && $rs->get('images')){
//					$images=explode(',',$rs->get('images'));
//					$data['gallery_img_list']=array_slice(array_reverse($images),0,3);
//
//				}
//			}
			$this->setCommonCont();
			if($tpl=$this->getTpl($this->getType().'_view.tpl.php')){
				$this->display($data,$tpl);
			}else{
				$this->display($data,$this->getTpl('news_view.tpl.php'));
			}
		}
	}
	
}
?>