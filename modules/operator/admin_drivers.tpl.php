<form method="POST">
<a href="<?=$this->mod_uri?>driversEdit/"><img src="/img/pic/add_16.gif"/>Добавить</a>
<?if($rs->getCount()){?>
<table class="grid">
	<tr>
		<th>id</th>
		<th>Фио</th>
		<th>Телефон</th>
		<th>Машина</th>
		<th>Статус</th>
	</tr>
	<?while($rs->next()){?>
	<tr>
		<td><strong><?=$rs->get('id');?></strong></td>
		<td><a href="<?=$this->mod_uri?>driversEdit/?id=<?=$rs->get('id');?>"><i><?=$rs->get("name");?></i></a></td>
		<td><?=$rs->get("phone");?></td>
		<td><i><?=$rs->get('car');?></i></td>
		<td><?if($rs->get('state')==1){?>Активен<?}else{?>Неактивен<?}?></td>	
	</tr>
	<?}?>
</table>
<?}else{?>
<div>Список пуст</div>
<?}?>
</form>