<div id="public_view">
<div>
<span class="date"><?=dte($date)?></span>
<a class="category" href="/<?=$type?>/category/<?=$category?>"><?=$category_desc?></a>
</div>


<?if($img){?>
	<img src="<?=scaleImg($img,'w300')?>" class="img">
<?}?>
<?if($this->isAdmin()){
	echo '<a href="/admin/'.$type.'/?act=edit&id='.$data['id'].'" class="coin-text-edit" title="�������������"></a>';
}?>

<?=$data['content']?>
</div>

<?if($gallery){?>
�����: <a href="/staff/<?=$gallery?>/"><?=$g_name?>, <?=$g_description?></a>
<?}elseif($author){?>
�����: <?=$author?>
<?}?>

<br><br><br>
<?
$label='�������';
if($type=='action'){
	$label='�����';
}if($type=='video'){
	$label='�����';
}if($type=='public'){
	$label='����������';
}
?>
<a class="back" href="/<?=$type?>/">��� <?=$label?></a>
<br><br><br>

<?if($rs=$rel){?>
<h2>������� ����������</h2>
<?include('public_list.tpl.php')?>
<?}?>


<?if(isset($gallery_img_list)){?>
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
