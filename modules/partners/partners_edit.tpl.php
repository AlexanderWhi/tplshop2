<form method="POST" id="partners-form" action="?act=save" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<tr>
<th style="width:150px">���</th>
<td>
<input name="name" value="<?=$name?>" class="input-text"/>
</td>
</tr>
<tr>
<th>��������</th>
<td>
<textarea name="description" class="input-text"><?=$description?></textarea>
</td>
</tr>

<?/*tr>
<th>�������</th>
<td>
<input name="phone" value="<?=$phone?>" class="input-text"/>
</td>
</tr>

<tr>
<th>e-mail</th>
<td>
<input name="mail" value="<?=$mail?>" class="input-text"/>
</td>
</tr>

<tr>
<th>ICQ</th>
<td>
<input name="icq" value="<?=$icq?>" class="input-text"/>
</td>
</tr>

<tr>
<th>��������</th>
<td>
��������� �������� <input name="delivery" value="<?=$delivery?>"/><br />
������� ���������� �������� <input name="delivery_cond" value="<?=$delivery_cond?>"/><br />
������� �������� ������ <input name="delivery_order_cond" value="<?=$delivery_order_cond?>"/>
</td>
</tr*/?>



<tr>
<th>������(URL)</th>
<td>
<input name="url" class="input-text" value="<?=$url?>">
</td>
</tr>
<tr>
<th>�����������</th>
<td>
<table>
<td>
<input type="hidden" name="img" value="<?=$img?>"/>
<img id="img-file" src="<?=$img?$img:'/img/admin/no_image.png'?>"/><br/>
<input type="file" name="upload"/> <input class="button" type="button" name="clear" value="��������"/>
</td>
<?/*td>
<input type="hidden" name="img1" value="<?=$img1?>"/>
<img id="img1-file" src="<?=$img1?$img1:'/img/admin/no_image.png'?>"/><br/>
<input type="file" name="upload1"/> <input class="button" type="button" name="clear1" value="��������"/>
</td*/?>
</table>


</td>
</tr>

<tr>
<th>������</th>
<td>
<?foreach($status as $key=>$desc){?>
	<input type="radio" class="radio" name="state" id="<?=$key?>" value="<?=$key?>" <?=($state == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>

<tr>
<th>���������</th>
<td>
<input name="sort" value="<?=$sort?>" class="input-text" style="width:20px"/>
</td>
</tr>
</table>
<hr/>
<input type="submit" name="save" class="button" value="���������"/> 
<input name="close" type="submit" class="button" value="�������"/>
</form>
<script type="text/javascript" src="/modules/partners/admin_partners_edit.js"></script>