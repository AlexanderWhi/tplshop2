<form method="POST"  enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="grid">
<tr>
<th>Автор</th>
<td><input name="name" value="<?=$name?>" class="input-text"/></td>
</tr>
<td colspan="2">Вопрос<textarea class="" name="question" style="width:100%;height:100px"><?=$question ?></textarea></td>
</tr>
<tr>
<td colspan="2">Ответ<textarea class="tiny" name="answer" style="width:100%;height:300px"><?=$answer  ?></textarea></td>
</tr>
<tr>
<tr>
<th>Приоритет</th>
<td><input name="pos" value="<?=$pos?>" class="input-text num" ></td>
</tr>
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
		<input type="submit" name="save" class="button save" value="Сохранить"/> 
		<input name="close" type="submit" class="button" value="Закрыть"/>
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/faq/admin_faq_edit.js"></script>