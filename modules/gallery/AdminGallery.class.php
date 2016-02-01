<?
include_once("core/component/AdminListComponent.class.php");
class AdminGallery extends AdminListComponent {			
	function actDefault(){
		global $ST;
		parent::refresh();
		
		$cond="type='{$this->getType()}'";
		if($cat=$this->getURIIntVal('category')){
			$cond.=" AND cat=$cat";
		}
		if($label=$this->getURIIntVal('label')){
			$cond.=" AND EXISTS (SELECT gallery_id FROM sc_gallery_label WHERE gallery_id=g.id AND label_id=$label)";
		}

		$q="SELECT count(*) AS c FROM sc_gallery g WHERE $cond";
		$rs=$ST->select($q);
	
		if($rs->next()){
			$this->page->all=$rs->getInt("c");
		}

		$order='ORDER BY ';
		$ord=$this->getURIVal('ord')!='asc'?'asc':'desc';
		if(in_array($this->getURIVal('sort'),array('name','id','sort','sort_main','cat','img','date'))){
			$order.=$this->getURIVal('sort').' '.$ord;
		}else{
			$order.='id DESC';
		}
		
//		$order="ORDER BY sort DESC";

		$q="SELECT g.*,c.value_desc AS cat_desc FROM sc_gallery g
		LEFT JOIN (SELECT field_value,value_desc FROM sc_enum where field_name='gal_{$this->getType()}_cat') AS c ON c.field_value=g.cat
		
		WHERE $cond $order limit ".$this->page->getBegin().",".$this->page->per;
		$this->rs=$ST->select($q);
		$ids=array();
		while ($this->rs->next()) {
			$ids[]=$this->rs->getInt('id');
		}$this->rs->reset();
		$labels=array();
		if($ids){
			$rs=$ST->select("SELECT * FROM sc_gallery_label WHERE gallery_id IN (".implode(',',$ids).")");
			while ($rs->next()) {
				if(empty($labels[$rs->get('gallery_id')])){
					$labels[$rs->get('gallery_id')]=array();
				}
				$labels[$rs->get('gallery_id')][]=$rs->get('label_id');
			}
		}
		$data=array(
			'cat_list'=>$this->enum("gal_{$this->getType()}_cat"),
			'label_list'=>$this->enum("gal_{$this->getType()}_label"),
			'labels'=>$labels,
		);
		
		$this->display($data,dirname(__FILE__).'/gallery_list.tpl.php');
	}
	
	function getType(){
		return $this->getURIVal('admin');
	}
	
	function actEdit(){
		global $ST,$get;		
		$field=array(
			'id'=>0,
			'name'=>'',
			'sort'=>0,
			'img'=>'',
			'img_format'=>0,
			
			'description'=>'',
			'text'=>'',
			
			'cat'=>'',
			'date'=>date('Y-m-d'),
			'type'=>$this->getType(),
		);
		
		if($id=$get->getInt('id')){
			$q="SELECT ".join(',',array_keys($field))." FROM sc_gallery WHERE id =".$id;
			$rs=$ST->select($q);
			if($rs->next()){
				$field=$rs->getRow();
			}
		}
		$field['label']=array();
		$rs=$ST->select("SELECT * FROM sc_gallery_label WHERE gallery_id={$id}");
		while ($rs->next()) {
			$field['label'][]=$rs->getInt('label_id');
		}
				
				
		$images=array();
		$rs=$ST->select("SELECT * FROM sc_gallery_img WHERE gallery_id={$id} ORDER BY pos");
		while ($rs->next()) {
			$images[]=$rs->getRow();
		}
		$field['images']=$images;
		
		$field['cat_list']=$this->enum("gal_{$field['type']}_cat");
		$field['format_list']=$this->enum("gal_{$field['type']}_format");
		$field['label_list']=$this->enum("gal_{$field['type']}_label");
		
		$this->explorer[]=array('name'=>'Редактировать');
		
		$tpl=dirname(__FILE__).'/'.$field['type'].'_edit.tpl.php';
		if(file_exists($tpl)){
			$this->display($field,$tpl);return;
		}
		$this->display($field,dirname(__FILE__).'/gallery_edit.tpl.php');
	}
	
	function actReload(){
		global $get;
		$id=$get->getInt('id');
		
		$path=$this->cfg('GALLERY_PATH').'/'.$id;
		$d=opendir(ROOT.$path);
		$files=array();
		while ($f=readdir($d)) {
			if(isImg($f)){
				$files[]=$path.'/'.$f;
			}
		}
		closedir($d);
		echo printJSON($files); exit;
	}
	
