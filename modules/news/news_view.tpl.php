<div class="news_view">
<?if($img){?>
	<img src="<?=scaleImg($img,'w170')?>" class="news_img">
<?}?>
<?if($this->isAdmin()){
	echo '<a href="/admin/'.$type.'/?act=edit&id='.$data['id'].'" class="coin-text-edit" title="�������������"></a>';
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
<a href="/gallery/<?=$gallery?>/">���������� ���� ������������</a>

</div>
</div>
<?}?>


<?/*div style="margin-top:10px"><strong>����������: <?=$view?></strong> */?>
<?
$label='�������';
if($type=='action'){
	$label='�����';
}if($type=='video'){
	$label='�����';
}if($type=='public'){
	$label='������';
}
?>

<a class="back" href="/<?=$type?>/">��� <?=$label?></a>