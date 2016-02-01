<?php
include_once 'core/component/AdminComponent.class.php';
include_once 'catsrv.properties.php';
include_once 'LibCatsrv.class.php';
function u($str){
	return iconv('cp1251','utf-8',$str);
}
class AdminCatsrv extends AdminComponent {
	protected $mod_name='Каталог сервис';
	protected $mod_title='Каталог сервис';
	
	function actDefault(){
		
		return $this->actFileList();
		
		$data['srv']=array(
//			'extcat'=>'Настройка импорта каталога',
			'extcat1c'=>'Настройка импорта каталога 1c',
			
			'rename'=>'Замена в названии товаров',
			'import'=>'Импорт продукции',
			'export'=>'Експорт продукции',
		);
		
		$this->display($data,dirname(__FILE__).'/admin_catsrv.tpl.php');
	}
		
	private function get1cExtCatalog(){
		$tree=array();
		$dir=CATALOG_DIR;
		$catalog=CATALOG_FILE_NAME;
		//Каталог
		if(file_exists($dir.'/'.$catalog)){
			
			$xml=simplexml_load_file($dir.'/'.$catalog);
			
			function fill_ext_catalog($groups){
				$res=array();
				foreach ($groups->{u('Группа')} as $group) {
					$d=array(
					'id'=>u2w($group->{u('Ид')}),
					'name'=>u2w($group->{u('Наименование')}),
					'ch'=>array(),
					);
					if(!empty($group->{u('Группы')})){
						$d['ch']=fill_ext_catalog($group->{u('Группы')});
					}
					$res[]=$d;
				}
				return $res;
			}
			
			$groups=$xml->{u('Классификатор')}->{u('Группы')};
			$tree=fill_ext_catalog($groups);
		}
		return $tree;
	}
	
	function get1cCatalog(){
		$dir=CATALOG_DIR;
		$catalog=CATALOG_FILE_NAME;
		
		$xml=simplexml_load_file($dir.'/'.$catalog);
		return $xml->{u('Классификатор')}->{u('Группы')};
	}

	
	
