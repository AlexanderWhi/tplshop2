<?php
include_once("config.php");

$begin_time=microtime(true);

include_once('core/function.php');

if(isAjaxRequest() && isset($_POST)){
	array_walk_recursive($_POST,'fromUtf');
}elseif(isset($_POST) && get_magic_quotes_gpc()){
	function unSlash(&$item, $key){
		$item = stripslashes ( $item );
	}
	array_walk_recursive($_POST,'unSlash');
}

global $begin_time,$query_report,$query_time,$query_count,$cache_time,$ST,$CONFIG,$get,$post;
$query_report="";
$query_time=0;
$cache_time=0;
$query_count=0;

$get=new HashMap($_GET);
$post=new HashMap($_POST);


define('ADMIN','/admin');

include('session.php');

$URL=parse_url($_SERVER['REQUEST_URI']);

define('MODULE_PATH','modules');
define('CORE_PATH','core/component');

$module='main';
$action=$default_action='Default';
$module_path=MODULE_PATH;

Cfg::add($CONFIG);
/* для обратной совместимости */
$ST=DB::getInstance();


$rs=DB::select("select * FROM sc_enum WHERE field_name='redirect' AND field_value='".SQL::slashes($_SERVER['REQUEST_URI'])."'");
if($rs->next()){
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: {$rs->get('value_desc')}");
	die("Redirect");
}
$isAdminPath=false;
if(0===strpos($URL['path'],ADMIN)){
	$isAdminPath=true;
	$paths=explode('/',str_replace(ADMIN,'',SQL::slashes($URL['path'])));
}else{
	$paths=explode('/',SQL::slashes($URL['path']));
}

$paths_query=array('/');
$path_str='/';

foreach ($paths as $path){
	if(!preg_match('|[0-9a-z]+|i',$path))continue;
	$path_str.=$path;
	$paths_query[]=$path_str;
	$path_str.='/';
	$paths_query[]=$path_str;
}
if(count($paths_query)>1){
	unset($paths_query[0]);
}

if(isset($paths[1]) && $paths[1]){
	$module=strtolower($paths[1]);
}
if(isset($_GET['module']) && $_GET['module']){
	$module=strtolower($_GET['module']);
}
if(isset($paths[2]) && $paths[2]){
	$action=ucfirst($paths[2]);
}
if(isset($_GET['act']) && $_GET['act']){
	$action=ucfirst($_GET['act']);
}


$class_name=ucfirst($module);
if( in_array($module,$CORE) || ($isAdminPath && $module=='main')){
	$module_path=CORE_PATH;
}elseif($isAdminPath){
	$class_name='Admin'.$class_name;
}
	
$class_path=$module_path.'/'.$module.'/'.$class_name.'.class.php';

$alias=($isAdminPath?ADMIN:'').'/'.$module.'/';

$explorer=array();

$componentData=array(
'mod_id'=>0,
'mod_parent_id'=>0,
'mod_name'=>'',
'mod_type'=>0,
'mod_module_name'=>'',
'mod_alias'=>$alias,
'mod_title'=>'',
'mod_description'=>'',
'mod_keywords'=>'',
'mod_content_id'=>0,
'mod_access'=>'',

);

//1 module 2 text 3 link
$path=str_replace(ADMIN,'',$URL['path']);
$cond=" AND '".SQL::slashes($path)."' LIKE CONCAT(mod_alias,'%') AND mod_alias<>'/'";
if($path=='/'){$cond=" AND mod_alias='/'";}
$q="SELECT ".implode(',',array_keys($componentData))." 
	FROM sc_module 
	WHERE mod_type!=2 $cond
	ORDER BY LENGTH(mod_alias) DESC LIMIT 1";

$result=$ST->select($q);
if($result->next()){
	$componentData=$result->getRow();

	$alias=($isAdminPath?ADMIN:'').$result->get('mod_alias');
	
	if($result->getInt('mod_type')==1){//TEXT MODULE
		$module_path=CORE_PATH;
		$class_name=($isAdminPath?'Admin':'').'Component';
		$class_path=$module_path.'/'.$class_name.'.class.php';
	}elseif($result->getInt('mod_type')==0){//MODULE
		$module_path='modules/'.$result->get('mod_module_name');
		$class_name=($isAdminPath?'Admin':'').ucfirst($result->get('mod_module_name'));
		$class_path=$module_path.'/'.$class_name.'.class.php';
	}
	$explorer[]=array('name'=>$result->get('mod_title'),'url'=>$result->get('mod_alias'));
}
	

//CONFIG
$r=DB::select("SELECT UPPER(name) AS name,value FROM sc_config");
while ($r->next()) {
	if(isset($CONFIG[$r->get('name')])){
		 if($r->get('value')){
			$CONFIG[$r->get('name')]=$r->get('value');
		 }
	}else{
		$CONFIG[$r->get('name')]=$r->get('value');
	}
}
Cfg::add($CONFIG);
if(!@include_once($class_path)){
	header("Location: /404/");
	exit;
}
$component=new $class_name();

$component->setURI($URL['path']);
$component->setConfig($CONFIG);
$component->setStatement($ST);
$component->setAction($action);
foreach($component as $name=>$value){
	if(strpos($name,'session_')===0){
		if(isset($_SESSION[$name])){
			$component->$name=&$_SESSION[$name];
		}else{
			$_SESSION[$name]=&$component->$name;
		}
	}
}

$componentData['mod_uri']=$alias;
$component->setComponent($componentData);

if($component->getUserId()){
	$rs=$ST->select("SELECT * FROM sc_users WHERE u_id=".$component->getUserId());
	if($rs->next()){
		$component->setUser($rs->getRow());
	}
}
$component->method();


$action=ucfirst($action);
if(method_exists($component,'act'.$action)){
	$component->{'act'.$action}();
}elseif(method_exists($component,'act'.$default_action)){
	$component->{'act'.$default_action}();
}
?>