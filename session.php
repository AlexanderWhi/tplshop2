<?
if(!file_exists(dirname(__FILE__).'/tmp')){
	mkdir(dirname(__FILE__).'/tmp',0755);
	file_put_contents(dirname(__FILE__).'/tmp/.htaccess','Deny from all');
}
session_save_path(dirname(__FILE__)."/tmp");
ini_set('session.gc_maxlifetime',3600*24*15);
//ini_set('session.cookie_lifetime',3600*24*15);
if(!empty($_COOKIE['save_cookie']) && $_COOKIE['save_cookie']>time()){
	session_set_cookie_params(3600*24*15, '/');
	setcookie('save_cookie',time()+3600*24*15,COOKIE_EXP,'/');
}

//session_set_cookie_params(3600*24*15, '/');
if(!empty($_COOKIE[session_name()])){
session_start();
}
?>