	/**
	 * Переместить дочерние узлы в текущий каталог из внешнего каталога
	 *
	 */
	function actMoveSubCat(){
		global $ST,$post,$lnk;
		$lnk=array();
		$ext_id=$post->get('ext_id');
		$id=$post->get('id');
		
		$tree=$this->get1cExtCatalog();
		
		function search_sub_cat($tree,$id){
			
			foreach ($tree as $node) {
			 	if($node['id']==$id){
			 		return $node;
			 	}
			 	if($res=search_sub_cat($node['ch'],$id)){
			 		return $res;
			 	}
			} 
			return null;
		}
		
		
		$tree=search_sub_cat($tree,$ext_id);
		
		function move_sub_cat($tree,$id){
			global $ST,$lnk;
			$res=array();
			
			
			foreach ($tree as $node) {
				
				$rs=$ST->select("SELECT * FROM sc_shop_catalog WHERE parentid=$id AND name='".SQL::slashes($node['name'])."'");
				if(!$rs->next()){
					$data=array(
						'name'=>$node['name'],
						'description'=>$node['name'],
						'parentid'=>$id,
					
					);
					$new_id=$ST->insert('sc_shop_catalog',$data);
					$lnk[$node['id']]=$new_id;
					
					
					$ST->delete('sc_shop_srv_extcat',"id='{$node['id']}'");
					$ST->insert('sc_shop_srv_extcat',array("id"=>$node['id'],'lnk'=>$new_id));
				}else{
					$new_id=$rs->get('id');
					$lnk[$node['id']]=$new_id;
					$ST->delete('sc_shop_srv_extcat',"id='{$node['id']}'");
					$ST->insert('sc_shop_srv_extcat',array("id"=>$node['id'],'lnk'=>$new_id));
				}
				if($node['ch']){
					move_sub_cat($node['ch'],$new_id);
				}
			}
			
		}
		
		move_sub_cat($tree['ch'],$id);
		echo printJSON($lnk);exit;
	}
	
	
	/**
	 * Переместить дочерние узлы в текущий каталог из внешнего каталога
	 *
	 */
	function actMoveToSubCat(){
		global $ST,$post,$lnk;
		$lnk=array();
		$ext_id=$post->get('ext_id');
		$id=$post->get('id');
		
//		$tree=$this->get1cExtCatalog();
		
		$tree=$this->get1cCatalog();
		
		
		function search_sub_cat($tree,$id){
			if(empty($tree->{u('Группа')})){
				return null;
			}
			foreach ($tree->{u('Группа')} as $group) {
			 	if($group->{u('Ид')}==$id){
			 		return $group;
			 	}
			 	if($res=search_sub_cat($group->{u('Группы')},$id)){
			 		return $res;
			 	}
			} 
			return null;
		}
		
		
		$tree=search_sub_cat($tree,$ext_id);
		
		function move_sub_cat($tree,$id){
			global $ST,$lnk;
			$res=array();
			
			
			foreach ($tree->{u('Группа')} as $group) {
				
				$node=array(
					'name'=>u2w($group->{u('Наименование')}),
					'id'=>u2w($group->{u('Ид')}),
				);
				
				$rs=$ST->select("SELECT * FROM sc_shop_catalog WHERE parentid=$id AND name='".SQL::slashes($node['name'])."'");
				if(!$rs->next()){
					$data=array(
						'name'=>$node['name'],
						'description'=>$node['name'],
						'parentid'=>$id,
					
					);
					$new_id=$ST->insert('sc_shop_catalog',$data);
					$lnk[$node['id']]=$id;
					
					
					$ST->delete('sc_shop_srv_extcat',"id='{$node['id']}'");
					$ST->insert('sc_shop_srv_extcat',array("id"=>$node['id'],'lnk'=>$id));
				}else{
					$new_id=$rs->get('id');
					$lnk[$node['id']]=$id;
					$ST->delete('sc_shop_srv_extcat',"id='{$node['id']}'");
					$ST->insert('sc_shop_srv_extcat',array("id"=>$node['id'],'lnk'=>$id));
				}
				if(!empty($group->{u('Группы')})){
					move_sub_cat($group->{u('Группы')},$id);
				}
			}
		}
		
		move_sub_cat($tree->{u('Группы')},$id);
		echo printJSON($lnk);exit;
	}
	
	
	
	
	function actExtCat1c(){
		global $ST;
//		echo 'werwer';
//		exit;
		$lnk=array();
		$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat");
		while($rs->next()){
			$lnk[$rs->get('id')]=$rs->get('lnk');
		}
		
		$dir=CATALOG_DIR;
		$catalog=CATALOG_FILE_NAME;
		
		$xml=simplexml_load_file($dir.'/'.$catalog);
//		$goods_list=$xml->{u('Каталог')}->{u('Товары')}->{u('Товар')};
		$goods_list=$xml->{u('Каталог')}->{u('Товар')};
		$count=array();
		foreach ($goods_list as $data) {
			if(empty($count[u2w($data->{u('Группы')}->{u('Ид')})])){
				$count[u2w($data->{u('Группы')}->{u('Ид')})]=0;
			}
			$count[u2w($data->{u('Группы')}->{u('Ид')})]++;			
		}
		
		$this->setTitle('Настройка импорта каталога 1с');
		$this->explorer[]=array('name'=>'Настройка импорта каталога 1с');
		
		$this->display(array('lnk'=>$lnk,'catalog'=>$xml->{u('Классификатор')}->{u('Группы')},'count'=>$count),dirname(__FILE__).'/admin_catsrv_extcat_1c.tpl.php');
	}
	

	function actFileList(){
		$data=array(
			'list'=>array(),
		);
		$d=opendir(CATALOG_DIR);
		while ($f=readdir($d)) {
			if(is_file(CATALOG_DIR.'/'.$f) && preg_match('/(xml)$/i',$f)){
				$data['list'][]=array('name'=>$f,
				'size'=>filesize(CATALOG_DIR.'/'.$f),
				'time'=>filemtime(CATALOG_DIR.'/'.$f),
				);
			}
			
		}
		$this->display($data,dirname(__FILE__).'/admin_catsrv_file_list.tpl.php');
	
	}
	
