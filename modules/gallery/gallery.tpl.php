<?=$this->getText(trim($this->mod_uri,'/'))?>

<?if($cat_list){?>
<div class="cat_list">
Отрасли: <a href="<?=$this->mod_uri?>" class="<?if($cat==''){?>act<?}?>">Все</a>
<?foreach ($cat_list as $k=>$d) {?>
	<a href="<?=$this->mod_uri?>cat-<?=$k?>/" class="<?if($cat==$k){?>act<?}?>"><?=$d?></a>
<?}?>
</div>
<?}?>




<?if(!empty($label_list)){?>
<div class="cat_list">
Метка: <a href="<?=$this->mod_uri?>" class="<?if($label==''){?>act<?}?>">Все</a>
<?foreach ($label_list as $k=>$d) {?>
	<a href="<?=$this->mod_uri?>label-<?=$k?>/" class="<?if($label==$k){?>act<?}?>"><?=$d?></a>
<?}?>
</div>
<?}?>

<?if ($rs){?>

<div class="gallery">
	<?foreach ($rs as $item) {?>	
	<div class="item ">
		<a class="img" href="<?=$this->mod_uri?><?=$item['id'] ?>/" title=" <?=$item['name']?>" style="background-image:url(<?=scaleImg($item['img'],'w300')?>)"></a>
		<div class="name_desc">
			<a class="name" title=" <?=$item['name']?>" href="<?=$this->mod_uri?><?=$item['id'] ?>/"><?=$item['name']?></a>
		</div>
	</div><?
	}?>
</div>

	<div class="page"><?$pg->display(1)?></div>
<?}else{?>
<div>Список пуст</div>
<?}?>
