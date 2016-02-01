<?php
include_once("modules/news/AdminNews.class.php");

class AdminArticle extends AdminNews {			
	
	function getStaffList(){
//		global $ST;
//		
//			$q="SELECT * FROM sc_gallery
//				WHERE type='staff'
//				ORDER BY name
//			";
//		$rs=$ST->select($q);
		$out=array();
//		while ($rs->next()) {
//			$out[$rs->get('id')]=$rs->getRow();
//		}
		return $out;
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			
			'title'=>'',
			'date'=>date('d.m.Y'),
			'state'=>'public',
			'description'=>'',
			'img'=>'',
			'content'=>'',
			'position'=>0,
			'author'=>'',
			'category'=>0,
			'type'=>$this->getType(),
			'rel'=>array(),
			'author'=>$this->getUser('name'),
		);
		
		if($id=$get->getInt('id')){
			$queryStr="SELECT * FROM sc_news WHERE id =".$id;
			$rs=$ST->select($queryStr);
			if($rs->next()){
				$field=$rs->getRow()+$field;
				$field['date']=dte($field['date']);
				
				
//				$rs1=$ST->select("SELECT par FROM sc_shop_relation WHERE type=100 AND ch={$field['id']}");
//				while ($rs1->next()) {
//					$field['rel'][]=$rs1->getInt('par');
//				}
				$rs1=$ST->select("SELECT child FROM sc_relation WHERE type='public' AND parent={$id}");
				while ($rs1->next()) {
					$field['rel'][]=$rs1->getInt('child');
				}
			}
		}
		$field['status']=array('public'=>'Опубликовано','edit'=>'На редактировании');
		$field['staff_list']=$this->getStaffList();
		$field['category_list']=$this->enum("{$field['type']}_category");
		$this->explorer[]=array('name'=>'Редактировать');
		$field['article_list']=$this->getArticle();
		
		$field+=$this->getSendCount($id);
		
		
		$this->display($field,dirname(__FILE__).'/admin_article_edit.tpl.php');
	}
	
	function getArticle($type='public'){
		global $ST;
		return $ST->select("SELECT * FROM sc_news WHERE type='$type' ORDER BY id DESC")->toArray();
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
			'state'=>$post->get('state'),
			'category'=>$post->getInt('category'),
			'position'=>$post->getInt('position'),
			'gallery'=>$post->getInt('gallery'),
			'type'=>$this->getType()
		);
		$img_out="";
		if(!empty($_FILES['upload']['name']) && isImg($_FILES['upload']['name'])){
			
			$img=$this->cfg('NEWS_IMAGE_PATH').'/'.md5($_FILES['upload']['tmp_name']).".".file_ext($_FILES['upload']['name']);
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$img);
			$data['img']=$img;
			$img_out=scaleImg($img,'w200');
		}
		
		if($post->getInt('clear')){
			$data['img']='';
		}
		
		if($id){
			$ST->update('sc_news',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_news',$data);	
		}
			
		$ST->delete('sc_relation',"parent=$id AND type='public'");
		foreach ($post->getArray('public_rel') as $v) {
			$ST->insert('sc_relation',array('parent'=>$id,'type'=>'public','child'=>$v));
		}
		
			
		$msg="Сохранено";
		
		if(true){
		
			$content='';
			
			$content.='<small>'.date('d.m.Y').'</small> ';
			
			$content.='<strong>'.$post->getHtml('title').'</strong><br />';
			$content.='<span>'.$post->getHtml('description').'</span><br />';
			$content.='<a href="http://'.$this->cfg('SITE').'/'.$post->get('type').'/'.$id.'/">подробнее...</a>';


			if($post->exists('save_and_send')){
				$mail=new Mail();
				$mail->setFromMail(array($this->cfg('SITE'), $this->cfg('mail')));
				$key='http://'.$this->cfg('SITE').'/cabinet/unsubscribe/?key='.md5($this->getUser('mail').$this->getType().'unsubscribe').'&type='.$this->getType().'&mail='.$this->getUser('mail');
				$key='<a href="'.$key.'">'.$key.'</a>';
				
				$mail->setTemplate('letter_'.$this->getType(),array('FROM_SITE'=>$this->cfg('SITE'),'CONTENT'=>$content,'BODY'=>$post->get('content'),'TITLE'=>$post->get('title')));
				
				
				$mail->xsend($this->getUser('mail'),array('UNSUBSCRIBE'=>$key));
				
			}elseif($post->exists('save_and_send_all')){
				$q="SELECT distinct mail,id FROM sc_subscribe WHERE type LIKE '%".$this->getType()."%' 
				AND NOT EXISTS(SELECT mailid FROM sc_news_sendlog WHERE id=mailid AND newsid=$id){$this->getMailFilter()}";
				if($post->getInt('pack')){
					$q.=" LIMIT {$post->getInt('pack')}";
				}
				$rs=$ST->select($q);
				$mail=new Mail();
//				$mail->setFromMail($this->getConfig('mail'));
				$mail->setFromMail(array($this->cfg('SITE'), $this->cfg('mail')));
				
				$mail->setTemplate('letter_'.$this->getType(),array('FROM_SITE'=>$this->cfg('SITE'),'CONTENT'=>$content,'BODY'=>$post->get('content'),'TITLE'=>$post->get('title')));
				$n=0;
				while ($rs->next()) {
					if(check_mail($m=trim($rs->get('mail')))){
						$key='http://'.$this->cfg('SITE').'/cabinet/unsubscribe/?key='.md5($rs->get('mail').$this->getType().'unsubscribe').'&type='.$this->getType().'&mail='.$rs->get('mail');
						$key='<a href="'.$key.'">'.$key.'</a>';
						$mail->xsend($m,array('UNSUBSCRIBE'=>$key));
						$ST->insert('sc_news_sendlog',array('mailid'=>$rs->get('id'),'newsid'=>$id));
						$n++;
					}else{
						$ST->delete('sc_subscribe',"mail='".SQL::slashes($rs->get('mail'))."'");
					}
				}
				$msg.=" отправлено $n";
			}
		}
		echo printJSONP(array('msg'=>$msg,'id'=>$id,'img'=>$img_out));exit;
	}
}
?>