	function actUpload(){
		
		if(isset($_FILES['file'])){
			foreach ($_FILES['file']['tmp_name'] as $n=>$tmp_name) {
				move_uploaded_file($tmp_name,CATALOG_DIR.'/'.$_FILES['file']['name'][$n]);
			}
		}
		header('Location: .');exit;
	}
	
	function actRemove(){
		global $get;
		if(file_exists(CATALOG_DIR.'/'.$get->get('name'))){
			unlink(CATALOG_DIR.'/'.$get->get('name'));
		}
		header('Location: .');exit;
	}
	
	function actExtCat(){
		global $ST,$get;
		$lnk=array();
		$cond='';
		$type=4;
		
		if($t=$get->get('type')){
			$type=$t;
		}
		$cond.=" AND offer=$type";
		
		$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat WHERE 1=1 $cond");
		while($rs->next()){
			$lnk[$rs->get('id')]=$rs->getInt('lnk');
		}
				
		$dir=CATALOG_DIR;
		$catalog_file='';
//		$catalog_file=CATALOG_FILE_NAME;
		if($f=$get->get('file')){
			$catalog_file=$f;
		}
		if($type==1){
			$data=CatImp::cat1($dir.'/'.$catalog_file);
		}
		if($type==2){
			$data=CatImp::cat2($dir.'/'.$catalog_file);
		}
		if($type==4){
			$data=CatImp::cat4($dir.'/'.$catalog_file);
		}
		if($type==11){
			$data=CatImp::cat1c1($dir);
		}

		
		$this->setTitle('Настройка импорта каталога тип '.$get->get('type'));
		$this->explorer[]=array('name'=>'Настройка импорта каталога тип '.$get->get('type'));
		/*пока тип файла привяжем к прайсу*/
		$this->display(array('offer'=>$type,'lnk'=>$lnk,'catalog'=>$data['catalog'],'count'=>$data['count'],'file'=>$dir.'/'.$catalog_file),dirname(__FILE__).'/admin_catsrv_extcat.tpl.php');
	}
	
	function actSaveExtCat(){
		global $ST,$post;
		$offer=$post->getInt('offer');
		$lnk=$post->getArray('lnk');
		foreach ($lnk as $k=>$v){
			$cond="offer='$offer' AND id='".u2w($k)."'";
			if(trim($v)){
				
				$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat WHERE $cond" );
				if($rs->next()){
					$ST->update('sc_shop_srv_extcat',array('lnk'=>intval($v)),$cond );
				}else{
					$ST->insert('sc_shop_srv_extcat',array('id'=>u2w($k),'lnk'=>intval($v),'offer'=>$offer) );
				}
			}else{
				$ST->delete('sc_shop_srv_extcat',$cond );
			}
		}
		if($k=$post->get('id')){
			$cond="offer='$offer' AND id='".u2w($k)."'";
			if($v=trim($post->get('val'))){
				
				$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat WHERE $cond" );
				if($rs->next()){
					$ST->update('sc_shop_srv_extcat',array('lnk'=>intval($v)),$cond );
				}else{
					$ST->insert('sc_shop_srv_extcat',array('id'=>u2w($k),'lnk'=>intval($v),'offer'=>$offer) );
				}
			}else{
				$ST->delete('sc_shop_srv_extcat',$cond );
			}
		}
		echo printJSON(array('msg'=>'Сохранено'));exit;
	}
	
	
	function actImport(){
		include_once('LibCatsrv.class.php');
		$this->setTitle('Импорт');
		$this->explorer[]=array('name'=>'Импорт');
		
		$PRICE_EXPR=$this->cfg('SHOP_SRV_PRICE_EXPR');
		$PRICE_EXPR_ACT=$this->cfg('SHOP_SRV_PRICE_EXPR_ACT');
		
		$data['price_expr']=$PRICE_EXPR;
		$data['price_expr_act']=$PRICE_EXPR_ACT;
		
		$data['log']=array();
		
		
		if(file_exists(CATALOG_DIR.'/log.txt')){
			$data['log']=file(CATALOG_DIR.'/log.txt');
			$data['log']=array_reverse($data['log']);
		}

		$this->display($data,dirname(__FILE__).'/admin_catsrv_import.tpl.php');
	}
	
