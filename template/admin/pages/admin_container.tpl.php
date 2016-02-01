<!DOCTYPE html>
<html>
<head>
	<title><?=$this->getTitle()?> - pежим администратора</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"/>
	<link href="/ui-1.11.1/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
	
	<link href="/template/admin/style/default.css" rel="stylesheet" type="text/css"/>
	<link href="/template/admin/style/admin.css" rel="stylesheet" type="text/css"/>
	
	<script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>
<!--	<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>-->
<!--	<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>-->
<!--	<script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>-->
<!--	<script type="text/javascript" src="/js/jquery/jquery-1.6.4.min.js"></script>-->
	
	<script type="text/javascript" src="/ui-1.11.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/js/ui.datepicker-ru.js"></script>
	
	<script type="text/javascript" src="/js/admin/common.js"></script>
	<script type="text/javascript">
		var THEME="<?=$this->cfg('THEME')?>";
		util.referer='<?=@$_SERVER['HTTP_REFERER']?>';
		
	</script>
</head>

<?function renderMenu($node){
					?><ul><?
					foreach($node as $id=>$item){
						?><li style="margin-left:8px;">
						<?if(empty($item['url'])){?>
						<span><?=$item['mod_name']?></span>
						<?}else{?>
						<a href="<?=$item['url']?>"><?=$item['mod_name']?></a>
						<?}?>
						<?
						if(isset($item['children'])){
							renderMenu($item['children']);
						}
						?></li><?
					}
					?></ul><?
				}?>


<body>
<div id="dialog-message" style="display:none" title="Сообщение">
		<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		<span id="dialog-message-text"></span>
</div>

<div id='preloader' style="display:none"><img src="/img/indicator.gif"/>Загрузка данных</div>
<div id="wrap">
		
<div id="logo">Система управления <strong><?=$this->getUser('login')?>[<?=$this->getUser('u_id')?>]</strong></div>

<div id="adm_main_menu">
	<ul>
	
	<li>
	<span>Меню</span>
	<?=renderMenu($this->getMenu())?>
	</li>
	
		<?$top_menu=$this->getTopMenu();
		foreach ($top_menu as $n=>$v){?>
			<li class="<?if($n==count($top_menu)-1){?>end<?}?>"><a href="<?=$v['url']?>"><?=$v['label']?></a></li>
		<?}?>
	</ul>

</div>
<div id="adm_all">

	<?/*div id="adm_left_menu">
		
		
		<?
		$cnt=$this->getNewOrdersCnt();
		$cnt_contacts=$this->getNewContactsCnt();
		
		if($cnt || $cnt_contacts ){?>
		<div id="adm_block_green">
			<?if($cnt  ){?><a href="/admin/shop/?order_status=0">Новые заказы <?=$cnt?></a><br><?}?>
			<?if($cnt_contacts  ){?><a href="/admin/contacts/type/feedback/?status=2">Новые отзывы <?=$cnt_contacts?></a><br><?}?>
		</div>
		<?}?>
		
		
		
		
	</div*/?>
	<div id="tips"><?=$this->renderExplorer()?></div>

	<div id="adm_content">
		<div>
			<div style="width:99%">
				<h2><?=$this->getTitle()?></h2>
				<div id="content"><?=$CONTENT;?></div>
			</div>
		</div>
	</div>
	
</div>

<div id="footer">
	
	<div id="footer_left"><?=$this->cfg('admin_copyrights')?></div>
	<div id="footer_right">
	<?if(isset($_SESSION['_USER']['suser'])){?>
		<a href="?act=switchUser">Переключить пользователя</a>&nbsp;&nbsp;&nbsp;
	<?}?>
	<a href="/admin/users/password/">Сменить пароль</a>&nbsp;&nbsp;&nbsp;<a href="?act=exit" onclick="return confirm('Выйти?')">Выйти</a></div>
</div>

</div>

</body>
</html>