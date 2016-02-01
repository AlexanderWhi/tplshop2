<?php
include_once("core/component/AdminComponent.class.php");
define('DRIVERS_IMG_PATH','storage/drivers');
class AdminOperator extends AdminComponent {
	
	protected $mod_name='Кабинет оператора';
	protected $mod_title='Кабинет оператора';
		

	function actDefault(){
		$this->actDrivers();
	}
	function actDrivers(){
		global $ST;
		$data['rs']=$ST->select("SELECT * FROM sc_shop_waybill_driver ORDER BY name");
		$this->display($data,dirname(__FILE__).'/admin_drivers.tpl.php');
	}

	function actDriversEdit(){
		global $ST,$get;
		$id=$get->getInt('id');
		$data=array(
			'id'=>$id,
			'name'=>'',
			'phone'=>'',
			'car'=>'',
			'img'=>'',
			'img_car'=>'',
			'state'=>0,
		);
		if($id){
			$queryStr="SELECT ".join(',',array_keys($data))." FROM sc_shop_waybill_driver WHERE id=".$id;
			$rs=$ST->select($queryStr);
			if($rs->next()){
				$data=$rs->getRow();
			}
		}		
		$this->explorer[]=array('name'=>'Редактировать карточку водителя');
		$this->setTitle('Редактировать карточку водителя');
		$this->display($data,dirname(__FILE__).'/admin_drivers_edit.tpl.php');
	}
		
	function actSaveDriver(){
		global $ST,$post,$get;
		$id=$post->getInt('id');
		
		$data=array(
			'name'=>$post->get('name'),
			'phone'=>$post->get('phone'),
			'car'=>$post->get('car'),
			'img'=>$post->get('img'),
			'img_car'=>$post->get('img_car'),
			'state'=>$post->getInt('state'),
		);
			
		if($data['img']=='clear'){
			$data['img']='';
		}elseif(isset($_FILES['img_upload']) && $_FILES['img_upload']['name']){
			$name=md5_file($_FILES['img_upload']['tmp_name']).'.'.file_ext($_FILES['img_upload']['name']);
			$data['img']=$path='/'.DRIVERS_IMG_PATH.'/'.$name;
			move_uploaded_file($_FILES['img_upload']['tmp_name'],ROOT.$path);
			$out['img']=scaleImg($path,'w100h100');
		}
		if($data['img_car']=='clear'){
			$data['img_car']='';
		}elseif(isset($_FILES['img_car_upload']) && $_FILES['img_car_upload']['name']){
			$name=md5_file($_FILES['img_car_upload']['tmp_name']).'.'.file_ext($_FILES['img_car_upload']['name']);
			$data['img_car']=$path='/'.DRIVERS_IMG_PATH.'/'.$name;
			move_uploaded_file($_FILES['img_car_upload']['tmp_name'],ROOT.$path);
			$out['img_car']=scaleImg($path,'w100h100');
		}
		
		if($id){
			$ST->update('sc_shop_waybill_driver',$data,"id=".$id);
		}else{
			$id=$ST->insert('sc_shop_waybill_driver',$data);	
		}
		$out['id']=$id;
		$out['msg']='Сохранено';
		echo printJSONP($out,$get->get('cb'));exit();
	}
	
	function actUpload(){
		global $get;
		$num='';
		if(isset($_FILES['upload']) && $_FILES['upload']['name']){
			$name=md5_file($_FILES['upload']['tmp_name']).'.'.file_ext($_FILES['upload']['name']);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$path);
		}
		if(isset($_FILES['upload1']) && $_FILES['upload1']['name']){
			$num='1';
			$name=md5_file($_FILES['upload1']['tmp_name']).'.'.file_ext($_FILES['upload1']['name']);
			$path='/storage/temp/'.$name;
			move_uploaded_file($_FILES['upload1']['tmp_name'],ROOT.$path);
		}
		echo printJSONP(array('msg'=>'Сохранено','path'=>$path,'num'=>$num),$get->get('cb'));exit;
	}
		
	function actRemove(){		
		global $ST;
		$ST->delete('sc_partner',"id=".intval($_POST['id']));
		echo printJSON(array("id"=>intval($_POST['id'])));exit;
	}
}
?>