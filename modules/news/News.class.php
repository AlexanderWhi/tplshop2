<?php
include_once('core/component/Component.class.php');
class News extends Component {
	
//	protected  $tplComponent="news.tpl.php";
//	protected  $tplContainer="core/tpl/www/main_container.tpl.php";


	/**
	 * @return Page
	 */
	
	function getType(){
		$type="news";
		if(preg_match('|^/([^/]+)|',$this->getURI(),$res)){
			$type=$res[1];
		}
		return $type;
	}
	function actDefault(){
		global $ST;
		
		if($id=$this->getURIIntVal($this->getType())){
			$this->actView();
			return;
		}		
		$page=new Page($this->cfg('PAGE_SIZE'));
//		$page=new Page(2);
				
		$condition="state='public' AND type='".$this->getType()."'";
		

		$queryStr="SELECT COUNT(*) as c FROM sc_news WHERE $condition";
		$rs=$ST->select($queryStr);
		if($rs->next()){
			$page->all=$rs->getInt("c");
		}
				
		$order=" ORDER BY date DESC,position DESC";
		if($this->getType()=='article'){
			$order=" ORDER BY position DESC, view DESC";
		}

		$queryStr="SELECT * FROM sc_news 
		WHERE $condition $order LIMIT ".$page->getBegin().",".$page->per;
		$rs=$ST->select($queryStr)->toArray();
		
		$data=array('rs'=>$rs,'pg'=>$page);
		
		if($tpl=$this->getTpl($this->getType().'.tpl.php')){
			$this->display($data,$tpl);
		}else{
			$this->display($data,$this->getTpl('news.tpl.php'));
		}
	}

	
	function actRss(){
		global $ST;
				
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
		header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
		header ("Pragma: no-cache");  
		header("Content-Type: text/xml; charset=windows-1251");
		echo '<?xml version="1.0" encoding="windows-1251"?>';
		$minusdate = date('Z');
		
		$condition="state='public' AND type='".$this->getType()."'";
		
		$order=" ORDER BY date DESC";
		
		$this->rs=$ST->select("SELECT * FROM sc_news WHERE $condition $order LIMIT 30")->toArray();
		
		include('rss.tpl.php');
	}
	
	function actView(){
		global $ST;
		
		$id=$this->getURIIntVal('view');
		if(!$id=$this->getURIIntVal($this->getType())){
			$id=$this->getURIIntVal('view');
		}
		$ST->update('sc_news',array('view=view+1'),"id=".$id);
		$rs=$ST->select("SELECT * FROM sc_news WHERE id=".$id);
		if($rs->next()){
			$data=$rs->getRow();
			$this->explorer[]=array('name'=>$rs->get('title'));
			$this->setTitle($rs->get('title'));
			$this->setHeader($rs->get('title'));
			
			
			$data['gallery_img_list']=array();
			if($data['gallery']){
				$rs=$ST->select("SELECT * FROM sc_gallery WHERE id={$data['gallery']}");
				if($rs->next() && $rs->get('images')){
					$images=explode(',',$rs->get('images'));
					$data['gallery_img_list']=array_slice(array_reverse($images),0,3);

				}
			}
			
			if($tpl=$this->getTpl($this->getType().'_view.tpl.php')){
				$this->display($data,$tpl);
			}else{
				$this->display($data,$this->getTpl('news_view.tpl.php'));
			}
		}
	}
}
?>