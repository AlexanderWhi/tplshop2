<?=$this->getText(trim($this->mod_uri,'/').$id)?>
<?/*if($img){?>
<img class="gallery_img" src="<?=scaleImg($img,'w500')?>">
<?}*/?>
<div>
<?=$data['description']?>
<br>

</div>
<div class="gallery_view">
<?if($this->isAdmin()){
	echo '<a href="/admin/'.$type.'/?act=edit&id='.$data['id'].'" class="coin-text-edit" title="Редактировать"></a>';
}?>
<div class="first_block">
<?=$data['text']?>
</div>




<?if($images){?>
<div class="block">
<?foreach ($images as $item) {?>
	<a href="<?=scaleImg($item['img'],'w1000')?>" title="<?=htmlspecialchars($item['description'])?>" rel="gallery" class="img">
	<img src="<?=scaleImg($item['img'],'w810')?>">
	</a>
<?}?>
</div>
<?}?>
</div>

<?/*div class="page"><?$pg->display()?></div*/?>


<?/*div class="gallery">
<a class="more" href="/<?=$type?>/">
<?$desc=array('our_works'=>'работы','gallery'=>'фотоальбомы','clients'=>'клиенты',)?>
Все <?=@$desc[$type]?></a>
</div*/?>
<script src="/colorbox1.5.13/jquery.colorbox-min.js"></script>
<script type="text/javascript"> 
$(function(){
	$('a[rel="gallery"]').colorbox();
});
</script>