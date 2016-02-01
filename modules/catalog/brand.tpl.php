<div class="brand">
	<?foreach ($rs as $item) {?><!--
	--><div class="item ">
		<div class="img_name_desc">
			<?$img=($item['img']?$item['img']:$this->cfg('NO_IMG'))?>
			<a class="img" title=" <?=htmlspecialchars($item['name'])?>" style="background-image:url('<?=scaleImg($img,'h70')?>')" href="/catalog/manid/<?=$item['id']?>/"></a>
<!--			<a class="name"  href="/catalog/manid/<?=$item['id']?>/"><?=$item['name']?>&nbsp;</a>-->
			<div class="desc">
				<?=$item['description']?>
			</div>
			

		</div>

	</div><!--
	--><?}?>
</div>