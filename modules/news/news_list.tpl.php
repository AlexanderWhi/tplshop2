<form method="POST">
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
		<button name="remove">Удалить</button>
			<table class="grid">
			<tr><th><input type="checkbox" class="item" name="all"></th><th>Изображение</th><th>Название</th><th>Дата</th><th>Показов</th><th>Стадия</th><th>Действие</th></tr>
			<?while($this->rs->next()){?>
				<tr>
				<td><input type="checkbox" name="item[]" value="<?=$this->rs->get('id');?>"></td>
					<td style="width:110px">
						<?if($this->rs->get('img')){?><img src="<?=scaleImg($this->rs->get('img'),'w100')?>" alt="<?=$this->rs->get('title')?>"/><?}else{?><img src="/img/admin/no_image.png"/><?}?>	
					</td>
					<td style="text-align:left">
						<a href="/admin/<?=$this->rs->get('type')?>/?act=edit&id=<?=$this->rs->get('id')?>"><?=htmlspecialchars($this->rs->get('title'))?></a>
					</td>
					<td>
						<small>[<?=dte($this->rs->get('date'))?>]</small> (<?=$this->rs->get('position')?>)
					</td>
					<td style="text-align:center" id="view<?=$this->rs->get('id')?>">
						<?=$this->rs->get('view')?>
					</td>
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
<script type="text/javascript" src="/modules/news/admin_news.js"></script>