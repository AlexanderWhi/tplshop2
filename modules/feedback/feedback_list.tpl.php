<div>
	<form method="POST">
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>�����</th><th>���</th><th>��������</th><th>���������</th><th>�������</th><th>���</th><th>������</th><th>��������</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td>
						<a href="<?=$this->mod_uri?>?act=edit&id=<?=$this->rs->get('id')?>"><?=dte($this->rs->get('time'),'d.m.Y H:i')?></a><br>
						<?=$this->rs->get('ip')?>
					</td>
					<td><strong><?=$this->rs->get('name')?></strong></td>
					<td><?=$this->rs->get('phone')?></td>
					
										
					<td><small><?=substr($this->rs->get('comment'),0,200)?></small></td>					
					<td><small><?=substr($this->rs->get('browser'),0,200)?></small></td>					
					<td><?=$this->rs->get('type_desc')?></td>					
					<td><?=$this->rs->get('status_desc')?></td>					
					<td style="width:120px">
						<a href="<?=$this->mod_uri?>?act=edit&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="�������������" alt=""/></a>
						<a class="remove" id="remove<?=$this->rs->get('id')?>" href="#<?=$this->rs->get('id')?>"><img src="/img/pic/trash_16.gif" title="�������" alt="�������"/></a>
					</td>
				</tr>	
			<?}?>
			</table>
			<?$this->displayPageControl();?>
		<?}else{?>
			<div>������� �� �������</div>
		<?}?>
	</form>
</div>
<script type="text/javascript" src="/modules/feedback/admin_feedback.js"></script>