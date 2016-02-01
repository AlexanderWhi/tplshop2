<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?=$this->getTitle()?> - кабинет оператора</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"/>
	<link href="/css/default.css" rel="stylesheet" type="text/css"/>
	<link href="/css/admin.css" rel="stylesheet" type="text/css"/>
	
	<link href="/datepicker/datepicker.css" rel="stylesheet" type="text/css" />
	<link href="/autocomplete/autocomplete.css" rel="stylesheet" type="text/css"/>
	
	<script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/ui/js/jquery-ui-1.8.5.custom.min.js"></script>
	
	<link href="/ui/css/start/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/js/admin/common.js"></script>
	<script type="text/javascript">
		util.referer='<?=@$_SERVER['HTTP_REFERER']?>';
		$(function(){
			$(this).data({SID:'<?=session_id()?>'});	
		});	
	</script>
	

	
</head>
<body>
<div id="dialog-message" style="display:none" title="Сообщение">
		<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		<span id="dialog-message-text"></span>
</div>

<div id='preloader' style="display:none"><img src="/img/indicator.gif"/>Загрузка данных</div>
<div id="wrap">
<div id='loader' style="position: fixed;width:auto;top:0px;right:0px;display:none"><img src="/img/indicator.gif"/> Загрузка данных</div>


		
<div id="logo">Кабинет оператора</div>


<div id="adm_all">

	<div id="adm_left_menu">
		
		<div id="adm_block_blue">
			<div id="adm_blue_lt"></div><div id="adm_blue_rt"></div>
				<h1>Разделы</h1>
				<?=$this->treeMap->render()?>
			<div id="adm_blue_lb"></div><div id="adm_blue_rb"></div>
		</div>
		
	</div>
	<div id="adm_content">
		<div>
			<div style="width:99%">
				<?/*=$this->renderExplorer()?><br/><br*/?>
				
				<h2><?=$this->getTitle()?></h2>
				<div id="content"><?=$CONTENT;?></div>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
</div>
<div class="br_clear"></div>
<div id="footer">
	<div id="footer_left"></div>
	<div id="footer_right">&nbsp;&nbsp;&nbsp;<a href="?method=onExit" onclick="return confirm('Выйти?')">Выйти</a></div>
</div>
</div>
</body>
</html>