<form method="POST">
<a href="/admin/enum/<?=$this->type?>_theme/">������ ���</a>
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>�����</th><th>����</th><th>������</th><th>�����</th><th>���������</th><th>������</th><th>��������</th></tr>
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
<script type="text/javascript" src="/modules/faq/admin_faq.js"></script>