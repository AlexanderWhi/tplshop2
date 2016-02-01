<form method="POST" id="vote-form" action="?act=save">
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<tr>
<th style="width:150px">Заголовок</th>
<td>
<input name="name" value="<?=$name?>" class="input-text"/>
</td>
</tr>
<tr>
<th>Место</th>
<td>
<input name="position" value="<?=$position?>" class="input-text" style="width:20px"/>
</td>
</tr>
<tr>
<th>Ответы</th>
<td id="vote-item">
<table>
<thead>
<tr>
<th>Вариант ответа</th>
<th>Позиция</th>
<th>результат</th>
</tr>
</thead>
<tbody>
<?foreach ($items as $item){?>
<tr>
<td><input name="item[<?=$item['id']?>]" value="<?=$item['name']?>"  style="width:200px"/></td>
<td><input name="item_sort[<?=$item['id']?>]" value="<?=$item['sort']?>"  style="width:20px"/></td>
<td><input name="item_res[<?=$item['id']?>]" value="<?=$item['result']?>"  style="width:20px"/></td>
<td><a href="#" class="remove">Удалить</a></td>
</tr>
<?}?>
</tbody>
</table>
<a id="item-add" href="#">Добавить</a>
</td>
</tr>
<tr>
<th><span>Дата начала <br /><small>(формат DD.MM.YYYY)</small></span></th>
<td><input name="start_time" value="<?=$start_time?>" class="date" ></td>
</tr>
<tr>
<th><span>Дата окончания<br /><small>(формат DD.MM.YYYY)</small></span></th>
<td><input name="stop_time" value="<?=$stop_time?>"  class="date" ></td>
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
<input type="submit" name="save" class="button" value="Сохранить"/> 
<input name="close" type="submit" class="button" value="Закрыть"/>
</form>
<script type="text/javascript" src="/modules/vote/admin_vote_edit.js"></script>