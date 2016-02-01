<form method="POST">

<button class="button_long" name="send">Отправить письмо</button>
<input type="checkbox" name="with_pass" id="with_pass" value="1" checked><label for="with_pass">Задать пароль</label>

			<?$this->displayPageControl();?>
			<?if($this->getUriVal('hidden')==''){?>
<a href="<?=$this->getUri(array('hidden'=>'1'))?>">Показать скрытых</a>
<?}else{?>
<a href="<?=$this->getUri(array('hidden'=>null))?>">Скрыть скрытых</a>
<?}?>
			<?if($rs){?>
<table class="grid">
	<tr>
		<th><input type="checkbox" name="all"></th>
		<th style="white-space:nowrap"><?$this->sort('u_id','ID');?></th>
		<th style="white-space:nowrap"><?$this->sort('login','Логин');?></th>
		<th><?$this->sort('company','Компания');?></th>
		<th><?$this->sort('fio','ФИО');?></th>
		
		<th>Инфо</th>
		<th style="white-space:nowrap"><?$this->sort('reg_date','Дата регистрации');?></th>
		<th style="white-space:nowrap"><?$this->sort('visit','Посещений');?></th>
		<th style="white-space:nowrap"><?$this->sort('last_visit','Посл. пос.');?></th>
		<th>Предложений</th>
		<th>Комментария администратора</th>
		<?/*th>Сообщений</th*/?>
		<?/*th style="white-space:nowrap"><?$this->sort('balance','Баланс');?></th>
		<th style="white-space:nowrap"><?$this->sort('discount','Скидка');?></th*/?>
		<th></th>
	</tr>
	<? foreach ($rs as $row){?>
		<tr class="<?if($row['hide']==1){?>hidden<?}?>">
			<td><input type="checkbox" name="item[]" value="<?=$row['u_id']?>"></td>
			<td style="white-space:nowrap"><a href="<?=$this->mod_uri?>edit/?id=<?=$row['u_id'];?>"><?=htmlspecialchars($row["u_id"]);?></a></td>
			
			<td style="white-space:nowrap">
				<a href="<?=$this->mod_uri?>edit/?id=<?=$row['u_id'];?>"><?=htmlspecialchars($row["login"]);?></a>
			</td>
			<td><small><?=htmlspecialchars(trim($row["company"]));?></small></td>
			<td><small><?=htmlspecialchars(trim($row["name"]));?></small>	</td>
			<td>
				<small><?=htmlspecialchars($row["city"].', '.$row["address"].' '.$row["phone"].' '.$row["mail"]);?></small>	
			</td>
			<td title="<?=dte($row['reg_date'],'d.m.y H:i:s');?>"><?=dte($row['reg_date'],'d.m.y');?></td>
			<td><?=$row['visit'];?></td>
			<td title="<?=dte($row['last_visit'],'d.m.y H:i:s');?>"><?=dte($row['last_visit'],'d.m.y');?></td>
			
			<td><a href="/admin/catalog/goods/vendor/<?=$row['u_id'];?>/"><?=(int)$row['ci'];?></a> </td>
			<td><small><?=htmlspecialchars(trim($row["adm_comment"]));?></small>	</td>
			<?/*td><?=$row['f_messages');?></td*/?>
			<?/*td><?=$row['balance');?></td>
			<td><?=$row['discount');?></td*/?>
			<td style="white-space:nowrap;text-align:right">
				<a href="<?=$this->mod_uri?>edit/?id=<?=$row['u_id'];?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/></a>
				<?if($row['hide']==0){?>
				<a onclick="return confirm('Вы действительно хотите скрыть запись?')" href="?act=Remove&id=<?=$row['u_id'];?>"><img src="/img/pic/trash_16.gif" title="скрыть запись" border="0" alt=""/></a>
				<?}else{?>
				<a href="?act=Restore&id=<?=$row['u_id'];?>"><img src="/img/pic/undo_16.gif" title="Показать запись" border="0" alt=""/></a>
				<?if(empty($row['ci'])){?>
				<a onclick="return confirm('Вы действительно хотите удалить запись?')" href="?act=Delete&id=<?=$row['u_id'];?>"><img src="/img/pic/del_16.gif" title="Удалить запись" border="0" alt=""/></a>
				
				<?}?>
				<?}?>
				
				
			</td>
		</tr>	
	<?}?>
</table>
<?$this->displayPageControl();?>
<?}else{?>
<div>Список пуст</div>
<?}?>
</form>
<script type="text/javascript" src="/modules/vendor/vendor.js"></script>