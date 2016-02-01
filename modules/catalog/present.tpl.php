<div class="present">
	<?
	$c=$this->getUriIntVal('catalog');
	foreach ($catalog as $item) {
		$url="/catalog/goods/{$item['id']}/".encodestring($item['name']);
		if($c){
			$url="/catalog/{$c}/goods/{$item['id']}/".encodestring($item['name']);
		}
		
		?>
	<div class="item   <?if(isset($in_basket[$item['id']])){?>changed<?}?>">
		<div class="img_name_desc">
			
			<?$img=($item['img']?$item['img']:$this->cfg('NO_IMG'))?>
			<a class="img" title=" <?=htmlspecialchars($item['name'])?>" style="background-image:url('<?=scaleImg($img,'w230h145')?>')"   rel="<?=scaleImg($img,'w420h420')?>"></a>
			<a class="name" <?/*title=" <?=$item['name']?>"*/?> ><?=isset($_GET['search'])?preg_replace('='.preg_quote($_GET['search']).'=i','<span style="color:red">\0</span>',$item['name']):$item['name']?></a>
			<span class="desc">
				<?=nl2br(substr($item['description'],0,100))?>
			</span>
			

		</div>

	</div>
	<?}?>
</div>