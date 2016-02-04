<?php
class BaseComponent{
	private $user;
	/**
	 * Основные настройки компоненты
	 */
//	protected $tplContainer="";
//	protected $tplComponent="";

//	protected $lastModified=0;
	/**
	 * Работа с БД
	 * 
	 * @var SQL
	 */
	protected $statement=null;
	/**
	 * URI компонеты
	 *
	 * @var string
	 */
	private $uri="";	
	/**
	 * @deprecated 
	 */	
	private $config=array();
	/**
	 * @deprecated 
	 */
	function setConfig($conf){
		$this->config=$conf;
	}
	/**
	 * @deprecated 
	 */
	function getConfig($key){
		$key=strtoupper($key);
		return isset($this->config[$key])?$this->config[$key]:'';
	}
	
	function setComponent($data=array()){
		foreach ($data as $key=>$val){
			if(empty($this->$key)){
				$this->$key=$val;
			}
			
		}
	}
	
	function cfg($key,$val=null){
		global $CONFIG;
		if($val!==null){
			$CONFIG[strtoupper($key)]=$val;
		}else{
			return isset($CONFIG[strtoupper($key)])?$CONFIG[strtoupper($key)]:'';
		}
	}
	
	/**
	 * @return SQL
	 */
	function getStatement(){
		return $this->statement;
	}
	function setStatement($statement){
		$this->statement = $statement;
	}
	
	private $mod_action;
	function setAction($mod_action){
		$this->mod_action=$mod_action;
	}
	function getAction(){
		return $this->mod_action;
	}
	
	function setHeader($header){
		$this->mod_header=$header;
//		$this->mod_name=$header;
	}
	function getHeader(){
		return empty($this->mod_header)?$this->mod_name:$this->mod_header;
	}
		
	function setTitle($title){
		$this->mod_title=$title;
	}
	function getTitle(){
		return $this->mod_title;
	}
	function setDescription($description){
		$this->mod_description=$description;
	}
	function getDescription(){
		return $this->mod_description;
	}
	function setKeywords($keywords){
		$this->mod_keywords=$keywords;
	}
	function getKeywords(){
		return $this->mod_keywords;
	}
	function setPageTitle($title,$url=null){
		$this->explorer[]=array('name'=>$title,'url'=>$url);
		$this->setTitle($title);
		$this->setHeader($title);
	}
	private $ceo=array('title'=>'','header'=>'','keywords'=>'','description'=>'','url'=>'','rule'=>'');
	function setCeo($d){
		foreach ($d as $key=>$val) {
			$this->ceo[$key]=$val;
		}
	}
	function getCeo($key){
		return $this->ceo[$key];
	}
	
	//////////////////////////////////
	// Обеспечение работы компонеты
	//////////////////////////////////
	
	
	function method(){
		/**
		 * Инициализируем компоненту
		 */
	}

	function refreshContainer(){}
	
	/**
	 * URI компоненты
	 *
	 * @return string
	 */
	function getURI($params=array(),$p=null){
		$uri=u2w(urldecode($this->uri));
		if($params){
			foreach ($params as $key=>$val){
				if($val===null){$uri=preg_replace('|('.$key.')/([^/]+/)?|','',$uri);continue;}
				if($val===false){$uri=preg_replace('|'.$key.'/|','',$uri);continue;}
				if(is_int($val)){
					$uri=preg_replace('|'.$key.'/\d*/?|',"$key/$val/",$uri);continue;
				}
				if(preg_match('|/'.$key.'/([^/]+)/|',$uri)){
					$uri=preg_replace('|/('.$key.')/([^/]+)/|','/\1/'.$val.'/',$uri);
				}else{
					$uri.=$key.'/'.$val.'/';
				}
			}
		}
		if($p===true){
			$uri.=($_SERVER['QUERY_STRING']?'?'.$_SERVER['QUERY_STRING']:'');
		}elseif(is_string($p)){
			$uri.='?'.$p;
		}elseif(is_array($p)){
			$uri.='?'.http_build_query($p);
		}
		return $uri;
	}
	
	function getReferer(){
		if(isset($_SERVER['HTTP_REFERER'])){
			return str_replace('http://'.$_SERVER['HTTP_HOST'],'',$_SERVER['HTTP_REFERER']);
		}
		return '';
	}
	
