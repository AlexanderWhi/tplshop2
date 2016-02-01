<form method="POST"  enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<?if($id){?>
<tr>
<th>Просмотр</th>
<td><a href="/<?=$type?>/<?=$id?>/">/<?=$type?>/<?=$id?>/</a></td>
</tr>
<?}?>
<tr>
<th>Заголовок</th>
<td><input type="text" name="title" value="<?=$title?>" class="input-text"/></td>
</tr>
<tr>
<th><span>Дата <small>(формат DD.MM.YYYY)</small></span></th>
<td><input name="date" value="<?=$date?>" class="date"/><br /></td>
</tr>
<tr>
<th><span>Дата окончания <small>(формат DD.MM.YYYY)</small></span></th>
<td><input name="date_to" value="<?=$date_to?>" class="date"/><br /></td>
</tr>
<tr>
<th>Приоритет</th>
<td><input name="position" value="<?=$position?>" class="input-text num"><br /></td>
</tr>
<tr>
<th>Краткое описание</th>
<td><textarea name="description" class="input-text"><?=$description?></textarea></td>
</tr>
<tr><th>Изображение : </th>
			<td>
				<input type="hidden" name="img" value="<?=$img?>"/>
				<img id="img-file" src="<?=$img?scaleImg($img,'w100h100'):'/img/admin/no_image.png'?>"/><br/>
				<input type="file" name="upload"/> <input type="button" name="clear" value="Очистить"/>
			</td>
		</tr>
<tr>
<td colspan="2">
<textarea class="tiny" name="content" style="width:100%;height:300px"><?=$content?></textarea>
</td>
</tr>
<tr>
<th>Автор</th>
<td><input name="author" value="<?=$author?>" class="input-text"/></td>
</tr>
<?if($gallery_list){?>
<tr>
<th>Галлерея</th>
<td>
<select name="gallery">
<option value="0">--Не выбрано</option>
<?foreach($gallery_list as $row){?>
	<option value="<?=$row['id']?>" <?=($row['id'] == $gallery)?'selected':''?> ><?=$row['name']?></option>
<?}?>
</select>
</td>
</tr>
<?}?>
<tr>
<th>Статус</th>
<td>
<?foreach($status as $key=>$desc){?>
	<input type="radio" class="radio" name="state" id="<?=$key?>" value="<?=$key?>" <?=($state == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>
</table>
<hr/>
		<!--<input type="submit" name="save_and_send" class="button_long save" value="Разослать администратору"/>
		<input type="submit" name="save_and_send_all" class="button_long save" value="Разослать подписчикам"/>-->
		<input type="submit" name="save" class="button save" value="Сохранить"/> 
		<input name="close" type="submit" class="button" value="Закрыть"/>
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/news/admin_news_edit.js"></script>