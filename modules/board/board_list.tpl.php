<form method="POST">
<a href="/admin/catalog/">Каталог</a>
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>ID</th><th>Автор</th><th>Время размещения</th><th>Дата до</th><th>Описание</th><th>Текст</th><th>Статус</th><th>Действие</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td>
						<a href="?act=edit&id=<?=$this->rs->get('id')?>"><?=$this->rs->get('id')?></a>
					</td>
					<td style="text-align:left">
						<a href="/admin/users/?act=edit&id=<?=$this->rs->get('u_id')?>"><?=htmlspecialchars($this->rs->get('name'))?></a>
					</td>
					<td><small>[<?=dte($this->rs->get('time'))?>]</small></td>
					<td><small>[<?=dte($this->rs->get('date_to'))?>]</small></td>
					<td><small><?=$this->rs->get('description')?></small></td>
					<td><small><?=$this->rs->get('text')?></small></td>
					
					<td>
						<small>[<?if($this->rs->get('status')==0){?>
							На редактировании
							<?}elseif ($this->rs->get('status')==1){?>
							Опубликовано
							<?}?>]</small>
					</td>
					<td style="width:120px">
						<a href="?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt=""/></a>
						<a class="remove" href="?act=remove&id=<?=$this->rs->get('id')?>"><img src="/img/pic/trash_16.gif" title="Удалить" alt="Удалить"/></a>
					</td>
				</tr>	
			<?}?>
			</table>
			<?$this->displayPageControl();?>
		<?}else{?>
			<div>Записей не найдено</div>
		<?}?>
	</form>
<script type="text/javascript" src="/modules/faq/admin_faq.js"></script>