	function getURIVal($key){
		$uri=$this->uri;
		preg_match('|/'.$key.'/([^/]+)|',$this->getURI(),$res);
		return (isset($res[1]))?$res[1]:'';
	}
	function getURIIntVal($key){
		$uri=$this->uri;
		preg_match('|/'.$key.'/([0-9]+)|',$this->getURI(),$res);
		return (isset($res[1]))?intval($res[1]):0;
	}
	function getURIBoolVal($key){
		$uri=$this->uri;
		preg_match('|/'.$key.'\W*|',$this->getURI(),$res);
		return (isset($res[0]))?true:false;
	}
	function getURINumVal($key){
		$uri=$this->uri;
		preg_match('|/'.$key.'/([0-9]+)|',$this->getURI(),$res);
		return (isset($res[1]))?preg_replace('/\D/','',$res[1]):0;
	}
	/**
	 * URI компоненты
	 *
	 * @return string
	 */
	function setURI($uri){$this->uri=$uri;}
	/**
	 *
	 * @param array $user
	 */
	function setUser($user){
		$this->user=$user;
		$_SESSION['TINY_MCE_ADMIN']=$this->isAdmin();//для редактора
	}
	/**
	 * Is Admin
	 *
	 * @return boolean
	 */
	function isAdmin(){
		return in_array($this->getUser('type'),array('admin','supervisor'));	
	}
	function isSu(){
		return in_array($this->getUser('type'),array('supervisor'));
	}
	/**
	 * Пользователь
	 *
	 * @return array
	 */
	function getUser($property=null){
		if($property==null){
			if($this->user)return $this->user;
		}else{
			if(isset($this->user[$property])){
				return $this->user[$property];
			}
		}
		return false;
	}
	/**
	 * Идентификатор пользователя
	 *
	 * @return integer
	 */
	function getUserId(){
		if(isset($_SESSION['_USER']['u_id']))return intval($_SESSION['_USER']['u_id']);
		return 0;
	}
	
	function actExit(){
//		$_SESSION=array();
		session_destroy();
		setcookie(session_name(),null,null,'/');
		header('Location: /'); exit;
	}
	function getText($name){
		$query="SELECT c_text,c_id FROM sc_content WHERE c_name='".$name."'";
		$rs=$this->getStatement()->execute($query);
		$res='';
		if($this->isSu()){
			$res.= '<a href="/admin/content/?act=edit&name='.$name.'" class="coin-text-edit" title="Редактировать"></a>';
		}
		if($rs->next()){
			
			$res.= $rs->get("c_text");
		}
		return $res;
	}
	//Хлебные крошки
	protected $explorer=array();
	function appendExplorer($name,$url){
		$this->explorer[]=array('name'=>$name,'url'=>$url);
	}
	
	function firstExplorer($name,$url){
		$this->explorer=array_merge(array(array('name'=>$name,'url'=>$url)),$this->explorer);
	}
	
	function getExplorer(){
		return $this->explorer;
	}
	
	function renderExplorer($delimitter=' &gt; '){
		$output='';
		$explorer_list=$this->getExplorer();
		if(count($explorer_list)>1){
			foreach($explorer_list as $explorer){
				if($output==''){
					$output= '<strong>'.$explorer['name'].'</strong>'.$output;
				}else{
					$output= '<a href="'.$explorer['url'].'">'.$explorer['name'].'</a>'.$delimitter.$output;
				}
			}
		}
		return $output;
	}
	
	
	function cacheSelect($q,$expire=600){
		global $ST,$cache_time;
		if($expire){
			$t1=microtime(true);
			$cache_dir=ROOT."/cache";
			if(!file_exists($cache_dir)){
				mkdir($cache_dir);
			}
			$cache_file=$cache_dir."/".md5($q).".query";
			if(file_exists($cache_file) && time()-$expire<filemtime($cache_file)){
				$out=unserialize(file_get_contents($cache_file));
				$cache_time+=microtime(true)-$t1;
				return $out;
			}
		}		
		$out=$ST->select($q)->toArray();
		if($expire){
			file_put_contents($cache_file,serialize($out));
		}
		return $out;
	}
	
	function cacheMethod($methodName,$params=null){
		$rs=$this->getStatement()->execute("SELECT * FROM sc_cache WHERE name='$methodName'");
		if($rs->next()){
			$result=unserialize($rs->get('body'));
		}else{
			if($params){
				$result=$this->$methodName($params);
			}else{
				$result=$this->$methodName();
			}
			
			$this->getStatement()->insert('sc_cache',array('name'=>$methodName,'body'=>serialize($result)));
		}
		return $result;
	}
	function cacheMethodClear($methodName,$params=''){
		global $ST;
		$ST->delete('sc_cache',"name='$methodName'");
	}
	
	
	function render($data=array(),$tpl=null,$CONTENT=null){
		if(!$tpl)return;
		foreach ($data as $k=>$v){
			$$k=is_string($v)?my_htmlspecialchars($v):$v;
		}
		ob_start();
		if(method_exists($this,$tpl)){
			$this->$tpl($data);
		}else{
			include($tpl);
		}

		$output= ob_get_contents(); 
		ob_end_clean();
		return $output;
	}
	
