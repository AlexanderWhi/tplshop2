<?php
include_once("../config.php");
if(!mysql_connect(DB_HOST,DB_LOGIN,DB_PASSWORD)) {
	exit;
}else{
	mysql_select_db(DB_BASE);
	mysql_query("SET NAMES 'utf8'");
//	mysql_query("SET NAMES 'cp1251'");
}

function unicodeUrlDecode($url, $encoding = "")
{
    if ($encoding == '')
    {
	if (isset($_SERVER['HTTP_ACCEPT_CHARSET']))
	{
	    preg_match('/^\s*([-\w]+?)([,;\s]|$)/', $_SERVER['HTTP_ACCEPT_CHARSET'], $a);
	    $encoding = strtoupper($a[1]);
	}
	else
	    $encoding = 'CP1251'; // default
    }

    preg_match_all('/%u([[:xdigit:]]{4})/', $url, $a);
    foreach ($a[1] as $unicode)
    {
	$num = hexdec($unicode);
	$str = '';

	// UTF-16(32) number to UTF-8 string
	if ($num < 0x80)
	    $str = chr($num);
	else if ($num < 0x800)
	    $str = chr(0xc0 | (($num & 0x7c0) >> 6)) .
		chr(0x80 | ($num & 0x3f));
	else if ($num < 0x10000)
	    $str = chr(0xe0 | (($num & 0xf000) >> 12)) .
		chr(0x80 | (($num & 0xfc0) >> 6)) .
		chr(0x80 | ($num & 0x3f));
	else
	    $str = chr(0xf0 | (($num & 0x1c0000) >> 18)) .
		chr(0x80 | (($num & 0x3f000) >> 12)) .
		chr(0x80 | (($num & 0xfc0) >> 6)) .
		chr(0x80 | ($num & 0x3f));

	$str = iconv("UTF-8", "$encoding//IGNORE", $str);
	$url = str_replace('%u'.$unicode, $str, $url);
    }

    return urldecode($url);
}


if(isset($_GET['q'])){
	//$q=html_entity_decode($_GET['q'], ENT_NOQUOTES,'UTF-8');
	$q=unicodeUrlDecode  ($_GET['q'],"UTF-8");
	$query="SELECT name  FROM ref_region WHERE lower(name) LIKE '$q%' ORDER BY name LIMIT 20 ";
	$query=@mysql_query($query); 
	while (true==($row=@mysql_fetch_array($query)))
	{
//		echo $row['city']."\r\n";
		echo iconv('utf-8','windows-1251',$row['name'])."\r\n";
	}
}
?>