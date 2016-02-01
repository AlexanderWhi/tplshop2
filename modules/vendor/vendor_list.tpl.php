<?=$this->getText('/vendor/')?>
<div id="catalog">
<?foreach ($vendorList as $item) {?><div class="item">
	<?$img=($item['avat']?$item['avat']:$this->cfg('NO_IMG'))?>
	<?$href="/vendor/{$item['u_id']}/"?><!--w300h200-->
	<!--w300h200-->
	<a class="img" href="<?=$href?>" style="background-image:url('<?=scaleImg($img,list_val($item['img_format'],array('w300h200','sq300')))?>')"></a>
	<a class="name" href="<?=$href?>"><?=$item['company']?></a>
	
	
	
	<small><a href="/catalog/vendor/<?=$item['u_id']?>/"><?=(int)$item['ci']?> <?=morph($item['ci'],'товар','товара','товаров')?></a></small>
	<small><a href="/catalog/vendor/<?=$item['u_id']?>/"><?=(int)$item['c']?> <?=morph($item['c'],'предложение','предложения','предложений')?></a></small>
	</div><?}?>
</div>