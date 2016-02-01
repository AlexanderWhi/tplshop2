<form method="POST" action="?act=apply">
<button class="button" name="save" type="submit">Применить</button>
<div>
<a href="/admin/enum/gal_<?=$this->getType()?>_cat">Каталог</a>:
<a href="<?=$this->getUri(array('category'=>null))?>">Все</a>
<?foreach ($cat_list as $k=>$d) {?>
	, <a href="<?=$this->getUri(array('category'=>"$k"))?>"><?=$d?></a>
<?}?>
</div>
<div>
<a href="/admin/enum/gal_<?=$this->getType()?>_label">метки</a>:
<a href="<?=$this->getUri(array('label'=>null))?>">Все</a>
<?foreach ($label_list as $k=>$d) {?>
	, <a href="<?=$this->getUri(array('label'=>"$k"))?>"><?=$d?></a>
<?}?>
</div>

		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>Изображение</th>
			<th><?=$this->sort('name','Название')?></th>
			<th><?=$this->sort('cat','Категория')?></th>
			<th>Метки</th>
			<th><?=$this->sort('date','Дата')?></th>
			<th><?=$this->sort('sort','Позиция')?></th>
			<th><?=$this->sort('sort_main','Позиция на главной')?></th>
			<th>Действие</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td style="width:110px">
					
						<?if($this->rs->get('img')){?><img src="<?=scaleImg($this->rs->get('img'),'w100')?>" alt="<?=$this->rs->get('title')?>"/><?}else{?><img src="/img/admin/no_image.png"/><?}?>	
					</td>
					<td style="text-align:left">
						<a href="?act=edit&id=<?=$this->rs->get('id')?>"><?=htmlspecialchars($this->rs->get('name'))?></a>
					</td>
					<td>
					<small>
					<?=$this->rs->get('cat_desc')?>
					</small>
					</td>
					
					<td>
					<?if(!empty($labels[$this->rs->get('id')])){?>
					<?foreach ($labels[$this->rs->get('id')] as $l) {?>
						<div>
					<small>
					<?=@$label_list[$l]?>
					</small>
					</div>
					<?}?>
					<?}?>
					</td>
					
					<td>
					<small>
					<?=dte($this->rs->get('date'))?>
					</small>
					</td>
					<?/*td>
						<small>[<?if($this->rs->get('state')=='edit'){?>
							На редактировании
							<?}elseif ($this->rs->get('state')=='arhiv'){?>
							В архиве
							<?}elseif ($this->rs->get('state')=='public'){?>
							Опубликовано
							<?}elseif ($this->rs->get('state')=='main'){?>
							Главная
						<?}?>]</small>
					</td*/?>
					<td><input class="num" name="sort[<?=$this->rs->getInt('id')?>]" value="<?=$this->rs->getInt('sort')?>"></td>
					
					<td><input class="num" name="sort_main[<?=$this->rs->getInt('id')?>]" value="<?=$this->rs->getInt('sort_main')?>"></td>
					<td style="width:120px">
					
						<a href="?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt=""/></a>
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
<script type="text/javascript" src="/modules/gallery/admin_gallery.js"></script>