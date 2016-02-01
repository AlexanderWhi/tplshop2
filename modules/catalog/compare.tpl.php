<table class="compare">

<tr>
<td></td>
<?foreach ($goods as $k=>$item) {?>
<td class="item">
	<div class="img">
			<?if($item['img']){?>
			<a class="title" title=" <?=htmlspecialchars(trim($item['description']))?>" href="/catalog/goods/<?=$item['id'] ?>/">
			<img src="<?=scaleImg($item['img'],'w180h240')?>" alt=" <?=htmlspecialchars($item['name'])?>">
			</a>
			<?}else{?>
			<!--	<img src="/template/mandarin/img/no_img.jpg"/>-->
			<?}?>
			</div>
			<a class="name" title=" <?=$item['name']?>" href="/catalog/goods/<?=$item['id'] ?>/"><?=$item['name']?></a>
			<div class="price">
				<span class="price"><?=number_format($item['price'],0,',',' ')?> р.</span> 
			</div>
			<a class="compare title" href="?act=removeCompare&id=<?=$item['id'] ?>" title="Убрать из сравнения">&nbsp;</a>
			<a class="add title" href="#" rel="<?=$item['id'] ?>" title="Добавить в корзину">&nbsp;</a>
			</td>
<?}?>



<?foreach ($prop as $item){?>

<tr>
<th colspan="2">
<?=$item['name']?>
</th>
</tr>

<?
$i=0;
foreach ($item['prop'] as $p) {?>
<tr class="<?=(++$i%2)?'odd':'even'?>">
	<td><?=$p['name']?></td>	
	<?foreach ($goods as $k=>$it) {?>
		<td>
			<?if(isset($p['val'][$k])){?>
			<?=str_replace('true','да',$p['val'][$k])?>
			<?}?>		
		</td>
	<?}?>
</tr>
<?}?>





	
<?}?>


</tr>
</table>
<script type="text/javascript" src="/modules/catalog/compare.js"></script>