	function actImportCartshop(){
		$data=array();
		$this->display($data,dirname(__FILE__).'/admin_catsrv_import_cartshop.tpl.php');
	}
	function actImpCartshop(){
		set_time_limit(0);
		include_once('LibCatsrv.class.php');
	
		if(!empty($_FILES['import']['tmp_name'])){
			LibCatsrv::importCartshop($_FILES['import']['tmp_name'],$_FILES['import']['name'],$out);
			echo print_r($out,true);
		}
		exit;
	}
	function actTmpImpCartshop(){
		set_time_limit(0);
		global $ST;
		echo "test";
//		exit;
/*
1 = (string:121) http://www.farmcosmetica.ru/image/cache/data/avene/AVENE D-Pigment ЛЕЖЕР Средство для осветления темных пятен-228x228.jpg
*/
		$rs=$ST->select("SELECT * FROM sc_shop_item WHERE img='' ");//AND id=717
		while ($rs->next()) {
			$c=file_get_contents("http://www.farmcosmetica.ru/index.php?route=product/product&product_id={$rs->getInt('id')}");
			$c=u2w($c);
			if(preg_match('|src="(.+)".*id="image"|Um',$c,$res)){
				$img_src=str_replace('cache/','',$res[1]);
				$img_src=str_replace('-228x228','',$img_src);
				
				
				if($img=@get_url(iconv('cp1251','utf-8',$img_src))){
						$img_name=preg_replace('|.*data/|','',$img_src);
						$img_name=str_replace('/','_',$img_name);
						$img_name="storage/catalog/goods/".$img_name;
						if(!file_exists($img_name)){
							file_put_contents($img_name,$img);
						}
						$field_ext['img']="/".$img_name;
					}else{
						$field_ext['img']="";
					}
				$ST->update('sc_shop_item',array('img'=>$field_ext['img']),"id={$rs->getInt('id')}");
			}
		}
	}
	
	function actImp(){
		global $ST,$post,$CONFIG,$get;
		include_once('LibCatsrv.class.php');
		
		$PRICE_EXPR=$this->cfg('SHOP_SRV_PRICE_EXPR');
		$PRICE_EXPR_ACT=$this->cfg('SHOP_SRV_PRICE_EXPR_ACT');
		
		if($post->get('price_expr') && $post->get('price_expr')!=$PRICE_EXPR){
			$PRICE_EXPR=$post->get('price_expr');
			$ST->update('sc_config',array('value'=>$PRICE_EXPR),"name='SHOP_SRV_PRICE_EXPR'");
			$CONFIG['SHOP_SRV_PRICE_EXPR']=$PRICE_EXPR;
		}
		if($post->get('price_expr_act') && $post->get('price_expr_act')!=$PRICE_EXPR_ACT){
			$PRICE_EXPR_ACT=$post->get('price_expr_act');
			$ST->update('sc_config',array('value'=>$PRICE_EXPR_ACT),"name='SHOP_SRV_PRICE_EXPR_ACT'");
			$CONFIG['SHOP_SRV_PRICE_EXPR_ACT']=$PRICE_EXPR_ACT;
		}
		
		if($get->get('mode')=='imp'){//Промежуточный этап
			$msg=LibCatsrv::import('imp',array(1));
			echo printJSON($msg);exit;
		}
		if($get->get('mode')=='upd'){
			$msg=LibCatsrv::import('upd',array(
				'start_time'=>$post->get('start_time'),
				'cnt_imp'=>$post->get('cnt_imp'),
				'cnt_imp_act'=>$post->get('cnt_imp_act'),
			));
			echo printJSON(array('msg'=>$msg));exit;
		}
		echo printJSON(array('msg'=>'Импорт потерпел неудачу'));exit;
	}
	function actImportFile(){
		global $ST,$post;

		$data=LibCatsrv::import($post->get('file'),$post->get('offer'));

		echo printJSON($data);exit;
		
		echo printJSON(array('msg'=>'Импорт потерпел неудачу'));exit;
	}
	
	
	
