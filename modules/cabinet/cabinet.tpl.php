<?//include('menu.tpl.php')?>
<div id="cabinet" class="common_block">
	<?//include('cabinet_menu.tpl.php')?>
	<label>�����: </label><strong><?=$login;?></strong><br/>
	
	<label>���: </label><strong><?=$name?></strong><br/>
	
	<label>�����: </label><strong><?=$street?>, <?=$house?>-<?=$flat?></strong>
	
	<?=trim($porch)?', ������� <strong>'.$porch.'</strong>':''?>
	<?=trim($floor)?', ���� <strong>'.$floor.'</strong>':''?>
	<br/>
				
	<label>�������: </label><strong><?=$phone?></strong><br/>
					
	<label>����������� �����: </label><strong><?=$mail?></strong><br/>

</div>
<br /><br />
