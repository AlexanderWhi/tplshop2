<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?=$this->getTitle()?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"/>
	<link href="/css/print.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>
<style media="print"> 
.noprint{display:none}
.pagebreak {page-break-after: always;}
</style>
</head>
<body>
<div class="noprint">
		<input type="button" value="Печать" onclick="window.print()"/>
		<input type="button" value="Закрыть" onclick="window.close()"/>
</div>
<div id="content"><?=$CONTENT;?></div>
<div class="noprint">
		<input type="button" value="Печать" onclick="window.print()"/>
		<input type="button" value="Закрыть" onclick="window.close()"/>
</div>
</body>
</html>