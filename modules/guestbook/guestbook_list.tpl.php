<form method="POST">
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>Время</th><th>Пользователь</th><th>Тема</th><th>Оценка</th><th>Статус</th><th>Действие</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td>
						<a href="<?=$this->mod_uri?>?act=edit&id=<?=$this->rs->get('id')?>"><?=$this->rs->get('time')?></a><br>
						<?=$this->rs->get('ip')?>
					</td>
					<td><strong><?=$this->rs->get('name')?></strong> <?=$this->rs->get('phone')?> <?=$this->rs->get('mail')?></td>				
					<?/*td><small><?=$this->rs->get('browser')?></small></td*/?>				
					<td><?=$this->rs->get('theme')?></td>				
					<td><?=$this->rs->get('score')?></td>				
					<td><?=$this->rs->get('status')?></td>				
					<td style="width:120px">
						<a href="<?=$this->mod_uri?>?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt=""/></a>
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
<script type="text/javascript" src="/modules/guestbook/admin_guestbook.js"></script>