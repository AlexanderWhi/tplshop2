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
		
		<?if($this->getURIVal('usertype')=='partner'){?>
		<th style="white-space:nowrap"><?$this->sort('city','�����');?></th>
		<th style="white-space:nowrap"><?$this->sort('company','�����');?></th>
		<?}else{?>
		<th style="white-space:nowrap"><?$this->sort('status','������');?></th>
		<th style="white-space:nowrap"><?$this->sort('login','�����');?></th>
		<th style="white-space:nowrap"><?$this->sort('type','������');?></th>
		<th style="white-space:nowrap"><?$this->sort('fio','���');?></th>
		<?}?>
		
		<th>����</th>
		<th style="white-space:nowrap"><?$this->sort('reg_date','���� �����������');?></th>
		<th style="white-space:nowrap"><?$this->sort('visit','���������');?></th>
		<th style="white-space:nowrap"><?$this->sort('last_visit','����. ���.');?></th>
		
		<th style="white-space:nowrap"><?$this->sort('balance','������');?></th>
		<th style="white-space:nowrap"><?$this->sort('discount','������');?></th>
		<th></th>
	</tr>
	<? while($this->rs->next()){?>
		<tr>
			
			<td style="white-space:nowrap"><a href="javascript:select(<?=$this->rs->get('u_id');?>)"><?=$this->rs->get("u_id");?></a></td>
			
			<?if($this->getURIVal('usertype')=='partner'){?>
			<td><strong><?=$this->rs->get('city');?></strong></td>
			<td><strong style="color:green"><?=$this->rs->get('company');?></strong></td>
			<?}else{?>
			<td><?=$this->status[$this->rs->getInt('status')]?></td>
			<td style="white-space:nowrap">
				<a href="javascript:select(<?=$this->rs->get('u_id');?>)"><?=htmlspecialchars($this->rs->get("login"));?></a>
			</td>
			<td><strong><?=$this->rs->get('type');?></strong></td>
			<td><small><?=htmlspecialchars(trim($this->rs->get("name")));?></small>	</td>
			<?}?>
			
			
			<td>
				<small><?=htmlspecialchars(trim($this->rs->get("town").' '.$this->rs->get("phone").' '.$this->rs->get("mail")));?></small>	
			</td>
			<td title="<?=dte($this->rs->get('reg_date'),'d.m.y H:i:s');?>"><?=dte($this->rs->get('reg_date'),'d.m.y');?></td>
			<td><?=$this->rs->getInt('visit');?></td>
			<td title="<?=dte($this->rs->get('last_visit'),'d.m.y H:i:s');?>"><?=dte($this->rs->get('last_visit'),'d.m.y');?></td>
			<?/*td><?=$this->rs->get('f_messages');?></td*/?>
			<td><?=$this->rs->get('balance');?></td>
			<td><?=$this->rs->get('discount');?></td>
		</tr>	
	<?}?>
</table>
<?$this->displayPageControl();?>
<?}else{?>
<div>������ ����</div>
<?}?>
</form>
<script type="text/javascript">
function select(id){
	window.parent.window._selectUsr(id);
}

</script>