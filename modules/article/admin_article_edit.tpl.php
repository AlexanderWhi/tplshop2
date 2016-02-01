<form method="POST"  enctype="multipart/form-data" id="article-form" target="fr" action="?act=save">
<iframe name="fr" style="display:none"></iframe>
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<tr>
<th>���������</th>
<td><input type="text" name="title" value="<?=$title?>" class="input-text"/></td>
</tr>
<tr>

<?if($category_list){?>
<tr>
<th><a href="/admin/enum/<?=$type?>_category/">���������</a></th>
<td>
<select name="category">
<option value="0">--�� �������</option>
<?foreach ($category_list as $k=>$d) {?>
	<option value="<?=$k?>" <?if($k==$category){?>selected<?}?>><?=$d?></option>
<?}?>
</select>

</td>
</tr>
<tr>
<?}?>


<th><span>���� <small>(������ DD.MM.YYYY)</small></span></th>
<td><input name="date" value="<?=$date?>" class="input-text" style="width:100px"/><br /></td>
</tr>
<tr>
<th>���������</th>
<td><input name="position" value="<?=$position?>" class="input-text" style="width:40px"/><br /></td>
</tr>
<tr>
<th>������� ��������</th>
<td><textarea name="description" class="input-text"><?=$description?></textarea></td>
</tr>
<tr><th>����������� : </th>
			<td>
				<img id="img-file" src="<?=$img?scaleImg($img,'w200'):'/img/no_image.png'?>"/><br/>
				<input type="file" name="upload"> <input type="checkbox" name="clear"  value="1"><label>�������</label>
			</td>
		</tr>
<tr>
<td colspan="2">
<textarea class="tiny" name="content" style="width:100%;height:300px"><?=$content?></textarea>
</td>
</tr>
<tr>
<th>������� ������</th>
<td>
<div class="checkbox_list">
<?foreach ($article_list as $row) {?>
	<div>
	<input type="checkbox" name="public_rel[]" id="public_rel_<?=$row['id']?>" value="<?=$row['id']?>" <?if(in_array($row['id'],$rel)){?>checked<?}?> >
	<label for="public_rel_<?=$row['id']?>"><?=dte($row['date'])?>:<?=$row['title']?></label>
	</div>
<?}?>
</div>
</td>
</tr>


<?if(!empty($staff_list)){?>
<tr>
<th>����� ���������</th>
<td>

<select name="gallery">
<option value="0">--�� ������</option>
<?foreach ($staff_list as $row) {?>
	<option value="<?=$row['id']?>" <?if($row['id']==$gallery){?>selected<?}?>><?=$row['name']?></option>
<?}?>

</select>

</td>
</tr>
<?}?>
<tr>
<th>�����</th>
<td><input name="author" value="<?=$author?>" class="input-text"/></td>
</tr>
<tr>
<th>������</th>
<td>
<?foreach($status as $key=>$desc){?>
	<input type="radio" class="radio" name="state" id="<?=$key?>" value="<?=$key?>" <?=($state == $key)?'checked="checked"':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>
</table>


		<input type="submit" name="save_and_send" class="button_long save" value="��������� ��������������"/>
		<input type="submit" name="save_and_send_all" class="button_long save" value="��������� ���� <?=$sended_count?>/<?=$send_count?>"/><input name="pack" value="1000" style="width:40px;">
		
		<input type="submit" name="save" class="button save" value="���������"/> 
		<input name="close" type="submit" class="button" value="�������"/>
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/article/admin_article_edit.js"></script>