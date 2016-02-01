<?//include('menu.tpl.php')?>
<div id="cabinet" class="common_block">
	<?//include('cabinet_menu.tpl.php')?>
	<label>Логин: </label><strong><?=$login;?></strong><br/>
	
	<label>ФИО: </label><strong><?=$name?></strong><br/>
	
	<label>Адрес: </label><strong><?=$street?>, <?=$house?>-<?=$flat?></strong>
	
	<?=trim($porch)?', подъезд <strong>'.$porch.'</strong>':''?>
	<?=trim($floor)?', этаж <strong>'.$floor.'</strong>':''?>
	<br/>
				
	<label>Телефон: </label><strong><?=$phone?></strong><br/>
					
	<label>Электронная почта: </label><strong><?=$mail?></strong><br/>

</div>
<br /><br />
