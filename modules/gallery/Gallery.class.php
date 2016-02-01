<?php
include_once('core/component/Component.class.php');
class Gallery extends Component {
		
	function actDefault(){
		global $ST;
			
		if($id=$this->getURIIntVal(trim($this->mod_uri,'/'))){
			$this->actView($id);return;
		}
		$page=new Page($this->cfg('PAGE_SIZE'));
//		$page=new Page(1);
		
		$type=trim($this->mod_uri,'/');
		
		$condition="g.type='".SQL::slashes($type)."' AND g.sort>-1";
		
		$cat_list=$this->enum("gal_{$type}_cat");
		$label_list=$this->enum("gal_{$type}_label");
		$label_list=array();
		
		$rs=$ST->select("SELECT * FROM sc_enum e WHERE field_name='gal_{$type}_label' AND EXISTS (SELECT gallery_id FROM sc_gallery_label , sc_gallery g WHERE gallery_id=g.id AND label_id=e.field_value AND type='".SQL::slashes($type)."' AND sort>-1) ORDER BY position");
		while ($rs->next()) {
			$label_list[$rs->get('field_value')]=$rs->get('value_desc');
		}
		
		$cat='';
		if(preg_match('|cat-([\d\w]+)|',$this->getURIVal($type),$res)){
			$cat=$res[1];
			$condition.=" AND cat='{$cat}'";
		}
		$label='';
		if(preg_match('|label-([\d\w]+)|',$this->getURIVal($type),$res)){
			$label=$res[1];
			$condition.="  AND EXISTS (SELECT gallery_id FROM sc_gallery_label WHERE gallery_id=g.id AND label_id=$label) ";
		}
		
		
		$queryStr="SELECT COUNT(*) as c FROM sc_gallery g WHERE $condition";
		$rs=$ST->select($queryStr);
		if($rs->next()){
			$page->all=$rs->getInt("c");
		}
				
		$order="ORDER BY g.sort DESC, g.date DESC, g.id DESC";
		if($type=='staff'){
			$order="ORDER BY ct.position, g.sort DESC, g.date DESC, g.id DESC";
		}
		$queryStr="SELECT g.*,p.title AS p_title,p.id AS p_id,ct.value_desc AS ct_desc  FROM sc_gallery g
			LEFT JOIN (SELECT field_value,value_desc,position FROM sc_enum WHERE field_name='gal_{$type}_cat') AS ct ON ct.field_value=g.cat
			LEFT JOIN (SELECT n.* FROM sc_news n,(SELECT MAX(id) as id ,gallery FROM sc_news GROUP BY gallery) AS mn WHERE type='public' AND mn.id=n.id) AS p ON p.gallery=g.id 
		
		WHERE $condition $order LIMIT ".$page->getBegin().",".$page->per;
				
		$rs=$ST->select($queryStr)->toArray();
			
		
		$data=array('rs'=>$rs,'pg'=>$page,'type'=>$type);
		
		$data['cat_list']=$cat_list;
		$data['cat_list_item']=array();
//		$data['cat']=$cat;
		
		$rs=$ST->select("SELECT * FROM sc_gallery WHERE type='$type' AND cat<>''");
		while ($rs->next()) {
			$data['cat_list_item'][$rs->get('cat')][]=$rs->getRow();
		}
		
		
//		$data['label_list']=$label_list;
//		$data['label']=$label;
				
		
		$tpl=dirname(__FILE__).'/'.$type.'.tpl.php';
		if(file_exists($tpl)){
			$this->display($data,$tpl);return;
		}
		$this->display($data,dirname(__FILE__).'/gallery.tpl.php');
		
	}
	
	function actView($id=0){
		global $ST;
		$type=trim($this->mod_uri,'/');
		$rs=$ST->select("SELECT * FROM sc_gallery WHERE id=".$id);
		if($rs->next()){
			$data=$rs->getRow();
			$data['pg']=$pg=new Page($this->cfg('PAGE_SIZE')); 
//			$data['pg']=$pg=new Page(3); 
			$this->setPageTitle($rs->get('name'));
			$data['images']=array();
			$pg->all=0;
			
			$images=array();
			
			$rs=$ST->select("SELECT * FROM sc_gallery_img WHERE gallery_id=$id ORDER BY pos");
			while ($rs->next()) {
				$images[]=$rs->getRow();
			}
			
			if($images){
				$pg->all=count($images);
				$data['images']=array_slice($images, $pg->getBegin() ,$pg->per);
			}

			$data['label_list']=$ST->select("SELECT gl.*,l.value_desc AS l_desc FROM sc_gallery_label gl
				LEFT JOIN (SELECT * FROM sc_enum WHERE field_name='gal_{$type}_label') AS l ON l.field_value=gl.label_id
			WHERE gallery_id=$id")->toArray();
		}
		$this->setCommonCont();
		$tpl=dirname(__FILE__).'/'.$type.'_view.tpl.php';
		if(file_exists($tpl)){
			$this->display($data,$tpl);return;
		}
		$this->display($data,dirname(__FILE__).'/gallery_view.tpl.php');
	}
}
?>