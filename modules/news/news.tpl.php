<?=$this->getText($this->getType())?>
<?if ($rs){?>
	<table id="action">
	<tr>
	<?
	$i=0;
	foreach($rs as $row){?>
	<?if( !($i++%1)){?></tr><tr><?}?>
	<td>
	<?if(trim($row['img'])){?><a href="/action/<?=$row['id']?>/"><img src="<?=scaleImg($row['img'],'w90')?>"></a><?}?>
	<div class="item">					
		<span class="date"><?=fdte($row['date'])?></span>
		<a class="header" href="/action/<?=$row['id']?>/" title="Подробнее..."><?=$row['title']?></a>
		<div class="description"><?=$row['description']?></div>
	</div>
	<?}?>
	</td>
	</tr></table>
	<div class="page"><?$pg->display()?></div>
<?}else{?>
<div>Список пуст</div>
<?}?>