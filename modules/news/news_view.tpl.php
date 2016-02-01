<div class="news_view">
<?if($img){?>
	<img src="<?=scaleImg($img,'w170')?>" class="news_img">
<?}?>
<?if($this->isAdmin()){
	echo '<a href="/admin/'.$type.'/?act=edit&id='.$data['id'].'" class="coin-text-edit" title="Редактировать"></a>';
}?>
<?if($type!='article'){?>
<div class="date"><?=dte($date)?></div>
<?}?>
<?=$data['content']?>
</div>

<?if($gallery_img_list){?>
<div class="news_gallery">
<?foreach ($gallery_img_list as $gallery_img) {?>
	<div class="img" style="background-image:url(<?=scaleImg($gallery_img,'sq280')?>)"></div>
<?}?>
<div class="more">
<a href="/gallery/<?=$gallery?>/">Посмотреть весь фоторепортаж</a>

</div>
</div>
<?}?>


<?/*div style="margin-top:10px"><strong>Просмотров: <?=$view?></strong> */?>
<?
$label='новости';
if($type=='action'){
	$label='акции';
}if($type=='video'){
	$label='видео';
}if($type=='public'){
	$label='статьи';
}
?>

<a class="back" href="/<?=$type?>/">Все <?=$label?></a>