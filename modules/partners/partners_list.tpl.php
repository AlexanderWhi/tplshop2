<form method="POST">
		<?$this->displayPageControl();?>
		<?if($this->rs->getCount()>0){?>
			<table class="grid">
			<tr><th>��������</th><th>��������</th><th></th><th></th><th>������</th><th>���������</th><th>��������</th></tr>
			<?while($this->rs->next()){?>
				<tr>
					<td>
						<a href="<?=$this->mod_uri?>edit/?id=<?=$this->rs->get('id')?>"><?=$this->rs->get('name')?></a>
					</td>
					<td>
						<small>[<?=$this->rs->get('description')?>]</small>
					</td>
					<td>
						<img src="<?=$this->rs->get('img')?>">
					</td>
					<?/*td>
						<img src="<?=$this->rs->get('img1')?>">
					</td*/?>
					<td>
						<?=$this->status[$this->rs->get('state')]?>
					</td>
					<td>
						<strong><?=$this->rs->get('sort')?></strong>
					</td>
					<td style="width:120px">
						<a href="<?=$this->mod_uri?>edit/?id=<?=$this->rs->get('id')?>"><img src="/img/pic/edit_16.gif" title="�������������" alt=""/></a>
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
<script type="text/javascript" src="/modules/partners/admin_partners.js"></script>