<div>
	<form method="POST">
		<?$pg->display()?><?$pg->displayPageSize()?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>№</th><th>Время</th><th>Статус</th><th>Действие</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td><a href="<?=$this->mod_uri?>?act=edit&id=<?=$this->rs->get('id')?>"><?=$this->rs->get('id')?></a></td>
					<td><?=$this->rs->get('time')?></td>
					<td><?=$this->rs->get('status_desc')?></td>					
					<td style="width:120px">
						<a href="<?=$this->mod_uri?>?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt=""/></a>
						<a class="remove" id="remove<?=$this->rs->get('id')?>" href="#"><img src="/img/pic/trash_16.gif" title="Удалить" alt="Удалить"/></a>
					</td>
				</tr>	
			<?}?>
			</table>
			<?$pg->display()?><?$pg->displayPageSize()?>
		<?}else{?>
			<div>Записей не найдено</div>
		<?}?>
	</form>
</div>
<script type="text/javascript" src="/modules/<?=$this->mod_module_name?>/admin_forms.js"></script>