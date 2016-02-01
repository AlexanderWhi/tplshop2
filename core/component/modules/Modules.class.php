<?php
include_once("core/component/AdminComponent.class.php");
class Modules extends AdminComponent {
	
	protected $mod_name='Модули';
	protected $mod_title='Модули';

	function actDefault(){
		$data=array(
			'location'=>$this->getLocation(),
			'module'=>$this->_updateModule(),
		);
		
		
		$this->display($data,dirname(__FILE__).'/modules.tpl.php');
	}
	
//	protected $module=array();
	
//	protected $location=array();
	
	private $type=array(1=>'Текст',2=>'Ссылка',0=>'Модуль');

	function getType(){
		$type=$this->type;
		if(!$this->isSu()){
			unset($type[0]);
		}
		return $type;
	}
	function actEdit(){
		global $ST,$get;
		$field=array(
			'mod_id'=>$get->getInt('id'),
			'mod_parent_id'=>$get->getInt('parent'),
			'mod_type'=>1,
			'mod_name'=>'',
			'mod_title'=>'',
			'mod_description'=>'',
			'mod_keywords'=>'',
			'mod_alias'=>'',
			'mod_module_name'=>'',
			'mod_content_id'=>0,
			'mod_state'=>1,
			'mod_location'=>'',
			'mod_access'=>array()
//			'mod_region'=>array()
		);
		$content='';
		if($field['mod_id']){
			$rs=$ST->select("SELECT ".join(',',array_keys($field))." FROM sc_module where mod_id=".$field['mod_id']);
			if($rs->next()){
				$field=$rs->getRow();
//				$field['mod_region']=explode(',',$field['mod_region']);
				$field['mod_access']=explode(',',$field['mod_access']);
				if($field['mod_type']==1 && $field['mod_content_id']){
					$rs=$ST->select("SELECT c_text FROM sc_content WHERE c_id=".$field['mod_content_id']);
					if($rs->next()){
						$content=$rs->get('c_text');
					}
				}
			}
		}
		$field['location']=$this->getLocation();
		$field['type']=$this->getType();
		$field['content']=$content;
		$field['modules']=$this->getModules();
		
		$field['mod_access_list']=$this->enum('u_type');
		
		
		$this->explorer[]=array('name'=>'Редактировать');
//		$this->explorer[]=array('name'=>'Модули','url'=>$this->mod_alias);
		$this->display($field,dirname(__FILE__).'/modules_edit.tpl.php');
	}
	
	function getModules(){
		$result=array();
		$path=ROOT.'/modules';
		if ($handle = opendir($path)) {
			  while (false !== ($file = readdir($handle))) {
			  	if($file!='.' && $file!='..' && is_dir($path.'/'.$file)){
			  		$result[]=$file;
			  	}
			  }
			  closedir($handle);
		}
		return $result;
	}
	
	function getLocation(){
		return $this->enum('mod_location');
	}
	

	
	function _updateModule($parent=0){
		$mod=array();//mod_state<>-1 AND
		$cond="mod_parent_id=".$parent."";
		if(!$this->isSu()){
			$cond.=" AND hide=0";
		}
		
		$query="SELECT mod_id,mod_parent_id,mod_name,mod_module_name,mod_title,mod_alias,mod_state,mod_location,mod_type,mod_access,hide FROM sc_module WHERE  $cond ORDER BY mod_position";
		$rs=DB::select($query);
		while($rs->next()){
			$mod[$rs->get('mod_id')]=$rs->getRow();
			$mod[$rs->get('mod_id')]['ch']=$this->_updateModule($rs->get('mod_id'));
		}
		return $mod;	
	}
	
