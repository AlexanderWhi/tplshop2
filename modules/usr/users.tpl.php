<form method="POST">

<a href="<?=$this->getUri(array('usertype'=>null))?>">���</a>
<?foreach ($usertype as $k=>$ut) {?>
	<a href="<?=$this->getUri(array('usertype'=>$k))?>"><?=$ut?></a> 
<?}?>
			<?$this->displayPageControl();?>
			<?if($this->rs->getCount()){?>
<table class="grid">
	<tr>
		
		<th style="white-space:nowrap"><?$this->sort('u_id','ID');?></th>
		<th style="white-space:nowrap"><?$this->sort('status','������');?></th>
		<th style="white-space:nowrap"><?$this->sort('login','�����');?></th>
		<?if($this->getURIVal('usertype')=='partner'){?>
		<th style="white-space:nowrap"><?$this->sort('city','�����');?></th>
		<th style="white-space:nowrap"><?$this->sort('company','�����');?></th>
		<?}else{?>
		<th style="white-space:nowrap"><?$this->sort('type','������');?></th>
		<?}?>
		<th style="white-space:nowrap"><?$this->sort('fio','���');?></th>
		<th>����</th>
		<th style="white-space:nowrap"><?$this->sort('reg_date','���� �����������');?></th>
		<th style="white-space:nowrap"><?$this->sort('visit','���������');?></th>
		<th style="white-space:nowrap"><?$this->sort('last_visit','����. ���.');?></th>
		<?/*th>���������</th*/?>
		<th style="white-space:nowrap"><?$this->sort('balance','������');?></th>
		<th style="white-space:nowrap"><?$this->sort('discount','������');?></th>
		<th></th>
	</tr>
	<? while($this->rs->next()){?>
		<tr>
			
			<td style="white-space:nowrap"><a href="<?=$this->mod_uri?>edit/?id=<?=$this->rs->get('u_id');?>"><?=htmlspecialchars($this->rs->get("u_id"));?></a></td>
			<td><?=$this->status[$this->rs->getInt('status')]?></td>
			<td style="white-space:nowrap">
				<a href="<?=$this->mod_uri?>edit/?id=<?=$this->rs->get('u_id');?>"><?=htmlspecialchars($this->rs->get("login"));?></a>
			</td>
			<?if($this->getURIVal('usertype')=='partner'){?>
			<td><strong><?=$this->rs->get('city');?></strong></td>
			<td><strong style="color:green"><?=$this->rs->get('company');?></strong></td>
			<?}else{?>
			<td><strong><?=$this->rs->get('type');?></strong></td>
			<?}?>
			
			<td>
				<small><?=htmlspecialchars(trim($this->rs->get("name")));?></small>	
			</td>
			<td>
				<small><?=htmlspecialchars(trim($this->rs->get("town").' '.$this->rs->get("phone").' '.$this->rs->get("mail")));?></small>	
			</td>
			<td title="<?=dte($this->rs->get('reg_date'),'d.m.y H:i:s');?>"><?=dte($this->rs->get('reg_date'),'d.m.y');?></td>
			<td><?=$this->rs->getInt('visit');?></td>
			<td title="<?=dte($this->rs->get('last_visit'),'d.m.y H:i:s');?>"><?=dte($this->rs->get('last_visit'),'d.m.y');?></td>
			<?/*td><?=$this->rs->get('f_messages');?></td*/?>
			<td><?=$this->rs->get('balance');?></td>
			<td><?=$this->rs->get('discount');?></td>
			<td style="white-space:nowrap;text-align:right">
				<a href="?act=asUser&id=<?=$this->rs->get('u_id');?>&type=<?=$this->rs->get('type')?>" title="������� � ������ ������� ������������"><img src="/img/pic/user_16.gif" /></a>
				
				<a href="<?=$this->mod_uri?>edit/?id=<?=$this->rs->get('u_id');?>"><img src="/img/pic/edit_16.gif" title="�������������" alt="�������������"/></a>
				<a onclick="return confirm('�� ������������� ������ ������� ������?')" href="?act=Remove&id=<?=$this->rs->get('u_id');?>"><img src="/img/pic/trash_16.gif" title="������� ������" border="0" alt=""/></a>
			</td>
		</tr>	
	<?}?>
</table>
<?$this->displayPageControl();?>
<?}else{?>
<div>������ ����</div>
<?}?>
</form>