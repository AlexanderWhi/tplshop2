<?php
include_once("../config.php");
if(!mysql_connect(DB_HOST,DB_LOGIN,DB_PASSWORD)) {
	exit;
}else{
	mysql_select_db(DB_BASE);
//	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET NAMES 'cp1251'");
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
	$q=unicodeUrlDecode  ($_GET['q'],"cp1251");
	$q=trim(strtolower(mysql_real_escape_string($q)));
	$query="SELECT i.name,price 
		FROM sc_shop_item i,
			sc_shop_catalog c
		WHERE 
			i.category=c.id";
		if(isset($_GET['m']) && $_GET['m']=='all'){
			
		}else{
			$query.=" AND price>0 AND in_stock>0";
		}
		$query.=" AND (lower(i.name) LIKE '%$q%' 
				OR lower(c.name) LIKE '%$q%' 
				OR (i.product>0 AND i.product='$q')
				 )
			ORDER BY 
				IF(LOCATE('$q',LOWER(i.name)),LOCATE('$q',LOWER(i.name)),256), 
				i.sort DESC 
			LIMIT 100 ";
	$query=@mysql_query($query); 
	while (true==($row=@mysql_fetch_array($query)))
	{
//		echo iconv('utf-8','windows-1251',"{$row['name']} ")."<b>{$row['price']}</b>ð.\r\n";
//		echo "{$row['name']} <b>{$row['price']}</b>ð.\r\n";
		echo "{$row['name']}|{$row['price']}\r\n";
	}
}
?>