<?
if(true){
	foreach ($_COOKIE as $k=>$v) {
		unset($_COOKIE[$k]);
		setcookie($k,null,null,'/','aptekadiana.ru');
	}
}

?>

test