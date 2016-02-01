<div id="public_view">
<div>
<span class="date"><?=dte($date)?></span>
<a class="category" href="/<?=$type?>/category/<?=$category?>"><?=$category_desc?></a>
</div>


<?if($img){?>
	<img src="<?=scaleImg($img,'w300')?>" class="img">
<?}?>
<?if($this->isAdmin()){
	echo '<a href="/admin/'.$type.'/?act=edit&id='.$data['id'].'" class="coin-text-edit" title="Редактировать"></a>';
}?>

<?=$data['content']?>
</div>

<?if($gallery){?>
Автор: <a href="/staff/<?=$gallery?>/"><?=$g_name?>, <?=$g_description?></a>
<?}elseif($author){?>
Автор: <?=$author?>
<?}?>

<br><br><br>
<?
$label='новости';
if($type=='action'){
	$label='акции';
}if($type=='video'){
	$label='видео';
}if($type=='public'){
	$label='публикации';
}
?>
<a class="back" href="/<?=$type?>/">Все <?=$label?></a>
<br><br><br>

<?if($rs=$rel){?>
<h2>похожие публикации</h2>
<?include('public_list.tpl.php')?>
<?}?>


<?if(isset($gallery_img_list)){?>
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