	function displayModule($module,$deep=0){
		$c=0;
		foreach ($module as $key=>$item){
			?><tr id="module<?=$item['mod_id']?>" class="modules-item" parent="<?=$item['mod_parent_id']?>"><td style="padding-left:<?=$deep*20?>px;">
			<img style="vertical-align:middle" src="/img/pic/apps_16.gif" title="Приложение" alt="Приложение"/>
			<?if($c>0 &&$deep==0){?>
				<a  class="scrooll " href="?id=<?=$item['mod_id']?>"><?=$item['mod_name']?></a><!--(<?=$c?>)-->
			<?}else{?>
				<?=$item['mod_name']?>
			<?}?>
			</td>
			<td><a href="<?=$item['mod_alias']?>"><?=$item['mod_alias']?></a></td>
			<td><?
			$loc=explode('|',$item['mod_location']);
			$locDesc=array();
			foreach ($loc as $l){
				if(isset($this->location[$l])){
					$locDesc[]=$this->location[$l];
				}
			}
			echo join(', ',$locDesc);

			?></td>
			<td style="width:100px">

		<a class="scrooll" href="?act=edit&id=<?=$key?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/></a>
	
		<a class="scrooll" href="?act=edit&parent=<?=$key?>"><img src="/img/pic/add_16.gif" title="Добавить подраздел" alt="Добавить подраздел"/></a>
			
		<a href="#" onclick="return false"><img class="move" src="/img/pic/move_16.gif" title="Переместить" alt="Переместить" /></a>
		
		<a href="?act=hide&id=<?=$key?>"><img src="/img/pic/trash_16.gif" title="Удалить" border="0" alt="" onclick="return confirm('Удалить <?=$item['mod_name']?>?')"/></a>
		<?if($this->isSu()){?>
		<a href="?act=remove&id=<?=$key?>"><img src="/img/pic/trash_16.gif" title="Удалить" border="0" alt="" onclick="return confirm('Удалить <?=$item['mod_name']?>?')"/></a>
		
		<?}?>
			</td></tr><?
			if(isset($item['ch']) && $item['ch']){
				$this->displayModule($item['ch'],$deep+1);
			}
			
		}
	}
	
	
	function actSave(){
		global $ST,$post,$get;		
		/*Сохранение*/
		if(!trim($post->get('mod_name'))){
			echo printJSON(array('msg'=>"Введите название! Сохранение невозможно",'mod_id'=>0,'mod_content_id'=>$post->get('mod_content_id')));exit();
		}
		if($post->get('mod_type')==1){//Текстовка
			if(!trim($post->get('mod_alias'))){
				$post->set('mod_alias','/'.encodestring($post->get('mod_name'))."/");
			}
		}elseif($post->get('mod_type')==0){
			if(!trim($post->get('mod_alias'))){
				$post->set('mod_alias','/'.encodestring($post->get('mod_module_name'))."/");
			}
		}
		
		if(!trim($post->get('mod_alias')) && $post->get('mod_type')!=2){
			echo printJSON(array('msg'=>"Введите псевдоним! Сохранение невозможно",'mod_id'=>0,'mod_content_id'=>$post->get('mod_content_id')));exit();
		}
		
		$content['c_text']=$post->remove('mod_content');
		$post->set('mod_location',implode('|',$post->getArray('mod_location')));
//		$post->set('mod_region',implode(',',$post->getArray('mod_region')));
		$post->set('mod_access',implode(',',$post->getArray('mod_access')));
		
		if($post->get('mod_type')==1){
			
			$content['c_name']=$post->get('mod_alias');
			
			$name=$content['c_name'];
				$i=0;
				while (true) {//если нашли тектовое содержимое с таким названием но другим ид то переименуем согласно алгоритму
					$rs=$ST->select("SELECT * FROM sc_content WHERE c_name='".SQL::slashes($name)."' AND c_id!=".$post->getInt('mod_content_id'));
					if($rs->next()){
						$name=$content['c_name'].'_'.++$i;
					}else{
						break;
					}
					
				}
				$content['c_name']=$name;
			
			
			$post->set('mod_module_name','');//стираем название модуля
			
			
			if($post->get('mod_content_id')){
				$rs=$ST->select("SELECT * FROM sc_content WHERE c_id=".$post->getInt('mod_content_id'));
				if($rs->next()){
					$ST->update('sc_content',$content,'c_id='.$post->getInt('mod_content_id'));
				}else{
					$c_id=$ST->insert('sc_content',$content,'c_id');
					$post->set('mod_content_id',$c_id);
				}
			}else{
				
				
				
				
				$c_id=$ST->insert('sc_content',$content,'c_id');
				$post->set('mod_content_id',$c_id);
			}
		}
		if($post->get('mod_type')==2){
			$post->set('mod_module_name','');
		}
		$id=$post->getInt('mod_id');
		if(!$post->get('mod_state')){
			$post->set('mod_state',1);
		}
		if($id){
			$ST->update('sc_module',$post->get(),"mod_id=".$id);
		}else{
			if($post->get('mod_type')!=2){
				$rs=$ST->select("SELECT * FROM sc_module WHERE mod_alias = '".SQL::slashes($post->get('mod_alias'))."' AND mod_type!=2");
				if($rs->next()){
					echo printJSON(array('msg'=>"Модуль с таким псевдонимом [{$post->get('mod_alias')}] уже существует ! Сохранение невозможно",'mod_id'=>0,'mod_content_id'=>0));exit();
		
				}
			}
				
			if($post->get('mod_id')=='0'){
				$post->remove('mod_id');
			}
			$id=$ST->insert('sc_module',$post->get(),'mod_id');
			$queryStr="UPDATE sc_module set mod_position=mod_id where mod_id=".$id;
			$ST->executeUpdate($queryStr);
		}
		echo printJSON(array('msg'=>'Сохранено','mod_id'=>$id,'mod_content_id'=>$post->get('mod_content_id'),'mod_alias'=>$post->get('mod_alias')));exit();
	}
	
		
	
