<?if($rs=$this->getLastView()){?>
<h2>Вы смотрели</h2>
<div id="last_view">
<a href="#" class="left png"></a>
<a href="#" class="right png"></a>
<ul>
<?foreach ($rs as $item) {?>
	<li>
	<div>
	<?if($item['img']){?>
	<img src="<?=scaleImg($item['img'],'h100')?>">
	<?}?>
	</div>
	<a href="/catalog/goods/<?=$item['id']?>/"><?=$item['name']?></a>
	<strong><?=$item['price']?></strong> руб.
	</li>
<?}?>
</ul>
</div>
<?}?>