<form method="POST">
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<tr>
<th style="width:150px">�����</th>
<td><input name="time" value="<?=$time?>" class="input-text"/></td>
</tr>
<tr>
<th>���</th>
<td><input name="name" value="<?=$name?>" class="input-text"/><br /></td>
</tr>
<tr>
<th>�������</th>
<td><input name="phone" value="<?=$phone?>" class="input-text"/><br /></td>
</tr>
<tr>
<th>����</th>
<td><input name="mail" value="<?=$mail?>" class="input-text"/><br /></td>
</tr>

<?/*tr>
<th>����� �������� ��-����</th>
<td><input name="cart_num" value="<?=$cart_num?>" class="input-text"/><br /></td>
</tr*/?>

<tr>
<th>����� ������ </th>
<td><input name="order_num" value="<?=$order_num?>" class="input-text"/><br /></td>
</tr>

<?/*tr>
<th>������� �������� </th>
<td><input name="shop" value="<?=$shop?>" class="input-text"/><br /></td>
</tr*/?>

<tr>
<th>���� ��������� </th>
<td><input name="theme" value="<?=$theme?>" class="input-text"/><br /></td>
</tr>

<tr>
<th>���������</th>
<td><textarea name="comment"><?=$comment?></textarea></td>
</tr>
<tr>
<th>�����</th>
<td><textarea name="answer" class="tiny"><?=$answer?></textarea></td>
</tr>
<tr>
<th>������</th>
<td>
<?foreach($status_list as $key=>$desc){?>
	<input type="radio" class="radio" name="status" id="<?=$key?>" value="<?=$key?>" <?=($status == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>
<th>������������</th>
<td><small><?=$browser?></small></td>
</tr>
</tr>
<th>IP �����</th>
<td><small><?=$ip?></small></td>
</tr>
</table>
<hr>
<input type="submit" name="save" class="button save" value="���������"/> 
<input type="submit" name="save_with_notice" class="button_long save" value="��������� ������"/>
<input name="close" type="submit" class="button" value="�������"/>	
</form>

<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/guestbook/admin_guestbook_edit.js"></script>