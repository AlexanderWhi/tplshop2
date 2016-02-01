<?php
include_once("core/component/AdminListComponent.class.php");
class AdminVendor extends AdminListComponent {
	
	protected $mod_name='Поставщики';
	protected $mod_title='Поставщики';
	
	function actDefault(){
		parent::refresh();
		$cond=" type='vendor' ";
		if($this->getURIVal('hidden')!=1){
			$cond.=" AND hide=0";
		}
		$query="SELECT count(*) AS c FROM sc_users WHERE $cond" ;
		$rs=DB::select($query);
		if($rs->next()){
			$this->page->all=$rs->getInt('c');
		}
		
		
		$order='ORDER BY ';
		$ord=$this->getURIVal('ord')!='asc'?'asc':'desc';
		if(in_array($this->getURIVal('sort'),array('login','u_id','status','type','company','balance','discount','bonus','visit','last_visit','reg_date'))){
			$order.=$this->getURIVal('sort').' '.$ord;
			
		}elseif($this->getURIVal('sort')=='fio'){
			$order.='last_name '.$ord.',first_name '.$ord.',middle_name '.$ord;
		}else{
			$order.='type,u_id';
		}
		
		$q="SELECT * FROM sc_users u
			LEFT JOIN sc_users_vendor v ON v.vendor_id=u.u_id
			LEFT JOIN (SELECT COUNT(id) ci,vendor FROM sc_shop_item GROUP BY vendor) AS cci ON cci.vendor=u.u_id
		WHERE $cond $order LIMIT ".$this->page->getBegin().",".$this->page->per;
		$data['rs']=DB::select($q)->toArray();		
		$this->display($data,dirname(__FILE__).'/admin_vendor.tpl.php');
	}
	
	
	function actEdit(){
		global $get;
		$field=array(
		'u_id'=>$get->getInt('id'),
		'login'=>'',
		'status'=>1,
		'name'=>'',
		'company'=>'',
		'phone'=>'',
		'city'=>'',
		'address'=>'',
		'mail'=>'',
		'avat'=>'',
		'img_format'=>1,
		'balance'=>0,
		'discount'=>0,
//		'bonus'=>0,
		'comment'=>'',
		'info'=>'',
		'html'=>'',
		'adm_comment'=>'',
		'images'=>array(),
		);
		if($field['u_id']){
			$rs=DB::select("SELECT * FROM sc_users u
				LEFT JOIN sc_users_vendor v ON vendor_id=u.u_id
			WHERE u_id=".$field['u_id']);
			if($rs->next()){
				$field=$rs->getRow();
				
				if($field['images'] ){
					$field['images']=explode(',',$field['images']);
				}else{
					$field['images']=array();
				}
				
				
			}
		}
		$field['gallery_list']=DB::select("SELECT * FROM sc_gallery WHERE type='gallery'")->toArray();
		
		$field['gallery']=array();
		
		foreach (DB::select("SELECT child FROM sc_relation WHERE type='vend_gal' AND parent={$field['u_id']}")->toArray() as $row) {
			$field['gallery'][]=$row['child'];
		};
		
		$this->explorer[]=array('name'=>'Редактировать');
		$this->display($field,dirname(__FILE__).'/vendor_edit.tpl.php');
	}
	
	
	function actSave(){
		global $post;
		$id=$post->getInt('u_id');
		$data=array(
		'login'=>$post->get('login'),
		
		'name'=>$post->get('name'),
		'company'=>$post->get('company'),
		'phone'=>$post->get('phone'),
		'city'=>$post->get('city'),
		'address'=>$post->get('address'),
		'img_format'=>$post->getInt('img_format'),
		
		'mail'=>$post->get('mail'),
		'balance'=>$post->getFloat('balance'),
		'discount'=>$post->getFloat('discount'),
		'type'=>'vendor',
		);

		$msg='Сохранено';
		$img_out="";
		if(!empty($_FILES['upload']['name']) && isImg($_FILES['upload']['name'])){
			$img=$this->cfg('AVATAR_PATH').'/'.md5($_FILES['upload']['tmp_name']).".".file_ext($_FILES['upload']['name']);
			move_uploaded_file($_FILES['upload']['tmp_name'],ROOT.$img);
			$data['avat']=$img;
			$img_out=scaleImg($img,'w200');
		}
		if($post->getInt('clear')){
			$data['avat']='';
		}
		
		
			
		
		$err=array();
		$rs=DB::select("SELECT * FROM sc_users WHERE login='".SQL::slashes($post->get('login'))."' AND u_id<>$id");
		if($rs->next()){
			$err['login']='Пользователь существует';
		}
		
		if(!$err){
			if($id===0){
				$id=DB::insert('sc_users',$data,'u_id');
			}else{
				DB::update('sc_users',$data,'u_id='.$id);
			}
			
			$rs=DB::select("SELECT * FROM sc_users_vendor WHERE vendor_id=$id");
			$ext_data=array(
				'info'=>$post->get('info'),
				'html'=>$post->get('html'),
				'comment'=>$post->get('comment'),
				'adm_comment'=>$post->get('adm_comment'),
			);
			
			$ext_data['images']=$post->getArray('images');
			
			if($img_pos=$post->getArray('pos')){//Сортировка картинок
					asort($img_pos);
					$temp_img=array();
					foreach ($img_pos as $k=>$v){
						$temp_img[]=$ext_data['images'][$k];
					}
					$ext_data['images']=$temp_img;
			}
	
			foreach ($_FILES['images_upload']['error'] as $k=>$err) {
				if($err!=0){continue;}
				if(isset($_FILES['images_upload']['tmp_name'][$k]) && isImg($name=$_FILES['images_upload']['name'][$k])){
					$path=$this->cfg('AVATAR_PATH').'/'.md5_file($_FILES['images_upload']['tmp_name'][$k]).'.'.file_ext($name);;
					if(!file_exists(ROOT.$path)){
						rename($_FILES['images_upload']['tmp_name'][$k], ROOT.$path);
						
					}
					if(!in_array($path,$ext_data['images'])){
						$ext_data['images'][]=$path;
					}
					
				}
			}
			$ext_data['images']=implode(',',$images=$ext_data['images']);
			
			
			
			if($rs->next()){
				DB::update('sc_users_vendor',$ext_data,"vendor_id=$id");
			}else{
				$ext_data['vendor_id']=$id;
				DB::insert('sc_users_vendor',$ext_data);
			}
			
			DB::delete("sc_relation","type='vend_gal' AND parent=$id");
			foreach ($post->getArray('gallery') as $g) {
				DB::insert('sc_relation',array('type'=>'vend_gal','parent'=>$id,'child'=>$g));
			}
			
			echo printJSONP(array('msg'=>$msg,'u_id'=>$id,'img'=>$img_out,'images'=>$images));exit;
		}else{
			echo printJSONP(array('err'=>$err));exit;
		}
	}
	function actRemove(){
		global $get;
		DB::update('sc_users',array('hide'=>1),"u_id =".$get->get("id"));
		header('Location: .');exit;
	}
	function actRestore(){
		global $get;
		DB::update('sc_users',array('hide'=>0),"u_id =".$get->get("id"));
		header('Location: .');exit;
	}
	
	function actDelete(){
		global $get;
		DB::delete('sc_users',"u_id =".$get->get("id"));
		header('Location: .');exit;
	}	
	function actSend(){
		global $post;
		$item=$post->getArray('item');
		$cat=LibCatalog::getInstance();
		if($item){
			$rs=DB::select("SELECT * FROM sc_users WHERE  u_id IN(".implode(',',$item).")");
			while ($rs->next()) {
				$rs1=DB::select("SELECT * FROM sc_shop_item i,sc_shop_proposal p WHERE vendor={$rs->get('u_id')}
				{$cat->proposalCond()}
				
				")->toArray();

				$goods=$this->render(array('rs'=>$rs1),dirname(__FILE__).'/goods.snd.php');

				$password=' текущий';
				if($post->getInt('with_pass')){
					$password=substr(md5(time().$rs->get('mail')),0,8);
					DB::update('sc_users',array('password'=>md5($password)),"u_id={$rs->get('u_id')}");
				}
				$notice=array(
					'name'=>$rs->get('name'),
					'login'=>$rs->get('login'),
					'password'=>$password,
					'goods'=>$goods,
					'href'=>"http://{$_SERVER['HTTP_HOST']}/"
				);
				$this->sendTemplateMail($rs->get('mail'),'notice_check_vendor',$notice);
			}
			
		}
		echo printJSON(array('msg'=>'Отправлено'));exit();	
		
	}
	
}
?>