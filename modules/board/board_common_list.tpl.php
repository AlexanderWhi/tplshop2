<div class="board">
	<?foreach($rs as $row){?>
	<div class="description" id="description<?=$row['id']?>">
	<small class="dte"><?=fdte($row['time'])?></small>
	<?if($row['c_id']){?><a href="/board/catalog/<?=$row['c_id']?>/">��������� <?=$row['c_name']?></a><?}?> ��������� �� <?=fdte($row['date_to'])?>
	<span class="name"> <?=$row['name']?></span>
						
		<a class="description expand" href="#text<?=$row['id']?>"><?=$row['description']?></a>	
	</div>
	<div class="text" id="text<?=$row['id']?>"><?=$row['text']?></div>
	 
	<?}?>
	</div>