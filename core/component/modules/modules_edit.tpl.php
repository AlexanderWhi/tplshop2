<form method="POST">
<input type="hidden" name="mod_id" value="<?=$mod_id?>"/>
<input type="hidden" name="mod_parent_id" value="<?=$mod_parent_id?>"/>
<input type="hidden" name="mod_content_id" value="<?=$mod_content_id?>"/>
<?
		if($mod_id===0){
			if($mod_parent_id==0){
				?><h1>Добавить раздел</h1><?
			}else{
				?><h1>Добавить подраздел</h1><?
			}
		}else{
			?><h1>Редактировать раздел</h1><?
		}?>
		<table class="form">
		<tr><th>Название раздела:</th><td><input class="input-text" name="mod_name" value="<?=$mod_name?>"/></td></tr>
		<tr><th>Псевдоним:</th><td><input class="input-text" type="text" name="mod_alias" value="<?=$mod_alias?>"/></td></tr>
		
		<tr>
			<th>Тип:</th>
			<td>
			<?foreach ($type as $k=>$v){?>
				<input id="mod_type<?=$k?>" type="radio" name="mod_type" value="<?=$k?>" <?=$k==$mod_type?'checked="checked"':''?>/>
				<label for="mod_type<?=$k?>"><?=$v?></label>
			<?}?>
			</td>
		</tr>
		<tr id="block-content" style="display:none">
		<td colspan="2">
		<label style="font-weight:bold">Контент:</label>
		<textarea style="width:100%" class="tiny" name="mod_content"><?=$content?></textarea></td></tr>
		
		<tr id="block-modules" style="display:none"><th>Модуль:</th><td>
		<select name="mod_module_name">
		<?foreach ($modules as $v){?>
			<option value="<?=$v?>" <?=$v==$mod_module_name?'selected':''?>><?=$v?></option>
		<?}?>
		</select>
		</td>
		
		<td></td></tr>
		<tr>
			<th><a href="/admin/enum/mod_location/">Раположение:</a></th>
			<td>
			<?foreach ($location as $k=>$v){?>
				<input type="checkbox" name="mod_location[]" value="<?=$k?>" <?=(false!==strpos($mod_location,$k))?'checked="checked"':''?>/>
				<?=$v?>
			<?}?>
			</td>
		</tr>
		<?if(!empty($mod_access_list)){?>
		<tr>
			<th>Доступ:</th>
			<td>
			<?foreach ($mod_access_list as $k=>$v){?>
				<input type="checkbox" name="mod_access[]" value="<?=$k?>" <?=(in_array($k,$mod_access))?'checked':''?>>
				<?=$v?>
			<?}?>
			</td>
		</tr>
		<?}?>
	
		<tr><th>Заголовок раздела:</th><td><textarea class="input-text" name="mod_title"><?=$mod_title?></textarea></td></tr>
		<tr><th>Описание раздела:</th><td><textarea class="input-text"  name="mod_description"><?=$mod_description?></textarea></td></tr>
		<tr><th>Ключевые слова:</th><td><textarea class="input-text" type="text" name="mod_keywords"><?=$mod_keywords?></textarea></td></tr>
		
		
		</table>
	
		<br /><br />
		<?if($this->isSu() || $mod_type!=0){?>
			<?if($mod_id==0) {?>
				<input class="button save" type="submit" name="save" value="Добавить"/>
			<?}else{?>
				<input class="button save" type="submit" name="save" value="Сохранить"/>
			<?}?>
			<?}?>
			<input class="button" name="close" type="submit" value="Закрыть"/>	
	</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/core/component/modules/modules_edit.js"></script>