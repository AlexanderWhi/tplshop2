<?php
include_once 'core/component/Component.class.php';
class Search extends Component {
	protected $mod_name='Поиск';
	protected $mod_title='Поиск';
	function actDefault(){
		global $ST,$get;
		$this->search=$get->getString('search');
		if(empty($this->search)){
			header('Location: /');exit;
		}
		
		$data=array(
			'rs'=>array(),
			'type_search'=>'',
		);
		if($data['type_search']=$type_search=$this->getURIVal('search')){
			if(isset($this->result[$type_search])){
				$pg=new Page();
				$query=$this->getQuery($type_search);
				$rs=$ST->select($query[1]);
				if($rs->next()){
					$pg->all=$rs->getInt('c');
				}
				$rs=$ST->select($query[0].' LIMIT '.$pg->getBegin().','.$pg->per);
				while ($rs->next()){
					$data['rs'][]=$rs->getRow();
				}
				$data['href']=$query[2];
				$data['title']=$query[3];
				$data['pg']=$pg;
			}else{
				header('Location: /');exit;
			}
		}else{
			foreach($this->result as $key=>$result ){
				$query=$this->getQuery($key);
				
				$rs=$ST->select($query[1]);
				if($rs->next()){
					$data['rs'][$key]['count']=$rs->getInt('c');
				}
				
				$rs=$ST->select($query[0].' LIMIT '.$this->type_size);
				while ($rs->next()){
					$data['rs'][$key]['result'][]=$rs->getRow();
				}
				
				$data['rs'][$key]['href']=$query[2];
				$data['rs'][$key]['title']=$query[3];
			}	
		}
		$this->setCommonCont();
		$this->display($data,dirname(__FILE__).'/search.tpl.php');
	}

	protected $search='';
	
	protected $result=array(
		
		'public'=>array(),
		'content'=>array(),
		'gallery'=>array(),
		'catalog'=>array(),
//		'forum'=>array()
	);
	
	protected $type_size=8;
	
	function getQuery($key){
		
		$search=explode(' ',trim(preg_replace('/\s+/',' ',$this->search)));
			if(in_array($key,array('public','news'))){
				$cond=" type IN('{$key}') 
					AND (state='main' OR state='public') ";
//				$relev=" MATCH nws_title, nws_content,nws_desc AGAINST ('".$this->search."') ";
				
//				$cond.=" AND (content ILIKE '%".SQL::slashes($this->search)."%' OR title ILIKE '%".SQL::slashes($this->search)."%' OR description ILIKE '%".SQL::slashes($this->search)."%')";
				$subCond=array();
				foreach ($search as $s) {
					$subCond[]="(content LIKE '%".SQL::slashes($s)."%' OR title LIKE '%".SQL::slashes($s)."%' OR description LIKE '%".SQL::slashes($s)."%')";
				}
//				$cond.=" AND (".implode(' OR ',$subCond).")";
				$cond.=" AND (".implode(' AND ',$subCond).")";
				
				$q="SELECT id ,title, description FROM sc_news WHERE ".$cond." ";
				$countQ="SELECT COUNT(id) AS c FROM sc_news WHERE ".$cond;
		
				$href="/news/view/";
				$title="Статьи";
			}
			if($key=='gallery'){
				$cond=" type='gallery' 
					";
				$subCond=array();
				foreach ($search as $s) {
					$subCond[]="(text LIKE '%".SQL::slashes($s)."%' OR name LIKE '%".SQL::slashes($s)."%' OR description LIKE '%".SQL::slashes($s)."%')";
				}
//				$cond.=" AND (".implode(' OR ',$subCond).")";
				$cond.=" AND (".implode(' AND ',$subCond).")";
				
				$q="SELECT id ,name as title, description FROM sc_gallery WHERE ".$cond." ";
				$countQ="SELECT COUNT(id) AS c FROM sc_gallery WHERE ".$cond;
		
				$href="/gallery/";
				$title="Галлерея";
			}
			if($key=='content'){
//				$cond=" mod_content_id=c_id AND (mod_location LIKE 'main' OR  mod_location LIKE 'footer' OR  mod_location LIKE 'top')
//						AND (mod_name ILIKE '%".SQL::slashes($this->search)."%' OR mod_title ILIKE '%".SQL::slashes($this->search)."%' OR c_text ILIKE '%".SQL::slashes($this->search)."%')";
				
//				$cond=" c_name ILIKE '%'||mod_alias||'%' AND mod_alias <>'/'";
				$cond=" mod_alias LIKE CONCAT('%',c_name,'%') AND mod_title<>'' AND c_text<>''";
//				$cond=" mod_alias =c_name ";
				
//				$cond.=" AND (mod_location LIKE '%main%' OR  mod_location LIKE '%footer%' OR  mod_location LIKE '%top%')";
//				$cond.=" AND (mod_name ILIKE '%".SQL::slashes($this->search)."%' OR mod_title ILIKE '%".SQL::slashes($this->search)."%' OR c_text ILIKE '%".SQL::slashes($this->search)."%')";
				
				$subCond=array();
				foreach ($search as $s) {
					$subCond[]="(mod_name LIKE '%".SQL::slashes($s)."%' OR mod_title LIKE '%".SQL::slashes($s)."%' OR c_text LIKE '%".SQL::slashes($s)."%')";
				}
//				$cond.=" AND (".implode(' OR ',$subCond).")";
				$cond.=" AND (".implode(' AND ',$subCond).")";
				
//				$q="SELECT mod_alias AS id,mod_title AS title,mod_description AS description FROM sc_content,sc_module WHERE ".$cond." ";
				$q="SELECT c_name AS id,mod_title AS title,mod_description AS description FROM sc_content,sc_module WHERE ".$cond." ";
				
				$countQ="SELECT COUNT(c_id) AS c FROM sc_content,sc_module WHERE ".$cond;
				
				$href="";
				$title="Разделы сайта";
			}
			
			
			if($key=='catalog'){
				$cond="";
				
//				$cond.=" AND (g.name ILIKE '%".SQL::slashes($this->search)."%' OR g.description ILIKE '%".SQL::slashes($this->search)."%' OR c.name ILIKE '%".SQL::slashes($this->search)."%' )";
				
				$subCond=array();
				foreach ($search as $s) {
					$subCond[]="(i.name LIKE '%".SQL::slashes($s)."%' OR i.description LIKE '%".SQL::slashes($s)."%' )";
				}
//				$cond.=" AND (".implode(' OR ',$subCond).")";
				$cond.=" AND (".implode(' AND ',$subCond).")";
				
				$cat=LibCatalog::getInstance();
				
				$q="SELECT g.*, g.id AS id, g.name AS title, g.description AS description FROM sc_shop_item g WHERE ".$cond."";
				$q="{$cat->select()} ".$cond."";
				
				$countQ="{$cat->selectCount()} ".$cond;
				
				$href="/catalog/goods/";
				$title="Товары";
			}

		return array($q,$countQ,$href,$title);
	}	
}
?>