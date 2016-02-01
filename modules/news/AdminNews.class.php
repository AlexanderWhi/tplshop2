<?php
include_once("core/component/AdminListComponent.class.php");

class AdminNews extends AdminListComponent {			
	
	function getType(){
		return $this->getURIVal('admin');
	}
	
	function getSendCount($id){
		global $ST;
		$field['send_count']=0;
		$field['sended_count']=0;
		
		$rs=$ST->select("SELECT COUNT(distinct mail,id) AS c FROM sc_subscribe WHERE type LIKE '%".$this->getType()."%'
		{$this->getMailFilter()}");
		if($rs->next()){
			$field['send_count']=$rs->getInt('c');
		}
		$rs=$ST->select("SELECT COUNT(distinct mail,id) AS c FROM sc_subscribe WHERE type LIKE '%".$this->getType()."%' 
		AND EXISTS(SELECT mailid FROM sc_news_sendlog WHERE id=mailid AND newsid={$id})
		{$this->getMailFilter()}");
		if($rs->next()){
			$field['sended_count']=$rs->getInt('c');
		}
		return $field;
	}
	
	
	function actDefault(){
		global $ST;
		parent::refresh();
		$this->updateParams();

		$queryStr="SELECT count(*) AS c FROM sc_news WHERE type='".$this->getType()."'";
		$rs=$ST->select($queryStr);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order="ORDER BY date desc,id DESC, position DESC";
		if($this->type=='article'){
			$order="ORDER BY position DESC, view DESC";
		}
		
		$queryStr="SELECT * FROM sc_news WHERE type='".$this->getType()."' $order limit ".$this->page->getBegin().",".$this->page->per;
		$this->rs=$ST->select($queryStr);
		$this->display(array(),dirname(__FILE__).'/news_list.tpl.php');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>$get->getInt('id'),
			'title'=>'',
			'date'=>date('d.m.Y'),
			'date_to'=>'',
			'state'=>'public',
			'description'=>'',
			'img'=>'',
			'content'=>'',
			'position'=>0,
			'gallery'=>0,
			'author'=>'',
			'type'=>'news'
		);
		
		if($get->get('url') && $get->get('guid')){
			if($rss=$this->getRssData($get->get('url'),$get->get('guid'))){
				$field=array_merge($field,$rss);

				if($field['img']){
					$img=get_url($field['img']);
					$f_ext=file_ext($field['img']);
					$name=md5($img).".$f_ext";
					$path=$this->cfg('NEWS_IMAGE_PATH').'/'.$name;
					file_put_contents(ROOT.$path,$img);
					$field['img']=$path;
				}
				
			}
			
		}
		
		if($field['id']){
			$queryStr="SELECT ".join(',',array_keys($field))." FROM sc_news WHERE id =".$field['id'];
			$rs=$ST->execute($queryStr);
			if($rs->next()){
				$field=$rs->getRow();
				$field['date']=dte($field['date']);
				$field['date_to']=dte($field['date_to']);
			}
		}else{
			$field['type']=$this->getURIVal('admin');
			$field['author']=$this->getUser('name');
		}
		$field['status']=array('public'=>'Опубликовано','edit'=>'На редактировании');
		
		$field['gallery_list']=array();//$ST->select("SELECT * FROM sc_gallery WHERE type='gallery' ORDER BY NAME")->toArray();
		
		$this->explorer[]=array('name'=>'Редактировать');
		
		$this->display($field,dirname(__FILE__).'/news_edit.tpl.php');
	}
	
	
	
	function actSave(){
		global $ST,$get,$post;
		$id=$post->getInt('id');
		
		$data=array(
			'content'=>$post->get('content'),
			'description'=>$post->get('description'),
			'title'=>$post->get('title'),
			'author'=>$post->get('author'),
			'date'=>dte($post->get('date'),'Y-m-d'),
			'date_to'=>$post->get('date_to')?dte($post->get('date_to'),'Y-m-d'):null,
			'state'=>$post->get('state'),
			'img'=>$post->get('img'),
			'gallery'=>$post->getInt('gallery'),
			'position'=>$post->getInt('position'),
			'type'=>$this->getType()
		);
		
		if($data['img'] && file_exists(ROOT.$data['img'])){
			$from=ROOT.$data['img'];
			$name=md5_file(ROOT.$data['img']).'.'.substr($data['img'],-3);
			
			$path=$this->cfg('NEWS_IMAGE_PATH').'/'.$name;
			$data['img']=$path;
			if(!file_exists(ROOT.$data['img'])){
				rename($from, ROOT.$data['img']);
			}     
		}elseif($data['img']=='clear'){
			$data['img']='';
		}
		
		if($id){
			$ST->update('sc_news',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_news',$data);	
		}
		if(true){
		
			$content='';
			if($post->get('type')=='news'){
				$content.='<small>'.date('d.m.Y').'</small> ';
			}
			$content.='<strong>'.$post->getHtml('title').'</strong><br />';
			$content.='<span>'.$post->getHtml('description').'</span><br />';
			$content.='<a href="http://'.$this->cfg('SITE').'/'.$post->get('type').'/view/'.$id.'/">подробнее...</a>';


			if($get->exists('save_and_send')){
				$mail=new Mail();
				$mail->setFromMail(array($this->cfg('SITE'), $this->cfg('mail')));
				$mail->setTemplate('letter_'.$post->get('type'),array('FROM_SITE'=>$this->cfg('SITE'),'CONTENT'=>$content,'BODY'=>$post->get('content')));
				$mail->xsend($this->getUser('mail'));
				
			}elseif($get->exists('save_and_send_all')){
				$rs=$ST->execute("SELECT distinct mail FROM sc_subscribe WHERE type LIKE '%".$post->get('type')."%'");
				$mail=new Mail();
				$mail->setFromMail($this->getConfig('mail'));
				$mail->setFromMail(array($post->get('title'), $this->cfg('mail')));
				$mail->setTemplate('letter_'.$post->get('type'),array('FROM_SITE'=>$this->cfg('SITE'),'CONTENT'=>$content,'BODY'=>$post->get('content')));
				
				while ($rs->next()) {
					$key='http://'.$this->cfg('SITE').'/cabinet/unsubscribe/?key='.md5($rs->get('mail').$post->get('type').'unsubscribe').'&type='.$post->get('type').'&mail='.$rs->get('mail');
					$key='<a href="'.$key.'">'.$key.'</a>';
					$mail->xsend($rs->get('mail'),array('UNSUBSCRIBE'=>$key));
				}
			}
		}
		
		echo printJSON(array('msg'=>'Сохранено','nws_id'=>$id));exit();
	}

	
	function updateParams(){
		preg_match('|^/admin/([^/]+)|',$this->getURI(),$arr);
		$this->type=isset($arr[1])?$arr[1]:'news';
	}
		
	function actRemove(){		
		global $ST,$post;
		$remove=$post->get('item');
		if($remove){
			$ST->delete('sc_news',"id IN (".implode(',',$remove).")");
			echo printJSON(array('msg'=>'Удалено'));exit;
		}else{
			$ST->delete('sc_news',"id={$post->getInt('id')}");
			echo printJSON(array("id"=>$post->getInt('id')));exit;
		}
		
		
		
	}
	
	function actReset(){		
		global $ST;
		$ST->update('sc_news',array('view'=>0),"id=".intval($_POST['id']));
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}	
	function actUpload(){
		global $get;
		if(isset($_FILES['upload'])){
			$name=md5_file($_FILES['upload']['tmp_name']).'.'.file_ext($_FILES['upload']['name']);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$path);
			$img=scaleImg($path,$get->get('size'));
			if($get->get('resize')=='true'){
				$path=scaleImg($path,$get->get('size'));
			}
		}
		echo printJSONP(array('msg'=>'Сохранено','path'=>$path,'img'=>$img),$get->get('cb'));exit;
	}
	
	function actRss(){
		global $ST,$get;
		$data=array(
			'src_list'=>$this->enum('news_rss_src'),
			'news_list'=>array(),
			'url'=>$get->get('url'),
		);
	
		if($get->get('url')){
			$data['news_list']=$this->getRssData($get->get('url'));
		}
		
		$this->display($data,dirname(__FILE__).'/news_list_rss.tpl.php');
	}
	
	
	function getRssData($url,$guid=null){
		$xml=get_url($url);
		$xml=simplexml_load_string($xml);
		$data=array();
		foreach ($xml->channel->item as $item) {
			
			$i=array(
				'title'=>u2w($item->title),
				'content'=>u2w($item->description),
				'img'=>(string)@$item->enclosure->attributes()->url,
				'date'=>date('Y-m-d',strtotime($item->pubDate)),
				'guid'=>(string)$item->guid,
			);
			if($guid && $guid==(string)$item->guid){
				return $i;
			}
			
			$data[]=$i;
		}
		return $data;
	}
	function getMailFilter(){
		
		$q="
		AND mail NOT LIKE '%mail.ru'
		AND mail NOT LIKE '%bk.ru'
		AND mail NOT LIKE '%list.ru'
		AND mail NOT LIKE '%inbox.ru'
		AND mail NOT LIKE '%gmail.com'
		";
		$q='';
		return $q;
	}
}
?>