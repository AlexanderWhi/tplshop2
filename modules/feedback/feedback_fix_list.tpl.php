
	<form method="POST">
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>�����</th><th>����</th><th>���������</th><th>�������</th><th>��������</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td>
						<?=dte($this->rs->get('time'),'d.m.Y H:i')?><br>
						<?=$this->rs->get('ip')?>
					</td>
					<td><strong><?=$this->rs->get('theme')?></strong></td>
								
					<td><small><?=substr($this->rs->get('comment'),0,200)?></small></td>					
					<td><small><?=substr($this->rs->get('browser'),0,200)?></small></td>					
							
					<td style="width:120px">
						<a  href="?act=removeFix&id=<?=$this->rs->get('id')?>"><img src="/img/pic/trash_16.gif" title="�������" alt="�������"/></a>
					</td>
				</tr>	
			<?}?>
			</table>
			<?$this->displayPageControl();?>
		<?}else{?>
			<div>������� �� �������</div>
		<?}?>
	</form>