	function display($data=array(),$tpl=null){
		if(!$tpl){
			$tpl=$this->tplComponent;
		}
		$this->refreshContainer();
		
		$out=$this->render($data,$this->tplContainer,$this->render($data,$tpl));
		//Вывод
		ob_start(); 
		ob_implicit_flush(0);
		
		echo $out;
		
		global $begin_time,$query_count,$query_time,$query_report;
		$t=microtime(true)-$begin_time;
		?><!-- <?=$t;?> --><!-- <?=$query_count?> <?=$query_time?> --><!-- <?=$t-$query_time?> --><?
		?><!-- <?=(isset($_GET['debug']) && $_GET['debug']=='true')?$query_report:'';?> --><?
		GzDocOut(3,(isset($_GET['debug'])&&$_GET['debug']=='true'));
	}
	
	
	function sendTemplateMail($to,$templateName,$varList=null,$attachments=array()){
		
		$FROM_MAIL=FROM_MAIL;
		
		
		$varList=array_merge($varList,array('FROM_SITE'=>FROM_SITE));
		
		if($mail=$this->cfg("FROM_MAIL")){
			$FROM_MAIL=$mail;
		}
		if(isset($varList['from_mail'])){
			$FROM_MAIL=$varList['from_mail'];
		}
		$mail=new Mail();
		$mail->setTemplate($templateName,$varList);
		$mail->setFromMail($FROM_MAIL);
		
		foreach ($attachments as $a) {
			$mail->addAttachment($a);
		}
		$mail->xsend($to,$varList);
		
	}
	
	function noticeICQ($to,$msg){
		global $ST;
		$ST->insert('sc_notice',array('type'=>'icq','msg'=>$msg,'receiver'=>$to));
//		file_put_contents('icq.txt',"$to $msg\r\n",FILE_APPEND);
	}
	
	function enum($field_name,$field_value=null ){
		global $ST,$ENUM;
		
		if(!empty($ENUM[$field_name])){
			$res=$ENUM[$field_name];
			if($field_value!==null){
				if(isset($res[$field_value])){
					return $res[$field_value];
				}
				return null;
			}
			return $res;
		}
		$res=array();
		$rs=$ST->select("SELECT * FROM sc_enum WHERE field_name='{$field_name}' ORDER BY position");
		while ($rs->next()) {
			$res[$rs->get('field_value')]=$rs->get('value_desc');
			
		}
		$ENUM[$field_name]=$res;
		if($field_value!==null){
			if(isset($res[$field_value])){
				return $res[$field_value];
			}
			return null;
		}
		return $res;
	}

	function enumMsg($field_name,$field_value,$edit=true){
		$res=$this->enum($field_name,$field_value);
		if($this->isAdmin() && $edit){
			$res='<a href="/admin/enum/'.$field_name.'/'.$field_value.'/">E</a>'.$res;
		}
		return $res;
	}
	function fieldExample($name){
		return "{$this->enumMsg('field_examle',$name,false)}";
	}
	
	function visitLog(){
		global $ST;
		// INSERT LOG
		if($this->cfg('SITE_LOG')){
			$ST->insert('sc_loger',array(
				'SESSION_ID'=>session_id(),
				'LOG_TIME'=>now(),
				'USER_ID'=>isset($_SESSION['_USER'])?$_SESSION['_USER']['u_id']:0,
				'HTTP_REFERER'=>isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'',
				'REMOTE_ADDR'=>$_SERVER['REMOTE_ADDR'],
				'USER_AGENT'=>$_SERVER['HTTP_USER_AGENT'],
				'REQUEST_URI'=>$_SERVER['REQUEST_URI'],
				'REQUEST_COMPONENT'=>$this->mod_alias,
			));
		}
		
		// USER_LOG
		if(!empty($_SESSION['_USER']['u_id'])){
			$data=array('last_visit'=>now());
			if(time()-strtotime($this->getUser('last_visit'))>3600*24){
				$data[]="visit=visit+1";
			}
			$ST->update('sc_users',$data,"u_id=".$this->getUserId());
		}
	}
}
?>