	function actYML(){
		global $ST,$post,$CONFIG,$get;
		include('LibCatsrv.class.php');
		LibCatsrv::makeYandexYML();
	}
	
	function actImpImg(){
		global $ST;
		
		$loaded=0;
		$unloaded=0;
		
		
		$d=opendir(CATALOG_DIR.'/'.CATALOG_IMG);
		while (FALSE !==($file=readdir($d))) {
			if($file!='.' && $file!='..' && preg_match('/\.(jpg|jpeg|gif|png)$/i',$file)){
//				$product=preg_replace('/\D/','',$file);
				$product=preg_replace('/\.(jpg|jpeg|gif|png)$/i','',$file);
				
				$name=CATALOG_DIR.'/'.CATALOG_IMG.'/'.$file;
				$newName='storage/catalog/goods/'.$file;
				
				$rs=$ST->select("SELECT * FROM sc_shop_item WHERE product='".$product."' AND product>0");
				if($rs->next()){
					$ST->update('sc_shop_item',array('img'=>'/'.$newName),"product='".$product."' AND product>0");
					rename($name,$newName);
					$loaded++;
				}else{
					$unloaded++;
				}
			}
		}
		$result=date('Y-m-d H:i:s')." - Загружено $loaded; Не загружено $unloaded\n";
		file_put_contents(CATALOG_DIR.'/log.txt',$result,FILE_APPEND);
		echo printJSON(array('msg'=>$result));exit;
	}
	function actImpImgZip(){
		global $ST,$post;
		
		$loaded=0;
		$unloaded=array();
		$t=time();
		
		$images=array();
		if(!empty($_FILES['img_zip']['tmp_name']) && preg_match('/\.zip$/i',$_FILES['img_zip']['name'])){
			
			$zip = new ZipArchive;
			if ($zip->open($_FILES['img_zip']['tmp_name']) === TRUE) {
				$img_dir="import/$t";
				if(!file_exists($img_dir)){
					mkdir($img_dir);
				}
				
				$zip->extractTo($img_dir);
				$zip->close();
				$d=opendir($img_dir);
				while ($f=readdir($d)) {
					if(isImg($f) && preg_match('/^(\d+)[_\d]*/',$f,$res)){
						if(empty($images[$res[1]])){
							$images[$res[1]]=array();
						}
						$images[$res[1]][]=$f;
					}
				}
				closedir($d);
				
				$fld='id';
				if(in_array($post->get('link'),array('id','ext_id','product'))){
					$fld=$post->get('link');
				}
				
				foreach ($images as $id=>$imglist) {
					
					$rs=$ST->select("SELECT * FROM sc_shop_item WHERE $fld=$id");
					if($rs->next()){
						$data=array();
						foreach ($imglist as $n=>$i) {
							$file_name=md5($img_dir.'/'.$i).'.'.file_ext($img_dir.'/'.$i);
							$file_path=ltrim("{$this->cfg('CATALOG_PATH')}/goods/$file_name",'/');
							
							if(file_exists($file_path)){
								unlink($file_path);
							}
							rename("$img_dir/$i",$file_path);
							if($n==0){
								$data['img']="/$file_path";
							}else{
								$data['img_add'][]="/$file_path";
							}
						}
						if($data){
							if(!empty($data['img_add'])){
								$data['img_add']=implode(',',$data['img_add']);
							}
							
							$ST->update('sc_shop_item',$data,"id={$rs->getInt('id')}");
							$loaded++;

						}
					}else{
						foreach ($imglist as $n=>$i) {
							unlink("$img_dir/$i");
						}
						$unloaded[]=$id;
					}			
				}
				rmdir($img_dir);
			}
			$result=date('Y-m-d H:i:s')." - Загружено $loaded; Не загружено [".count($unloaded).'] '.implode(', ',$unloaded)."\n";
			file_put_contents(CATALOG_DIR.'/log.txt',$result,FILE_APPEND);
		}else{
			$result=date('Y-m-d H:i:s')." - Файл не загружен ";
		}
		echo printJSONP(array('msg'=>$result));exit;
	}
	
