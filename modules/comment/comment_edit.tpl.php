<form id="comment-form" method="POST"  enctype="multipart/form-data" action="?act=save">
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="grid">
<tr>
<th style="width:200px"><span>����� ��������� <small>(������ DD.MM.YYYY)</small></span></th>
<td><input name="time" value="<?=dte($time)?>" class="date"><br /></td>
</tr>
<tr>
<th>���������</th>
<td><textarea name="comment" style="width:100%;height:100px"><?=$comment?></textarea></td>
</tr>
<th>�����</th>
<td>
<textarea name="answer" style="width:100%;height:100px"><?=$answer?></textarea>
</td>
</tr>
<tr>
<th><span>����� <small>(������ DD.MM.YYYY)</small></span></th>
<td><input name="time_answer" value="<?=dte($time_answer)?>" class="date" ><br /></td>
</tr>
<tr>
<th>������</th>
<td>
<?foreach($status_list as $key=>$desc){?>
	<input type="radio" class="radio" name="status" id="<?=$key?>" value="<?=$key?>" <?=($status == $key)?'checked':''?>/> <label for="<?=$key?>"><?=$desc?></label><br />
<?}?>
</td>
</tr>
</table>
<hr/>
		<input type="submit" name="save" class="button save" value="���������"/> 
		<input name="close" type="submit" class="button" value="�������"/>
</form>
<script type="text/javascript" src="/modules/comment/comment_edit.js"></script>