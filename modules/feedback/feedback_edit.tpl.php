<div>
<form method="POST" action="?act=save">
<input type="hidden" name="id" value="<?=$id?>"/>
<input type="hidden" name="type" value="<?=$type?>"/>
<table class="form">

<?if(!empty($type_desc)){?>
<tr>
<th>���</th>
<td>
<?=$type_desc?>
</td>
</tr>
<?}?>


<tr>
<th>�����</th>
<td>
<input name="time" value="<?=$time?>" class="input-text">
</td>
</tr>
<tr>
<th>���</th>
<td>
<input name="name" value="<?=$name?>" class="input-text">
</td>
</tr>
<tr>
<th>��������</th>
<td>
<input name="phone" value="<?=$phone?>" class="input-text">
</td>
</tr>
<?if($file){?>
<tr>
<th>URL</th>
<td><a href="<?=$file?>"><?=$file?></a></td>
</tr>
<?}?>
<tr>
<th>���������</th>
<td>
	<textarea name="comment" style="height:200px"  class="input-text"><?=$comment?></textarea>
</td>
</tr>
<tr>
<th>�����</th>
<td>
	<textarea name="answer" style="height:200px"  class="input-text"><?=$answer?></textarea>
</td>
</tr>
<tr>
<th>������</th>
<td>
<?foreach($status_list as $key=>$desc){?>
	<input type="radio" class="radio" name="status" id="<?=$key?>" value="<?=$key?>" <?=($status == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>
</table>
<hr>
<input type="submit" name="save" class="button save" value="���������"/> 
<input name="close" type="submit" class="button" value="�������"/>	
</form>
</div>
<script type="text/javascript" src="/modules/feedback/admin_feedback.js"></script>