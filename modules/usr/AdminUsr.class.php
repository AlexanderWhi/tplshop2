<?php
include_once 'core/component/AdminListComponent.class.php';
class AdminUsr extends AdminListComponent {
	protected $mod_name='Пользователи';
	protected $mod_title='Пользователи';
	
	protected $status=array('Неактивен','Активен');
	
	
	function actDefault(){
		global $ST;
		parent::refresh();
		
		$cond=" WHERE 1 ";
		if($this->getURIVal('usertype')){
			$cond.=" AND type='".SQL::slashes($this->getURIVal('usertype'))."'";
		}
		
		$query="SELECT count(*) AS c FROM sc_users".$cond ;
		$rs=$this->getStatement()->execute($query);
		if($rs->next()){
			$this->page->all=$rs->getInt('c');
		}
		
		$order='ORDER BY ';
		$ord=$this->getURIVal('ord')!='asc'?'asc':'desc';
		if(in_array($this->getURIVal('sort'),array('login','u_id','status','type','city','company','balance','discount','bonus','visit','last_visit','reg_date'))){
			$order.=$this->getURIVal('sort').' '.$ord;
			
		}elseif($this->getURIVal('sort')=='fio'){
			$order.='last_name '.$ord.',first_name '.$ord.',middle_name '.$ord;
		}else{
			$order.='type,u_id';
		}
		
		
		$queryStr="SELECT * FROM sc_users $cond $order LIMIT ".$this->page->getBegin().",".$this->page->per ;
		$this->rs=$ST->select($queryStr);	
		$data['usertype']=$this->enum('u_type');
		$this->display($data,dirname(__FILE__).'/users.tpl.php');
	}
	
	function actPopup(){
		global $ST;
		parent::refresh();
		
		$cond=" WHERE 1 ";
		if($this->getURIVal('usertype')){
			$cond.=" AND type='".SQL::slashes($this->getURIVal('usertype'))."'";
		}
		
		$query="SELECT count(*) AS c FROM sc_users".$cond ;
		$rs=$this->getStatement()->execute($query);
		if($rs->next()){
			$this->page->all=$rs->getInt('c');
		}
		
		$order='ORDER BY ';
		$ord=$this->getURIVal('ord')!='asc'?'asc':'desc';
		if(in_array($this->getURIVal('sort'),array('login','u_id','status','type','city','company','balance','discount','bonus','visit','last_visit','reg_date'))){
			$order.=$this->getURIVal('sort').' '.$ord;
			
		}elseif($this->getURIVal('sort')=='fio'){
			$order.='last_name '.$ord.',first_name '.$ord.',middle_name '.$ord;
		}else{
			$order.='type,u_id';
		}
		
		
		$queryStr="SELECT * FROM sc_users $cond $order LIMIT ".$this->page->getBegin().",".$this->page->per ;
		$this->rs=$ST->select($queryStr);	
		$data['usertype']=$this->enum('u_type');
		
		$this->tplContainer='core/tpl/admin/admin_popup.php';
		$this->display($data,dirname(__FILE__).'/users_popup.tpl.php');
	}
	

	function onRemove(ArgumentList $args){		
		$q="delete from sc_users where u_id =".$args->getArgument("id");
		$this->getStatement()->executeDelete($q);
		$this->callSelfComponent();
	}	
	
