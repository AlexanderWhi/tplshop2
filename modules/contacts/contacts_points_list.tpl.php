<div>
	<form method="POST">
		<?$this->displayPageControl('editAddr');?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>���</th><th>�����</th><!--<th>�����</th>--><th>��������</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td>
						<a href="<?=$this->mod_uri?>?act=editAddr&id=<?=$this->rs->get('id')?>"><?=$this->rs->get('name')?></a><br>
					</td>
					<td><strong><?=$this->rs->get('addr')?></strong></td>
					
										
					<!--<td><small><?=$this->rs->get('point')?></small></td>	-->				
									
					<td style="width:120px">
						<a href="<?=$this->mod_uri?>?act=editAddr&id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="�������������" alt=""/></a>
						<a class="removeAddr" id="remove<?=$this->rs->get('id')?>" href="#<?=$this->rs->get('id')?>"><img src="/img/pic/trash_16.gif" title="�������" alt="�������"/></a>
					</td>
				</tr>	
			<?}?>
			</table>
			<?$this->displayPageControl('editAddr');?>
		<?}else{?>
			<div>������� �� �������</div>
		<?}?>
	</form>
</div>
<script type="text/javascript" src="/modules/contacts/admin_contacts.js"></script>