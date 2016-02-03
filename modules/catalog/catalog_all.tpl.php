<?=$this->getText('catalog')?>

<div id="catalog">
<?function __render_left_catalog($catalog,$component){
	?><ul><?
	foreach ($catalog as $item) {
		?> <li class="<?if($component->getUriVal('catalog')==$item['id']){?>act<?}?>"><a href="/catalog/<?=$item['id']?>/"><?=$item['name']?></a><?=$item['mcr']?></li><?
	}
	?></ul><?
}
foreach ($this->getCatalog() as $node) {
	$href="/catalog/{$node['id']}/";
	?><!--
--><div class="item">
<?$img=($node['img']?$node['img']:$this->cfg('NO_IMG'))?>
<a class="img " title=" <?=htmlspecialchars($node['name'])?>" style="background-image:url('<?=scaleImg($img,'w220h140')?>')"  href="<?=$href?>" rel="<?=scaleImg($img,'w420h420')?>"></a>
			
	<a href="<?=$href?>" class="root"><?=$node['name']?></a><?
	if(!empty($node['children'])){
		__render_left_catalog($node['children'],$this);
                ?><a class="more" href="<?=$href?>">Все разделы</a><?
	}?>
	
</div><!--
--><?}?>
</div>


<?if($catalog=$hit_list){?>
<?  include 'catalog_view_table.tpl.php'?>
<?}?>

<?if(false && $manufacturer){?>
<div class="manufacturer">
<h2>Бренды</h2>
<?foreach ($manufacturer as $item) {?><!--
	--><a href="/catalog/manid/<?=$item['id']?>" title="<?=$item['name']?>">
	<img src="<?=scaleImg($item['img'],'h40')?>">
	</a><!--
--><?}?>
</div>
<?}?>