	function actEdit(){
		global $ST,$get;
		$field=array(
		'u_id'=>$get->getInt('id'),
		'login'=>'',
		'status'=>1,
		'name'=>'',
		'company'=>'',
		'last_name'=>'',
		'first_name'=>'',
		'middle_name'=>'',
		'phone'=>'',
		'city'=>'',
		'address'=>'',
		'street'=>'',
		'house'=>'',
		'flat'=>'',
		'porch'=>'',
		'floor'=>'',
		'mail'=>'',
		'avat'=>'',
		'balance'=>0,
		'discount'=>0,
//		'bonus'=>0,
		'type'=>'',
//		'curator'=>0,
//		'region'=>array(),
		'other_info'=>'',
		);
		$other_info=array(
			'inn'=>''
		);
		if($field['u_id']){
			$rs=$ST->select("SELECT ".join(',',array_keys($field))." FROM sc_users WHERE u_id=".$field['u_id']);
			if($rs->next()){
				$field=$rs->getRow();
				
				if($oi=getJSON($field['other_info'])){
					$field=array_merge($field,$oi);
				}
				
				if(in_array($field['type'],array('partner'))){
					$rs=$ST->select("SELECT * FROM sc_partner_data WHERE userid=".$field['u_id']);
					if($rs->next()){
						$field['ank']=$rs->getRow();
						
						
						
						$field['ank']['flowers']=@unserialize($field['ank']['flowers']);
						
					}
				}

			}
		}
		$field['ank_flowers']=$this->enum('ank_flowers');
		$field['groups']=$this->enum('u_type');

		$this->explorer[]=array('name'=>'Редактировать');
		$this->display($field,dirname(__FILE__).'/users_edit.tpl.php');
	}
	
	
	function actSave(){
		global $ST,$post,$get;
		
		$data=array(
		'u_id'=>$post->getInt('u_id'),
		'login'=>$post->get('login'),
		'status'=>$post->get('status'),
		'name'=>$post->get('name'),
		'company'=>$post->get('company'),
//		'inn'=>$post->get('inn'),
//		'kpp'=>$post->get('kpp'),
		'phone'=>$post->get('phone'),
		'city'=>$post->get('city'),
		'address'=>$post->get('address'),
//		'address_jur'=>$post->get('address_jur'),
		'mail'=>$post->get('mail'),
//		'avat'=>$post->get('avat'),
		'balance'=>$post->getFloat('balance'),
		'discount'=>$post->getFloat('discount'),
		'type'=>$post->get('type'),
		);

		$other_info=array(
			'inn'=>$post->get('inn')
		);
		$data['other_info']=printJSON($other_info);
		
		
		$password=$post->remove('password');
		$avat_path=$post->remove('avat_path');

		$id=$post->getInt('u_id');
		
//		if($avat_path && file_exists(ROOT.$avat_path)){
//			$name=md5_file(ROOT.$avat_path).'.'.file_ext($avat_path);
//			$path='/storage/avatar/'.$name;
//			$data['avat']=$path;
//			if(!file_exists(ROOT.$data['avat'])){
//				rename(ROOT.$avat_path, ROOT.$data['avat']);
//			}     
//		}elseif($avat_path=='clear'){
//			$data['avat']='';
//		}
		if($id===0){
			
			$data['password']=md5(trim($password));
			$id=$ST->insert('sc_users',$data);
		}else{
			if(trim($password)){
				$data['password']=md5(trim($password));
			}
			$ST->update('sc_users',$data,'u_id='.$id);
		}
		
		if($data['type']=='partner' && $ank=$post->getArray('ank')){
			$rs=$ST->select("SELECT * FROM sc_partner_data WHERE userid={$id}");
			
			
			
			$ank['flowers']=serialize($ank['flowers']);		
			if($rs->next()){
				$ank_id=$rs->getInt('id');
				$ST->update('sc_partner_data',$ank,"id={$rs->getInt('id')}");
			}else{
				$ank['userid']=$id;
				$ank_id=$ST->insert('sc_partner_data',$ank);
			}
		}
		
		
		if($get->exists('activate')){
			$data['password']=trim($password);
			$data['id']=$ank_id;
			
			$this->sendTemplateMail($data['mail'],'notice_new_company',$data);
	        $this->sendTemplateMail($this->cfg('MAIL'),'notice_new_company',$data);
	        echo printJSON(array('msg'=>'Сохранено и уведомлено','u_id'=>$id));exit();
		}
		echo printJSON(array('msg'=>'Сохранено','u_id'=>$id));exit();
	}

//	function actUpload(){
//		if(isset($_FILES['avat'])){
//			$name=md5_file($_FILES['avat']['tmp_name']).'.'.file_ext($_FILES['avat']['name']);
//			$path='/storage/temp/'.$name;
//			move_uploaded_file($_FILES['avat']['tmp_name'],ROOT.$path);
//			$path=scaleImg($path,'w100h100');
//		}
//		echo printJSONP(array('msg'=>'Сохранено','path'=>$path),'_upload');exit;
//	}
	
	function actPassword(){
		$this->explorer[]=array('name'=>'Сменить пароль');
		$this->setTitle('Сменить пароль');
		$this->display(array(),dirname(__FILE__).'/password.tpl.php');
	}
	function actPasschange(){
		global $ST,$post;
		$ST->executeUpdate("UPDATE sc_users SET password=PASSWORD('".SQL::slashes($post->get('password'))."') WHERE u_id=".$this->getUserId());
		echo printJSON(array('msg'=>'Пароль принят'));exit;
	}
}
?>