<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?=$id?>"/>
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$this->cfg('UPLOAD_MAX_FILESIZE')?>">
	<table class="form">
	<tr>
		<th>Описание</th>
		<td><input class="input-text" name="description" value="<?=$description?>"></td>
	</tr>
	<tr>
		<th>Расположение</th>
		<td>
			<select name="place" class="input-text">
			<option value="0" <?=(0==$place)?'selected':''?>>Нет места</option>
			<?while ($placeList->next()) {
				$desc=$placeList->get('description');
				$desc.="[{$placeList->get('id')}]";
				if($placeList->get('width')){
					$desc.=" Ширина: {$placeList->get('width')}px;";
				}
				if($placeList->get('height')){
					$desc.=" Высота: {$placeList->get('height')}px;";
				}
				?>
				<option value="<?=$placeList->get('id')?>" <?=($placeList->get('id')==$place)?'selected="selected"':''?>><?=$desc?></option>
			<?}?>
			</select>
		</td>
	</tr>	
	<tr>
		<th>Дата начала</th>
		<td><input class="date" name="start_date" value="<?=$start_date?>"></td>
	</tr>	
	<tr>
		<th>Дата окончания</th>
		<td><input class="date" name="stop_date" value="<?=$stop_date?>"></td>
	</tr>	
	<tr>
		<th>URL</th>
		<td><input class="input-text" name="url" value="<?=$url?>"></td>
	</tr>	
	<tr>
		<th>Изображение</th>
		<td>
			<div id="html"><?if($file){echo $this->advHtml($file);}?></div>
			<input class="input-text" name="file" value="<?=$file?>" type="hidden"/>
			<input class="input-text" name="upload" type="file"/>
			<input class="button" type="button" name="clear" value="Убрать"/>
		</td>
	</tr>
	<tr>
		<th>HTML код</th>
		<td><textarea name="code" class="tiny"><?=$code?></textarea></td>
	</tr>
	</table>
	<input type="submit" class="button" name="save" value="Сохранить"/>
	<input name="close" class="button" type="submit" value="Закрыть"/>
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/core/component/adv/adv_edit.js"></script>