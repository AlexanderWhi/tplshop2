<?php
require_once('lib/str.php');
require_once('lib/Autoloader.class.php');
Autoloader::Register();

//include_once('lib/Cookie.class.php');
//include_once('lib/Mail.class.php');
//include_once('lib/Page.class.php');
//include_once('lib/HashMap.class.php');
//include_once('lib/Cfg.class.php');
//include_once('lib/DB.class.php');
//include_once('lib/SQL.class.php');
function CheckCanGzip(){ 
    if (headers_sent() || connection_aborted()) return 0;
    if (isset ($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'],  'x-gzip') !== false) return "x-gzip"; 
    if (isset ($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) return "gzip"; 
    return 0; 
}
/* $level = compression level 0-9,  0=none,  9=max */
function GzDocOut($level=3, $debug=0){
    $ENCODING = CheckCanGzip(); 
    if ($ENCODING){
        print "\n<!-- Use compress $ENCODING -->\n"; 
        $Contents = ob_get_contents(); 
        ob_end_clean(); 
        if ($debug){
        	$t1=microtime(true);
        	
            $s = "<center><font style='color:#C0C0C0; font-size:9px; font-family:tahoma'>Not compress length: ".strlen($Contents).";  "; 
            $s .= "Compressed length: ".
                   strlen(gzcompress($Contents, $level));
                 
            $time=microtime(true)-$t1;
			$s .=' time:'.$time;   
                   
            $s .=  "</font></center>"; 
            $Contents .= $s; 
        }
        header("Content-Encoding: $ENCODING"); 
        print "\x1f\x8b\x08\x00\x00\x00\x00\x00"; 
        $Size = strlen($Contents); 
        $Crc = crc32($Contents); 
        $Contents = gzcompress($Contents, $level); 
        $Contents = substr($Contents,  0,  strlen($Contents) - 4); 
        print $Contents; 
        print pack('V', $Crc); 
        print pack('V', $Size); 
        
    }else{
        ob_end_flush(); 
        
    }
}
//function __autoload($className) { 
//	include_once 'core/lib/'.$className . '.class.php' ;
//}
function isAjaxRequest(){
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest';
}
function toUtf(&$item, $key){
	$item=stripcslashes(iconv('windows-1251','utf-8',$item));
}
function fromUtf(&$item, $key){
	$item=iconv('utf-8','windows-1251',$item);
	if(get_magic_quotes_gpc()){
		$item = stripslashes ( $item );
	}
}
function printJSON($data){
	if(is_string($data)){
		$data=iconv('windows-1251','utf-8',$data);
	}elseif(is_array($data)){
		array_walk_recursive($data,'toUtf');
	}
	return json_encode($data);
}

function getJSON($str){
	if($out=@json_decode($str,true)){
		array_walk_recursive($out,'fromUtf');	
	}
	return $out;
}
function printJSONP($data,$cb='_cb'){
	if(isset($_GET['_cb'])){
		$cb=$_GET['_cb'];
	}
	return '<html><body><script type="text/javascript">parent.'.$cb.'('.printJSON($data).')</script></body></html>';
}	

function execTime(){
	global $query_time,$begin_time;
	$t2=explode(' ',microtime());
	$t=($t2[0]+$t2[1])-($begin_time[0]+$begin_time[1]);
	return array($t,$query_time,$t-$query_time);
}
function check_mail($mail){
	return preg_match('/^[\da-z](([\da-z\_\.\-]+)*)@([\da-z]+)(([a-z\d\.\-]+)*)\.([a-z])+$/i', $mail);
	return preg_match('/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z])+$/', $mail);
}
function scaleImg($img,$params){
	if(preg_match('!(jpe?g|png|gif)!i',$params,$res)){
		$params=str_replace($res[1],file_ext($img),$params);
		$img=preg_replace('/.(\w+)$/','.'.$res[1],$img);
	}
	return preg_replace('!([^/]+)\.(jpe?g|png|gif)$!i','.thumbs/\1_'.$params.'.\2',$img);
//	return preg_replace('!\.(jpg|jpeg|png|gif)$!i','.'.$params.'.\1',$img);
}
function list_val($val,$list=array(),$default=null){
	if(isset($list[$val])){
		return $list[$val];
	}
	return $default;
}
function isImg($f){
	return preg_match('/(jpe?g|png|gif)$/i',$f)!==0;
}
function isDoc($f){
	return preg_match('/(txt|docx?|pdf)$/i',$f)!==0;
}


function file_ext($path){
	return preg_replace('!.+\.([^\.]+)$!','\1',$path);
}
function now(){
	return date('Y-m-d H:i:s');
}
define('DTE_FORMAT_SQL','Y-m-d');
function dte($date,$format='d.m.Y'){
	if(is_int($date)){
		return date($format,$date);
	}elseif($date=strtotime($date)){
		return date($format,$date);
	}
	return '';
}
function fdte($date){
	global $month_rus_by;
	if(preg_match('/\.(\d+)\./',$d=dte($date),$r)){
		$d=str_replace(".{$r[1]}."," {$month_rus_by[$r[1]]} ",$d);
	}
	return strtolower($d);
}
function fdte1($date){
	global $month_rus_by;
	if(preg_match('/\.(\d+)\./',$d=dte($date),$r)){
		$d=preg_replace("/\.{$r[1]}\.\d+/"," {$month_rus_by[$r[1]]} ",$d);
	}
	return strtolower($d);
}
function u2w($s){
	return @iconv('utf-8','windows-1251',(string)$s);
}
function extract_files($file){
	$zip = new ZipArchive;
	if ($zip->open($file) === TRUE) {
		$zip->extractTo(dirname($file));
		$zip->close();
		return true;
	} else {
		return false;
	};
}

function removeDirectory($dir) { 
    if ($objs = glob($dir."/*")) { 
       foreach($objs as $obj) { 
         is_dir($obj) ? removeDirectory($obj) : unlink($obj); 
       } 
    } 
    rmdir($dir); 
}

function price($price=0,$lab='Р'){
	$labArr=array(
//		'0'=>'Р/шт',
		'0'=>'Р',
		'1'=>'Р/кг',
		'2'=>'Р/г',
		'3'=>'Р/л',
	);
	if(isset($labArr[$lab])){
		$lab=$labArr[$lab];
	}
	return preg_replace('/,00$/','',number_format($price,2,',',' '))." <small>$lab</small>";
}
function price2($price=0,$lab='р.'){
	return preg_replace('/,00$/','',number_format($price,2,'.',' '))." $lab";
}

/* дубликат функции price, где вместо "руб." прописаны упаковки */
function ext_price($price=0,$lab='уп.'){
	return preg_replace('/,00$/','',number_format($price,2,',',' '))." $lab";
}

function str_cut($str,$lim=32){
	return preg_replace('/^(.{'.$lim.'}).*/','\1...',$str);
}
function get_url($url) 
{ 
   global $_CURLOPT;
	$r = curl_init(); 
   curl_setopt($r, CURLOPT_NOPROGRESS, 0); 
   curl_setopt($r, CURLOPT_RETURNTRANSFER, 1); 

   curl_setopt($r, CURLOPT_URL, $url); 
 if(!empty($_CURLOPT['PROXY'])){
 	curl_setopt($r, CURLOPT_PROXY, $_CURLOPT['PROXY']);
 }if(!empty($_CURLOPT['PROXYUSERPWD'])){
 	curl_setopt($r, CURLOPT_PROXYUSERPWD, $_CURLOPT['PROXYUSERPWD']);
 }
   $res = curl_exec($r); 
   return $res; 
}

function my_session_start($save=true){
	if(!session_id()){
		if($save){
			setcookie('save_cookie',time()+3600*24*15,COOKIE_EXP,'/');
			session_set_cookie_params(3600*24*15, '/');
		}
		session_start();
	}
}
?>