<div>
<form method="POST">
<input type="hidden" name="id" value="<?=$id?>"/>

<?=$data['html']?>

<table class="form">
<th>������</th>
<td>
<?foreach($status_list as $key=>$desc){?>
	<input type="radio" class="radio" name="status" id="<?=$key?>" value="<?=$key?>" <?=($status == $key)?'checked':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>
</table>
<input type="submit" name="save" class="button save" value="���������"/> 
<input name="close" type="submit" class="button" value="�������"/>	
</form>
</div>
<script type="text/javascript" src="/modules/<?=$this->mod_module_name?>/admin_forms_edit.js"></script>