<div>
			<form method="POST" id="content">
			<?$this->displayPageControl();?>
			<?if($this->rs->getCount()){?>
			<button name="remove">�������</button>
			<table class="grid">
				<tr>
					<th>
					<input type="checkbox" name="all">
					</th>
					<th>��������</th>
					<th>��������</th>
					<th style="white-space:nowrap;text-align:right">��������</th>
				</tr>
				<?while($this->rs->next()){?>
					<tr>
					<td>
					<input type="checkbox" name="item[]" value="<?=$this->rs->get('c_id');?>">
					</td>
					
						<td><a class="scrooll" href="?act=edit&id=<?=$this->rs->get('c_id');?>"><strong>[<?=htmlspecialchars($this->rs->get("c_name"));?>]</strong></a></td>
						<td><small><?=substr(strip_tags($this->rs->get("c_text")),0,200);?>...</small></a></td>
						<td style="white-space:nowrap;text-align:right">
							<a href="?act=edit&id=<?=$this->rs->get('c_id');?>"><img src="/img/pic/edit_16.gif" title="�������������" alt="�������������"/></a>
							<a onclick="return confirm('�� ������������� ������ ������� ������?')" href="?act=remove&id=<?=$this->rs->get('c_id');?>"><img src="/img/pic/trash_16.gif" title="������� ������" border="0" alt=""/></a>
						</td>
					</tr>	
				<?}?>
			</table>
			<button name="remove">�������</button>
			<?$this->displayPageControl();?>
			<?}else{?>
			<div>������ ����</div>
			<?}?>
			</form>
		</div>
<script type="text/javascript" src="/core/component/content/content.js"></script>