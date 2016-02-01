<form method="POST">
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>Название</th><th>Дата начала</th><th>Дата окончания</th><th>Статус</th><th>Действие</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td>
						<a href="<?=$this->mod_uri?>edit/?id=<?=$this->rs->get('id')?>"><?=$this->rs->get('name')?></a>
					</td>
					<td>
						<small>[<?=$this->rs->get('start_time')?>]</small>
					</td>
					<td>
						<small>[<?=$this->rs->get('stop_time')?>]</small>
					</td>
					<td>
						<?=$this->status[$this->rs->get('state')]?>
					</td>
					<td style="width:120px">
						<a href="<?=$this->mod_uri?>edit/?id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt=""/></a>
						<a class="reset " id="reset<?=$this->rs->get('id')?>"  href="#"><img src="/img/pic/undo_16.gif" title="Сброс" alt=""/></a>
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
<script type="text/javascript" src="/modules/vote/admin_vote.js"></script>