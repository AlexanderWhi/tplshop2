<form method="POST">
<input type="hidden" name="id" value="<?=$id?>"/>
	<table class="form">
	<tr>
	<th>��������:</th>
	<td><input class="input-text" name="description" value="<?=$description?>"/></td>
	</tr>
	
	<tr>
	<th>������:</th>
	<td><input class="input-text" style="width:60px" name="width" value="<?=$width?>"/></td>
	</tr>
	
	<tr>
	<th>������:</th>
	<td><input class="input-text" style="width:60px" name="height" value="<?=$height?>"/></td>
	</tr>
	</table>
	<hr>
	<input type="submit" class="button" name="save" value="���������"/>
	<input name="close" class="button" type="submit" value="�������"/>
</form>
<script type="text/javascript" src="/core/component/adv/adv_place_edit.js"></script>