	function actRemove(){
		global $ST,$get;
		$ST->delete('sc_module','mod_id='.$get->getInt('id'));
		$ST->update('sc_module',array('mod_parent_id'=>0),"mod_parent_id={$get->getInt('id')}");		
		header("Location: .");exit;
	}
	function actHide(){
		global $ST,$get;
		$ST->update('sc_module',array('hide'=>$get->getInt('mode')),'mod_id='.$get->getInt('id'));
		
		header("Location: .");exit;
	}
	
	
	function setPosition(ArgumentList $args){
     	$id=$args->getInt('id');
     	$move=$args->getArgument('move');
     	
     	$rs=$this->getStatement()->execute("select * from sc_module where mod_id=".$id);
     	if($rs->next()){
	     	if($move=="up"){
	     		$this->getStatement()->up('sc_module',$id,"mod_parent_id='".$rs->get("mod_parent_id")."'",'mod_id','mod_position');
	     	}
	     	if($move=="down"){
	     		$this->getStatement()->down('sc_module',$id,"mod_parent_id='".$rs->get("mod_parent_id")."'",'mod_id','mod_position');
	     	}
    	}
    	//Очистим кеш
//		$this->getStatement()->delete('sc_cache',"name='ModuleManager::module'");
    	$this->callSelfComponent();
	}
	
	function actMoveall(){
		global $post,$ST;
		
		$ids=explode(',',trim($post->get('ids'),','));
		$rs=$ST->select("SELECT mod_position FROM sc_module WHERE mod_parent_id=".$post->getInt('parent')." ORDER BY mod_position");
		$i=0;
		while ($rs->next()) {
			$ST->update('sc_module',array('mod_position'=>$rs->getInt('mod_position')),"mod_id=".intval($ids[$i++]));
			
		}
		echo printJSON(array('msg'=>'Перемещено'));exit;
	}
	
	function actMoveTo(){
		global $post,$ST;
		$item=$post->getArray('item');
		$to=$post->getInt('to');
		foreach ($item as $i) {
			if($i!=$to){
				$ST->update('sc_module',array('mod_parent_id'=>$to),"mod_id=".intval($i));
			}
			
		}
		echo printJSON(array('msg'=>'Перемещено'));exit;
	}
}
?>