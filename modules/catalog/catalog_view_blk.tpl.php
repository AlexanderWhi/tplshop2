<div class="goods">
	<?
	$i=0;
	$i6=0;
	$c=$this->getUriIntVal('catalog');
			
	
	foreach ($catalog as $item) {
		$url="/catalog/goods/{$item['id']}/".encodestring($item['name']);
		if($c){
			$url="/catalog/{$c}/goods/{$item['id']}/".encodestring($item['name']);
		}
		?><div class="item   <?if(isset($in_basket[$item['id']])){?>changed<?}?>">
		<span class="in_basket">
		<?=isset($in_basket[$item['id']])?$in_basket[$item['id']]:''?>
		</span>
		<div class="img_name_desc">
			<div class="lab">
				<?if($item['old_price'] && $d=round(($item['price']-$item['old_price'])/$item['old_price']*100)){?><span class="discount" title="������"><?=$d?>%</span><?}?>
				<?if($item['sort1']>0){?><span class="act" title="�����"></span><?}?>
				<?if($item['sort2']>0){?><span class="hit" title="��� ������"></span><?}?>
				<?if($item['sort3']>0){?><span class="new">�������</span><?}?>
			</div>
			<?$img=($item['img']?$item['img']:$this->cfg('NO_IMG'))?>
			<a class="img  " title=" <?=htmlspecialchars($item['name'])?>" style="background-image:url('<?=scaleImg($img,'w280h140')?>')"  href="<?=$url?>" rel="<?=scaleImg($img,'w420h420')?>"></a>
			<a class="name" <?/*title=" <?=$item['name']?>"*/?> href="<?=$url?>"><?=isset($_GET['search'])?preg_replace('='.preg_quote($_GET['search']).'=i','<span style="color:red">\0</span>',$item['name']):$item['name']?></a>
			<span class="desc">
				<?/*if($item['in_stock']==0){?>
				<div class="not_in_stock">�������� �� ��������</div>
				<?}*/?>
				<?//=nl2br($item['description'])?>
			</span>
			
			<div class="price">
				
				
				<?if(!empty($item['nmn'])){
					$title='';
					foreach ($item['nmn'] as $nmn) {
						$title.=$nmn['price'].' - '.$nmn['description']."\n";
					}?>
				<span class="price" title="<?=trim($title)?>"> �� <?=price($this->getPrice($item['price']))?></span>
				<a class="add" href="<?=$url?>" title="��������">��������</a>
				<?}else{?>
				<span class="price " <?if($this->isAdmin()){?>title="<?=$item['price']?>"<?}?> > <?=price($this->getPrice($item['price']),$item['weight_flg'])?></span>
				<?if($item['old_price']){?>
				<span class="old_price"><strike><?=price($item['old_price'],$item['weight_flg'])?></strike></span>
				<?}?>
				<?}?>
			</div>
			
		</div>
		<?/*a href="#" class="down"></a><input id="count-<?=$item['id']?>" name="count[<?=$item['id']?>]" value="1" class="count <?=$item['weight_flg']?'weight':'' ?>" /><a href="#" class="up"></a*/?>
		<a class="add" href="javascript:shop.add(<?=$item['id'] ?>)" title="� �������">� �������</a>
	</div><?}?>

</div>