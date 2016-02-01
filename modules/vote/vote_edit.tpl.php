<form method="POST" id="vote-form" action="?act=save">
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<tr>
<th style="width:150px">���������</th>
<td>
<input name="name" value="<?=$name?>" class="input-text"/>
</td>
</tr>
<tr>
<th>�����</th>
<td>
<input name="position" value="<?=$position?>" class="input-text" style="width:20px"/>
</td>
</tr>
<tr>
<th>������</th>
<td id="vote-item">
<table>
<thead>
<tr>
<th>������� ������</th>
<th>�������</th>
<th>���������</th>
</tr>
</thead>
<tbody>
<?foreach ($items as $item){?>
<tr>
<td><input name="item[<?=$item['id']?>]" value="<?=$item['name']?>"  style="width:200px"/></td>
<td><input name="item_sort[<?=$item['id']?>]" value="<?=$item['sort']?>"  style="width:20px"/></td>
<td><input name="item_res[<?=$item['id']?>]" value="<?=$item['result']?>"  style="width:20px"/></td>
<td><a href="#" class="remove">�������</a></td>
</tr>
<?}?>
</tbody>
</table>
<a id="item-add" href="#">��������</a>
</td>
</tr>
<tr>
<th><span>���� ������ <br /><small>(������ DD.MM.YYYY)</small></span></th>
<td><input name="start_time" value="<?=$start_time?>" class="date" ></td>
</tr>
<tr>
<th><span>���� ���������<br /><small>(������ DD.MM.YYYY)</small></span></th>
<td><input name="stop_time" value="<?=$stop_time?>"  class="date" ></td>
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
<hr/>
<input type="submit" name="save" class="button" value="���������"/> 
<input name="close" type="submit" class="button" value="�������"/>
</form>
<script type="text/javascript" src="/modules/vote/admin_vote_edit.js"></script>