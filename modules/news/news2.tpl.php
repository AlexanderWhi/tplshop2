<?=$this->getText($this->type)?>
<?/*h1 class="header"><span><?=$this->getHeader()?></span></h1*/?>
<?if ($rs){?>
	<div id="news">
	<?foreach($rs as $row){?>
	<?if(trim($row['img'])){?><a class="news_img" href="/<?=$this->type?>/<?=$row['id']?>/" ><img src="<?=scaleImg($row['img'],'w90h90')?>"></a><?}?>
	<div class="item">					
		<span class="date"><?=fdte($row['date'])?></span>
		<a class="header" href="/<?=$this->type?>/<?=$row['id']?>/" title="Подробнее..."><?=$row['title']?></a>
		<div class="description">
		<?=$row['description']?>
		</div>
	
	</div>	
		
	<?}?>
	</div>
	<div class="page"><?$pg->display(1)?></div>
<?}else{?>
<div>Список пуст</div>
<?}?>