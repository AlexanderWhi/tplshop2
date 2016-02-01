<div>
		<form method="POST">
			<?$this->displayPageControl();?>
			<?if($this->rs->getCount()){?>
<table class="grid">
	<tr>
		<th>Название</th>
		<th>Тема</th>
		<th style="width:100px;text-align:right">Действие</th>
	</tr>
	<? while($this->rs->next()){?>
		<tr>
			<td style="width:260px">
				<img src="/img/pic/docs_16.gif" title="<?=$this->rs->get('name');?>" alt="<?=$this->rs->get('name');?>"/> 
				<a href="?act=edit&id=<?=$this->rs->get('id');?>">[<?=htmlspecialchars($this->rs->get("name"));?>]</a>
			</td>
			<td>
			<small><?=htmlspecialchars($this->rs->get("theme"));?></small>
				
			</td>
			<td style="text-align:right">
				<a class="scrooll" href="?act=edit&id=<?=$this->rs->get('id');?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt="Редактировать"/></a>
				<a class="scrooll" onclick="return confirm('Вы действительно хотите удалить запись?')" href="?act=remove&id=<?=$this->rs->get('id');?>"><img src="/img/pic/trash_16.gif" title="Удалить запись" border="0" alt=""/></a>
			</td>
		</tr>	
	<?}?>
</table>
<?$this->displayPageControl();?>
<?}else{?>
<div>Список пуст</div>
<?}?>
</form>
</div>
		
		