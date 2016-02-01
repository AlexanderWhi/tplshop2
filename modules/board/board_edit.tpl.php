<form method="POST"  enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="grid">

<td colspan="2">Описание<textarea  name="description" style="width:100%;height:100px"><?=$description ?></textarea></td>
</tr>
<tr>
<td colspan="2">Текст<textarea  name="text" style="width:100%;height:300px"><?=$text  ?></textarea></td>
</tr>
<tr>
<tr>
<th>
Опубликовать до:
</th>
<td>
<input name="date_to" value="<?=dte($date_to)?>" class="date">
</td>
</tr>
<tr>
<th>Статус</th>
<td>
<?foreach($status_list as $key=>$desc){?>
	<input type="radio" class="radio" name="status" id="<?=$key?>" value="<?=$key?>" <?=($status == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>
</table>
<hr/>
		<input type="submit" name="save" class="button save" value="Сохранить"/> 
		<input name="close" type="submit" class="button" value="Закрыть"/>
</form>
<script type="text/javascript" src="/modules/board/admin_board_edit.js"></script>