<form method="POST">
<a href="/admin/enum/<?=$this->type?>_theme/">Список тем</a>
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>Автор</th><th>Дата</th><th>Вопрос</th><th>Ответ</th><th>Приоритет</th><th>Статус</th><th>Действие</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td style="text-align:left">
						<a href="/admin/<?=$this->rs->get('type')?>/?act=edit&id=<?=$this->rs->get('id')?>"><?=htmlspecialchars($this->rs->get('name'))?></a>
					</td>
					<td>
						<small>[<?=dte($this->rs->get('time'))?>]</small>
					</td>
					<td><small><?=$this->rs->get('question')?></small></td>
					<td><small><?=$this->rs->get('answer')?></small></td>
					<td><?=$this->rs->get('pos')?></td>
					<td>
						<small>[<?if($this->rs->get('state')=='edit'){?>
							На редактировании
							<?}elseif ($this->rs->get('state')=='arhiv'){?>
							В архиве
							<?}elseif ($this->rs->get('state')=='public'){?>
							Опубликовано
							<?}elseif ($this->rs->get('state')=='main'){?>
							Главная
						<?}?>]</small>
					</td>
					<td style="width:120px">
						<a href="/admin/<?=$this->rs->get('type')?>/?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt=""/></a>
						<a class="remove" id="remove<?=$this->rs->get('id')?>" href="#"><img src="/img/pic/trash_16.gif" title="Удалить" alt="Удалить"/></a>
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