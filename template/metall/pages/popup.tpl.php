<?include('gmetrica.tpl.php')?>

<?if($this->isAdmin()){?>
<div id="ceo-blk">
<a  href="/admin/">Adm</a>
<a id='ceo-open' href="#">CEO</a>
<a class="exit" href="?act=exit">Выход</a>
<!--<a  href="/admin/enum/seo_vars/">CEO переменные</a>-->
</div>
<?}?>
<noindex>
<div id="coin-frame"></div>




<!--#popup_box-->
<div id="popup_box" class="popup_form">					
	<form>
	<a class="close_popup_form" href="#"></a>
	<div class="cont"></div>
	</form>
</div>
<!--#/popup_box-->

<!--#popup_form-->
<div id="call_me_box" class="popup_form">
<?include('modules/contacts/call_me.tpl.php')?>
</div>

<div id="ask_quest_box" class="popup_form">
<?include('modules/contacts/ask_quest.tpl.php')?>
</div>

<!--#/popup_form-->
<?/*include('modules/catalog/shop_popup.tpl.php')*/?>
<?include($this->getTpl('catalog/shop_popup.tpl.php'))?>
</noindex>

<!--goup-->
<!--<a href="#" id="goup">вверх страницы</a>-->
<!--/goup-->
<!--#alert_box-->
<div id="alert_box" class="popup_form white">					
	<form>
		<span class="h1">Сообщение </span>
		<a class="close_popup_form" href="#"></a>
		<span class="msg"></span>
		<a href="#" class="close">продолжить</a>
	</form>
</div>
<!--#/alert_box-->