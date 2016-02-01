<div class="wrapper">
<ul class="sub_menu">
<li><a href="#">О фермерстве</a></li>
<?if($proposal){?><li><a href="#predl">Предложения</a></li><?}?>
<?if($goods){?><li><a href="#vendGoods">Товары</a></li><?}?>
<?if($gallery_list){?><li><a href="#gall">фотогалерея</a></li><?}?>
<li><a href="#fb">Отзывы</a></li>
</ul>

<?if($img_list){?>
<div id="vendor-img">
<div class="slider">
<ul>
<?foreach ($img_list as $i) {?>
	<li>
	<img src="<?=scaleImg($i,'w520')?>">
	</li>
<?}?>

</ul>
</div>
</div>
<?}?>

<?=$data['html']?>

<div style="clear:both"></div>

<?if($catalog=$proposal){?>
<br>
<h2 id="predl">Предложение</h2>
<?include("modules/catalog/catalog_view_table.tpl.php")?>
<?}?>

<?if($catalog=$goods){?>
<br>
<h2 id="vendGoods">Товары</h2>
<?include("modules/catalog/catalog_view_table.tpl.php")?>
<?}?>

<?if($rs=$gallery_list){?>
<h2 id="gall">фотогалерея</h2>
<div class="gallery">
	<?foreach ($rs as $item) {?><div class="item ">
		<a class="img" href="/gallery/<?=$item['id'] ?>/" title=" <?=$item['name']?>" style="background-image:url(<?=scaleImg($item['img'],'w300')?>)"></a>
		<div class="name_desc">
			<a class="name" title=" <?=$item['name']?>" href="/gallery/<?=$item['id'] ?>/"><?=$item['name']?></a>
		</div>
	</div><?}?>
</div>

<?}?>
</div>

<div class="blk1 vendor_comment">
<div class="wrapper">
<h2 id="fb">Отзывы</h2>
<a class="but" href="#goods-fb-form">Оставить свой отзыв</a><br><br>
<?include("modules/comment/comment2.tpl.php")?>
</div>
</div>