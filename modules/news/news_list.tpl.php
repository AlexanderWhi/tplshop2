<form method="POST">
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
		<button name="remove">�������</button>
			<table class="grid">
			<tr><th><input type="checkbox" class="item" name="all"></th><th>�����������</th><th>��������</th><th>����</th><th>�������</th><th>������</th><th>��������</th></tr>
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
							�� ��������������
							<?}elseif ($this->rs->get('state')=='arhiv'){?>
							� ������
							<?}elseif ($this->rs->get('state')=='public'){?>
							������������
							<?}elseif ($this->rs->get('state')=='main'){?>
							�������
						<?}?>]</small>
					</td>
					<td style="width:120px">
						<a href="/admin/<?=$this->rs->get('type')?>/?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="�������������" alt=""/></a>
						<a class="reset " id="reset<?=$this->rs->get('id')?>"  href="#"><img src="/img/pic/undo_16.gif" title="�����" alt=""/></a>
						<a class="remove" id="remove<?=$this->rs->get('id')?>" href="#"><img src="/img/pic/trash_16.gif" title="�������" alt="�������"/></a>
					</td>
				</tr>	
			<?}?>
			</table>
			<?$this->displayPageControl();?>
		<?}else{?>
			<div>������� �� �������</div>
		<?}?>
	</form>
<script type="text/javascript" src="/modules/news/admin_news.js"></script>