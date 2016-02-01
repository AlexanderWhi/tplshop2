<form id="gallery-edit-form" class="gallery" method="POST"  enctype="multipart/form-data" target="form_dst" action="?act=save">
<iframe id="form_dst" name="form_dst" style="display:none"></iframe>
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="type" value="<?=$type?>">
<table class="form">
<?if($id){?>
<tr>
<th>Просмотр</th>
<td><a href="/<?=$type?>/<?=$id?>/">/<?=$type?>/<?=$id?>/</a></td>
</tr>
<?}?>
<tr>
	<th>Заголовок</th>
	<td><input type="text" name="name" value="<?=$name?>" class="input-text"></td>
</tr>
<?if($cat_list || $this->isSu()){?>
<tr>
	<th><a href="/admin/enum/gal_<?=$type?>_cat">Категория</a></th>
	<td>
	<select name="cat">
	<?foreach ($cat_list as $k=>$desc) {?>
		<option value="<?=$k?>" <?if($k==$cat){?>selected<?}?>><?=$desc?></option>
	<?}?>
	
	</select>
	</td>
</tr>
<?}?>
<?if($label_list || $this->isSu()){?>
<tr>
	<th><a href="/admin/enum/gal_<?=$type?>_label">Метки</a></th>
	<td>
	<?foreach ($label_list as $k=>$desc) {?>
		<input name="label[]" type="checkbox" value="<?=$k?>" <?if(in_array($k,$label)){?>checked<?}?> id="label-<?=$k?>">&nbsp;<label for="label-<?=$k?>"><?=$desc?></label>&nbsp;&nbsp;&nbsp; 
	<?}?>
	</td>
</tr>
<?}?>
<?if($img_format || $this->isSu()){?>
<tr>
	<th><a href="/admin/enum/gal_<?=$type?>_format">Формат изображения</a></th>
	<td>
	<select name="img_format">
	<option value="0">--по умолчанию</option>
	<?foreach ($format_list as $k=>$desc) {?>
		<option value="<?=$k?>" <?if($k==$img_format){?>selected<?}?>><?=$desc?></option>
	<?}?>
	</select>
	</td>
</tr>
<?}?>



<tr>
<th>Дата</th>
<td><input name="date" value="<?=dte($date)?>" class="date" ></td>
</tr>

<tr>
		<th>Изображение</th>
		<td>
			
			<div id="html"><img rel="/img/no_image.png" id="img" src="<?if($img){?><?=scaleImg($img,'h100')?><?}else{?>/img/no_image.png<?}?>"></div>
			<input name="img" value="<?=$img?>" type="hidden"/>
			<input name="img_upload" type="file">
			<input class="button" type="button" name="clear" value="Убрать"/>
		</td>
	</tr>
	
<tr>
<th>Приоритет</th>
<td><input name="sort" value="<?=$sort?>" class="input-text" style="width:40px"/><br /></td>
</tr>

<tr>
<th>Краткое описание</th>
<td><textarea name="description" class="input-text"><?=$description?></textarea></td>
</tr>

<tr>
<td colspan="2">
<textarea class="tiny" name="text" style="width:100%;height:300px"><?=$text?></textarea>
</td>
</tr>
	
<td colspan="2">
<div class="img-upload-item" style="display:none">
	<input name="pos_">
	<input type="hidden" name="images_" value="">
	<a href="#" class="del"></a>
	<div ><img src="/img/no_image.png"></div>
	
	<textarea name="desc_"></textarea><br>
	<select name="format_" style="display:none">
	<option value="0">--по умолчанию</option>
	<?foreach ($format_list as $k=>$desc) {?>
		<option value="<?=$k?>"><?=$desc?></option>
	<?}?>
	</select>
</div>

<div id="img-upload">


</div>
<?if($this->isSu() && $id){?>
<a class="reload" href="?act=reload&id=<?=$id?>">Обновить из директории</a>
<?}?>

<div id="img-upload-bar" style="clear:both">
<h3>Загрузить изображения</h3>
<div class="img-upload-bar-item">
<input name="images_upload[]" type="file" multiple>
</div>

</div>

</td>	
<?/*tr>
<th>Статус</th>
<td>
<?foreach($status as $key=>$desc){?>
	<input type="radio" class="radio" name="state" id="<?=$key?>" value="<?=$key?>" <?=($state == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr*/?>
</table>
<hr/>
		<input type="submit" name="save" class="button save" value="Сохранить"/> 
		<input name="close" type="submit" class="button" value="Закрыть"/>
</form>

<script type="text/javascript">

var IMAGES=<?=printJSON($data['images'])?>;
</script>

<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/gallery/admin_gallery_edit.js"></script>