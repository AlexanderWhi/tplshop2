<?
include_once 'core/component/BaseComponent.class.php';
class Ceo extends BaseComponent  {
	function actDefault(){
		
	}

	function actSave(){
		global $ST,$post;
		if($this->isAdmin()){
			$data=array(
				'url'=>$post->get('url'),
				'rule'=>$post->get('rule'),
				'title'=>$post->get('title'),
				'header'=>$post->get('header'),
				'keywords'=>$post->get('keywords'),
				'description'=>$post->get('description'),
			);
			$rs=$ST->select("SELECT url FROM sc_ceo_meta WHERE url='".$data['url']."' LIMIT 1");
			if($rs->next()){
				$ST->update('sc_ceo_meta',$data,"url='".$data['url']."'");
			}else{
				$ST->insert('sc_ceo_meta',$data,'url');
			}
		}
		echo 'ok';exit;
	}
	
	function actDel(){
		global $ST,$post;
		if($this->isAdmin()){
			$ST->delete('sc_ceo_meta',"url='{$post->get('url')}'");
		}
		echo 'ok';exit;
	}
	
	function actSaveText(){
		global $ST,$post;
		if($this->isAdmin()){
			$data=array(
				'url'=>$post->get('url'),
				'place'=>$post->get('place'),
				'rule'=>$post->get('rule'),
				'text'=>$post->get('text'),
			);
			$rs=$ST->select("SELECT url FROM sc_ceo_text WHERE url='{$data['url']}' AND place='{$data['place']}' LIMIT 1");
			if($rs->next()){
				$ST->update('sc_ceo_text',$data,"url='{$data['url']}' AND place='{$data['place']}'");
			}else{
				$ST->insert('sc_ceo_text',$data,'url');
			}
		}
		echo 'ok';exit;
	}
	
	function actDelText(){
		global $ST,$post;
		if($this->isAdmin()){
			$ST->delete('sc_ceo_text',"url='{$post->get('url')}' AND place='{$post->get('place')}'");
		}
		echo 'ok';exit;
	}
	
	function actGetText(){
		global $ST,$post;
		$data=array('rule'=>'','text'=>'','url'=>$post->get('url'),'exists'=>false);
		$rs=$ST->select("SELECT text,url,rule FROM sc_ceo_text
			 WHERE
			 	place='{$post->get('place')}' 
			 	AND ((url='".SQL::slashes($post->get('url'))."' AND rule='=') 
			 	OR ('".SQL::slashes($post->get('url'))."' LIKE CONCAT(url,'%') AND rule!='=' ))
			 ORDER BY LENGTH(url) DESC LIMIT 1");
		
		if($rs->next()){
			$data=$rs->getRow();
			$data['exists']=true;
		}
		$data['place']=$post->get('place');
		echo printJSON($data);exit;
	}
}
?>