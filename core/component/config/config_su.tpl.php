<form method="POST">
		<table class="grid" id="config">
		<thead>
		<th>
		<input class="item" type="checkbox">
		</th>
		<th>Имя</th>
		<th>Значение</th>
		<th>Описание</th>
		</thead>
		<tbody>
		<?include('config_list.tpl.php')?>
		</tbody>
		<tfoot>
		<tr>
		<td></td>
		<td><input name="name"></td>
		<td><input name="value" class="input-text"></td>
		<td><input name="description"></td>
		<td>
		<button name="add">Добавить</button>
		</td>
		</tr>
		</tfoot>
		</table>
		<br/>
		<input name="remove" type="button" value="Удалить" class="button"/>	
		<input type="submit" value="Сохранить" class="button save"/>	
		</form>
		<script type="text/javascript" src="/core/component/config/config.js"></script>