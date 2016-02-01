<?php
include_once("core/component/AdminListComponent.class.php");
class Adv extends AdminListComponent {
	protected $mod_name='Баннеры';
	protected $mod_title='Баннеры';
	function actDefault(){
		parent::refresh();
		global $ST;
		
		$q="SELECT count(*) AS c FROM sc_advertising" ;
		$rs=$ST->select($q);
		if($rs->next()){
			$this->page->all=$rs->getInt('c');
		}
		$q="select adv.*,pl.description AS pl_description from sc_advertising adv
		LEFT JOIN sc_advertising_place AS pl ON pl.id=adv.place
		
		LIMIT ".$this->page->getBegin().",".$this->page->per ;
		$data['rs']=$ST->select($q);

		$this->display($data,dirname(__FILE__).'/adv.tpl.php');
	}

	function actEdit(){
		global $ST,$get;
		
		$data=array(
			'id'=>$get->getInt('id'),
			'description'=>'',
			'file'=>'',
			'place'=>'',
			'start_date'=>date('d.m.Y'),
			'stop_date'=>date('d.m.Y'),
			'url'=>'',
			'code'=>''
		);
		if($data['id']){
			$rs=$ST->select("SELECT ".join(',',array_keys($data))." FROM sc_advertising WHERE id=".$data['id']);
			if($rs->next()){
				$data=$rs->getRow();
				$data['start_date']=dte($data['start_date']);
				$data['stop_date']=dte($data['stop_date']);
			}
		}

		$q="SELECT * FROM sc_advertising_place";
		$data['placeList']=$ST->select($q);
		$this->display($data,dirname(__FILE__).'/adv_edit.tpl.php');
	}
	
	function actSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		$data=array(
			'description'=>$post->get('description'),
			'file'=>$post->get('file'),
			'place'=>$post->getInt('place'),
			'start_date'=>dte($post->get('start_date'),'Y-m-d'),
			'stop_date'=>dte($post->get('stop_date'),'Y-m-d'),
			'url'=>$post->get('url'),
			'code'=>$post->get('code')
		);
		
		if($data['file'] && file_exists(ROOT.$data['file'])){
			$from=ROOT.$data['file'];
			$name=md5_file(ROOT.$data['file']).'.'.substr($data['file'],-3);
			
			$path=$this->cfg('ADVERTISING_PATH').'/'.$name;
			$data['file']=$path;
			if(!file_exists(ROOT.$data['file'])){
				rename($from, ROOT.$data['file']);
			}     
		}elseif($data['file']=='clear'){
			$data['file']='';
		}
		
		if($id){
			$ST->update('sc_advertising',$data,'id='.$id);
		}else{
			$id=$ST->insert('sc_advertising',$data);
		}
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit;
	}
	
	
	
	function advHtml($src){
		$r="";	
		if(preg_match('/\.(jpg|png|gif)$/i',$src)){	
			$r.='<img src="'.$src.'"  style="margin:0;border:0"/>';
		}elseif(preg_match('/\.(swf)$/i',$src)){
			$r.='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0">
				<param name="movie" value="'.$src.'?link='.'" />
				<param name="quality" value="high" />
				<param name="wmode" value="opaque" />
				<param name="allowScriptAccess" value="always" />
				<embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowScriptAccess="always" wmode="opaque"></embed>
				</object>';
		}
				
		return $r;
	}
	
	
	function actUpload(){
		if(isset($_FILES['upload'])){
			$name=md5_file($_FILES['upload']['tmp_name']).'.'.substr($_FILES['upload']['name'],-3);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$path);
			$html=$this->advHtml($path);
		}
		echo '<script type="text/javascript">parent._upload('.printJSON(array('msg'=>'Сохранено','path'=>$path,'html'=>$html)).')</script>';exit;
	}
	
	function actRemove(){
		global $ST,$get;
		$queryStr="delete from sc_advertising where id =".$get->getInt("id");
		$ST->executeDelete($queryStr);
		header("Location:.");exit;
	}
	
	function actReset(){
		global $ST,$get;
		$ST->update('sc_advertising',array('show_ad'=>0,'click'=>0),"id=".$get->getInt("id"));
		header("Location:.");exit;
	}
	
	
	function actPlace(){
		global $ST;
		parent::refresh();
		$q="SELECT count(*) AS c FROM sc_advertising_place" ;
		$rs=$ST->execute($q);
		if($rs->next()){
			$this->page->all=$rs->getInt('c');
		}
		$q="select * from sc_advertising_place LIMIT ".$this->page->getBegin().",".$this->page->per ;
		$data['rs']=$ST->execute($q);	
		$this->setPageTitle('Места');
		$this->display($data,dirname(__FILE__).'/adv_place.tpl.php');
		
	}
	
	function actPlEdit(){
		global $ST,$get;
		$data=array(
			'id'=>$get->getInt('id'),
			'description'=>'',
			'width'=>0,
			'height'=>0
		);
		if($data['id']){
			$rs=$ST->select("SELECT ".join(',',array_keys($data))." FROM sc_advertising_place WHERE id=".$data['id']);
			if($rs->next()){
				$data=$rs->getRow();
			}
		}
		$this->setPageTitle('Редактировать место');
		$this->display($data,dirname(__FILE__).'/adv_place_edit.tpl.php');
		
	}
	
	function actPlSave(){
		global $ST,$post;
		$id=$post->getInt('id');
		$data=array(
			'description'=>$post->get('description'),
			'width'=>$post->getInt('width'),
			'height'=>$post->getInt('height'),
		);
		if($id){
			$ST->update('sc_advertising_place',$data,'id='.$id);
		}else{
			$id=$ST->insert('sc_advertising_place',$data);
		}
		echo printJSON(array('msg'=>'Сохранено','id'=>$id));exit;
	}
	
	function actPlRemove(){
		global $ST,$get;
		$queryStr="delete from sc_advertising_place where id =".$get->getInt("id");
		$ST->executeDelete($queryStr);
		header("Location:.");exit;
	}
	
	function actPlApply(){
		global $post;
		
		$width=$post->getArray('width');
		$height=$post->getArray('height');
		foreach ($width as $id=>$w) {
			$d=array(
				'width'=>$w,
				'height'=>$height[$id],
			);
			DB::update('sc_advertising_place',$d,"id={$id}");
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}	
}
?>