<?=$this->getText($this->type)?>
<?/*h1 class="header"><span><?=$this->getHeader()?></span></h1*/?>
<?if ($rs){?>
	<div id="awards">
	<?foreach($rs as $row){?>
	<?if(trim($row['img'])){?><img src="<?=scaleImg($row['img'],'w90h90')?>"><?}?>
	
	<div class="item">
		
		<a class="header" href="<?=$this->mod_uri?>view/<?=$row['id']?>/"><?=$row['title']?></a>
		<?if(!empty($row['author'])){?>
		<a href="<?=$row['author']?>" class="author"><?=$row['author']?></a>
		<?}?>
		<div class="description"><?=$row['description']?></div>
		<a href="<?=$this->mod_uri?>view/<?=$row['id']?>/" class="more">Читать отзыв</a>
	</div>
	<?}?>
	</div>
	<div class="page"><?$pg->display()?></div>
<?}else{?>
<div>Список пуст</div>
<?}?>