	/*
	
	*/
	
	
	
	function actEmptyLog(){		
		file_put_contents(CATALOG_DIR.'/log.txt','');
		echo printJSON(array('msg'=>"Лог файл очищен"));exit;
	}
	
	function actExport(){
		global $ST;
		$this->setTitle('Експорт');
		$this->explorer[]=array('name'=>'Експорт');
		$data=array(
		'date_from'=>date('d.m.Y'),
		'date_to'=>date('d.m.Y',time()+3600*24),
		);
		
		$data['last_time']='';
		
		$rs=$ST->select("SELECT MAX(time) as t FROM sc_shop_srv_diff");
		if($rs->next()){
			$data['last_time']=$rs->get('t');
		}
		$data['disc_pens']=$this->getConfig('SHOP_SRV_DISC_PENS');
		$this->display($data,dirname(__FILE__).'/admin_catsrv_export.tpl.php');
	}
	
	function actExp(){
		global $ST,$post;
		$queryStr="SELECT *,c.name AS c_name,i.name as i_name,i.id as i_id 
			FROM sc_shop_item_tmp i, sc_shop_catalog c 
			WHERE 
				c.id=i.category 
				AND i.price>0 AND 
				i.in_stock>0 
			ORDER BY c.name,i.name,i.price ";
		$data['rs']=$ST->select($queryStr)->toArray();
		
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=result.xls');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');	
		echo $this->render($data,dirname(__FILE__).'/admin_catsrv_export.xls.php');exit;
	}
	
	function actExp1(){
		global $ST,$post;
		
		$percent=$post->getFloat('percent');
		
		$ST->update('sc_config',array('value'=>$percent),"name='SHOP_SRV_DISC_PENS'");
		
		$data['percent']=$percent;
		$cond="c.id=i.category 
				AND i.price>0 
				AND i.in_stock>0";
		
		$date_from=dte($post->get('date_from'),'Y-m-d');
		$date_to=dte($post->get('date_to'),'Y-m-d');
		
		if($post->exists('sort_print')){
			$cond.=' AND i.sort_print>0';
		}
		
		if($post->exists('changed')){
			$cond.=" AND EXISTS(SELECT d.product FROM sc_shop_srv_diff d WHERE d.product =i.id AND d.time BETWEEN '$date_from' AND '$date_to')";
		}
		
		$queryStr="SELECT *,c.name AS c_name,i.name as i_name,i.id as i_id 
			FROM sc_shop_item i, sc_shop_catalog c 
			WHERE $cond
			ORDER BY c.name,i.name,i.price ";
		$res=$ST->select($queryStr);
		$rs=array();
		while ($res->next()) {
			$row=$res->getRow();
			$row['price1']=$row['price']-($row['price']/100*$percent);
			$row['diff']=$row['price']-$row['price1'];
			$rs[]=$row;
		}
		$data['rs']=$rs;
		
		
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=result.xls');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');	
		echo $this->render($data,dirname(__FILE__).'/admin_catsrv_export1.xls.php');exit;
	}
	
	function actExp2(){
		global $ST,$post;
		
		$pers1=$post->getFloat('pers1');
		$pers2=$post->getFloat('pers2');
		$pers3=$post->getFloat('pers3');
		$pers4=$post->getFloat('pers4');
		$pers5=$post->getFloat('pers5');
		$queryStr="SELECT *,c.name AS c_name,i.name as i_name,i.id as i_id 
			FROM sc_shop_item i, sc_shop_catalog c 
			WHERE c.id=i.category AND i.price>0 AND i.in_stock>0 
			ORDER BY c.name,i.name,i.price 
			";
		$res=$ST->select($queryStr);
		$rs=array();
		while ($res->next()) {
			$row=$res->getRow();
			$row['price1']=$row['price']-($row['price']/100*$pers1);
			$row['price2']=$row['price']-($row['price']/100*$pers2);
			$row['price3']=$row['price']-($row['price']/100*$pers3);
			$row['price4']=$row['price']-($row['price']/100*$pers4);
			$row['price5']=$row['price']-($row['price']/100*$pers5);
			
			$rs[]=$row;
		}
		$data['rs']=$rs;
		
		header('Content-Type: application/vnd.ms-excel;');
        header('Content-Disposition: attachment; filename=result.xls');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');	
		echo iconv('cp1251','utf-8',$this->render($data,dirname(__FILE__).'/admin_catsrv_export2.xls.php'));exit;
	}
	
	function actExp3(){
		global $ST,$post;
		
		$date_from=dte($post->get('date_from'),'Y-m-d');
		$date_to=dte($post->get('date_to'),'Y-m-d');
		
		$queryStr="SELECT *,c.name AS c_name,i.name as i_name,i.id as i_id, d.* 
			FROM sc_shop_item i, sc_shop_catalog c,sc_shop_srv_diff d 
			WHERE c.id=i.category AND d.product=i.id AND d.time BETWEEN '$date_from' AND '$date_to' ORDER BY c.name,i.name ";
		$res=$ST->select($queryStr);
		$rs=array();
		while ($res->next()) {
			$row=$res->getRow();
			$row['price_diff']=$row['price_new']-$row['price_old'];
			$row['percent']=($row['price_new']-$row['price_old'])/$row['price_old']*100;
			
			$rs[]=$row;
		}
		$data['rs']=$rs;
		
		header('Content-Type: application/vnd.ms-excel; charset=windows-1251');
        header('Content-Disposition: attachment; filename=result.xls');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');	
		echo $this->render($data,dirname(__FILE__).'/admin_catsrv_export3.xls.php');exit;
	}
	
	function actClearHistory(){
		global $ST,$post;
		$ST->exec('TRUNCATE TABLE sc_shop_srv_diff');
		echo printJSON(array('msg'=>'история удалена'));exit;
	}
	function actRename(){
		$this->setTitle('Замена в названии товаров');
		$this->explorer[]=array('name'=>'Замена в названии товаров');
		$this->display(array(),dirname(__FILE__).'/admin_catsrv_rename.tpl.php');
	}
	function actDoRename(){
		global $ST,$post;
		$upd=$ST->executeUpdate("UPDATE sc_shop_item SET name=REPLACE(name,'".SQL::slashes($post->get('name'))."','".SQL::slashes($post->get('new_name'))."')");
		echo printJSON(array('msg'=>"Затронуто {$upd} записей"));exit;
	}
	/**
	 * Доставка наценка для стрекозы
	 *
	 */
	function actDelivery(){
		global $ST;
		$rs=$ST->select("SELECT * FROM sc_shop_delivery_loc ORDER BY country,region,city");
		
		$data=array(
			'rs'=>$rs->toArray(),
		);
		$this->display($data,dirname(__FILE__).'/admin_catsrv_delivery.tpl.php');
	}
	
	function actSaveDelivery(){
		global $ST,$post;
		$ST->exec("TRUNCATE TABLE sc_shop_delivery_loc");
		
		$country=$post->getArray('country');
		$region=$post->getArray('region');
		$city=$post->getArray('city');
		$details=$post->getArray('details');
		$price_condition=$post->getArray('price_condition');
		$price=$post->getArray('price');
		$margin=$post->getArray('margin');
		
		
		foreach ($region as $k=>$v){
			$data=array(
				'country'=>$country[$k],
				'region'=>$region[$k],
				'city'=>$city[$k],
				'details'=>$details[$k],
				'price_condition'=>$price_condition[$k],
				'price'=>$price[$k],
				'margin'=>$margin[$k],
			
			);
			$ST->insert('sc_shop_delivery_loc',$data);
		}
		echo printJSON(array('msg'=>'Сохранено')); exit;
	}
}
?>