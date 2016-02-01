	<div id="public"><?foreach($rs as $row){
		$img="";if($row['img']){$img=$row['img'];}
		?><div class="item">
		<a class="img" style="background-image:url(<?=scaleImg($img, 'w300')?>)" href="/<?=$row['type']?>/<?=$row['id']?>/" ></a>
		<span class="date"><?=fdte($row['date'])?></span>
		<?/*a class="category" href="/<?=$row['type']?>/category/<?=$row['category']?>/"><?=$row['category_desc']?></a*/?>
		<a class="header" href="/<?=$row['type']?>/<?=$row['id']?>/" title="Подробнее..."><?=$row['title']?></a>
		<div class="description"><?=str_cut($row['description'],100)?></div>
<!--		<a class="more" href="/<?=$row['type']?>/<?=$row['id']?>/" title="Подробнее...">Подробнее</a>-->
	</div><?}?></div>	