	function actSave(){
		global $ST,$get,$post;
		$id=$post->getInt('id');
		
		$data=array(
			'name'=>$post->get('name'),
			'img_format'=>$post->get('img_format'),
			'sort'=>$post->getInt('sort'),
			'description'=>$post->get('description'),
			'text'=>$post->get('text'),
			'type'=>$post->get('type'),
//			'point'=>$post->get('point'),
			'cat'=>$post->get('cat'),
			'date'=>$post->getDate('date'),
		);

		
		
			
		if($id){
			$ST->update('sc_gallery',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_gallery',$data);	
		}
		$d['img']=$post->get('img');
		
		$dir=$this->cfg('GALLERY_PATH').'/'.$id;
		if(!file_exists(ROOT.$dir)){
			mkdir(ROOT.$dir);
		}
		
		if(!empty($_FILES['img_upload']['tmp_name']) && isImg($name=$_FILES['img_upload']['name'])){
			$path=$dir.'/'.md5_file($_FILES['img_upload']['tmp_name']).'.'.file_ext($name);
			if(!file_exists(ROOT.$path)){
				rename($_FILES['img_upload']['tmp_name'], ROOT.$path);
				$d['img']=$path;
			}
		}
		//---
		$i=$post->getArray('images');
		$desc=$post->getArray('desc');
		$format=$post->getArray('format');
		
		$images=array();
		
		$img_all=array();
		
		if($img_pos=$post->getArray('pos')){//Сортировка картинок
				asort($img_pos);
				foreach ($img_pos as $k=>$v){
					
					if(!in_array($i[$k],$img_all)){
						$img_all[]=$i[$k];
						$images[]=array(
							'img'=>$i[$k],
							'description'=>$desc[$k],
							'format'=>$format[$k],
						);
					}	
				}
		}

		foreach ($_FILES['images_upload']['error'] as $k=>$err) {
			if($err!=0){continue;}
			if(isset($_FILES['images_upload']['tmp_name'][$k]) && isImg($name=$_FILES['images_upload']['name'][$k])){
//				$path=$dir.'/'.encodestring($name);
				$path=$dir.'/'.md5_file($_FILES['images_upload']['tmp_name'][$k]).'.'.file_ext($name);
//				$i=0;
//				while (file_exists(ROOT.$path)) {
//					$path=$dir.'/'.++$i.'_'.encodestring($name);
//				}
				if(!file_exists(ROOT.$path)){
					rename($_FILES['images_upload']['tmp_name'][$k], ROOT.$path);
					
				}
				if(!in_array($path,$img_all)){
						$img_all[]=$path;
					$images[]=array(
						'img'=>$path,
						'description'=>'',
						'format'=>0,
					);
				}
			}
		}
		
		
		$ST->update('sc_gallery',$d,"id=".$id);
		
		$ST->delete('sc_gallery_img',"gallery_id=$id");
		foreach ($images as $n=>$i) {
			$ST->insert('sc_gallery_img',$i+array('gallery_id'=>$id,'pos'=>$n+1));
		}
		$ST->delete('sc_gallery_label',"gallery_id=$id");
		foreach ($post->getArray('label') as $i) {
			$ST->insert('sc_gallery_label',array('gallery_id'=>$id,'label_id'=>$i));
		}		
		echo printJSONP(array('msg'=>'Сохранено','id'=>$id,'images'=>$images,'img'=>$d['img']));
	}
	
	function galHtml($src){
		$r="";	
		if(preg_match('/\.(jpg|png|gif)$/i',$src)){	
			$r.='<img src="'.$src.'"  style="margin:0;border:0"/>';
		}				
		return $r;
	}
	function actUpload(){
		if(isset($_FILES['upload'])){
			$name=md5_file($_FILES['upload']['tmp_name']).'.'.substr($_FILES['upload']['name'],-3);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$path);
			$html=$this->galHtml($path);
		}
		echo printJSONP(array('msg'=>'Сохранено','path'=>$path,'html'=>$html));exit;

	}
	
		
	function actRemove(){		
		global $ST;
		$q="DELETE FROM sc_gallery WHERE id=".intval($_POST['id']);
		$ST->executeDelete($q);
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
	
	function actApply(){
		global $ST,$post;
		$sort_main=$post->getArray('sort_main');
		$sort=$post->getArray('sort');
		foreach ($sort as $k=>$v) {
			$d=array(
				'sort'=>$v,
				'sort_main'=>$sort_main[$k],
			);
			$ST->update('sc_gallery',$d,"id=$k");
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
}
?>