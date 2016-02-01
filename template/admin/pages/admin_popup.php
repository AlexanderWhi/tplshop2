<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?=$this->getTitle()?> - pежим администратора</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"/>
	<link href="/template/admin/style/default.css" rel="stylesheet" type="text/css"/>
	<link href="/template/admin/style/admin.css" rel="stylesheet" type="text/css"/>
	<link href="/autocomplete/jquery.autocomplete.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript">
	$(function(){
		$(".grid tr").hover(
			function(){	$(this).addClass("tr_hover");},
			function(){	$(this).removeClass("tr_hover");}
		);
	});
	</script>	
</head>
<body>
<div id="content"><?=$CONTENT;?></div>
</body>
</html>