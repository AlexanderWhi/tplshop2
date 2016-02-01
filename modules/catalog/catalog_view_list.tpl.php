<div class="goods_list">
	<?
	$c=$this->getUriIntVal('catalog');
	foreach ($catalog as $item) {
		$url="/catalog/goods/{$item['id']}/".encodestring($item['name']);
		if($c){
			$url="/catalog/{$c}/goods/{$item['id']}/".encodestring($item['name']);
		}
		?>
	<div class="item  <?if(isset($in_basket[$item['id']])){?>changed<?}?>">
		
			<div class="lab">
				<?if($item['old_price'] && $d=round(($item['price']-$item['old_price'])/$item['old_price']*100)){?><span class="discount" title="Скидка"><?=$d?>%</span><?}?>
				<?if($item['sort1']>0){?><span class="act" title="Акция"></span><?}?>
				<?if($item['sort2']>0){?><span class="hit" title="Хит продаж"></span><?}?>
				<?if($item['sort3']>0){?><span class="new">Новинка</span><?}?>
			</div>
			<?$img=($item['img']?$item['img']:$this->cfg('NO_IMG'))?>
			<a class="img <?if($this->cfg('SHOP_SCALE_IMAGE')=='true'){?>image<?}?>" title=" <?=htmlspecialchars($item['name'])?>" style="background-image:url('<?=scaleImg($img,'w200h200')?>')"  href="<?=$url?>" rel="<?=scaleImg($img,'w420h170')?>"></a>
		
		<div class="name_desc_price">
			<a class="name" <?/*title=" <?=$item['name']?>"*/?> href="<?=$url?>"><?=isset($_GET['search'])?preg_replace('='.preg_quote($_GET['search']).'=i','<span style="color:red">\0</span>',$item['name']):$item['name']?></a>
			
			<span class="desc">
			<?if($item['r']){?><div class="rait r<?=round($item['r'])?>"></div><?}?>
				<?/*if($item['in_stock']==0){?>
				<div class="not_in_stock">временно не продаётся</div>
				<?}*/?>
				<?=nl2br(substr($item['description'],0,100))?>
			</span>
			
			
			<span class="price " <?if($this->isAdmin()){?>title="<?=$item['price']?>"<?}?> > <?=price($this->getPrice($item['price']))?></span>
				<?if($item['old_price']){?>
				<span class="old_price"><strike><?=price($item['old_price'])?></strike></span>
				<?}?>
			
		</div>
		<a class="add" href="javascript:shop.add(<?=$item['id'] ?>,1)" title="Заказать">Заказать</a>
			
	</div>
	<?}?>
</div>