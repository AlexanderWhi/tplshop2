<div>
<form method="POST">

<?$this->displayPageControl();?>
<?if($rs->getCount()){?>
<table class="grid">
	<tr>
		<th></th>
		<th>��������</th>
		<th>���� ������</th>
		<th>���� �����</th>
		<th>�����</th>
		<th>�������</th>
		<th>������</th>
		<th>��������</th>
	</tr>
	<? while($rs->next()){?>
		<tr>
			<td style="width:200px">
			<?if(isImg($rs->get('file'))){?>
			<img src="<?=scaleImg($rs->get('file'),'w200h100')?>">
			<?}?>
			
			</td>
			<td style="width:200px">
				 <a href="?act=edit&id=<?=$rs->get('id');?>"><small>[<?=htmlspecialchars($rs->get("description"));?>]</small></a>
			</td>
			<td><?=$rs->get("start_date");?></td>
			<td><?=$rs->get("stop_date");?></td>
			<td>[<?=$rs->get("place");?>] <?=$rs->get("pl_description");?></td>
			<td><?=$rs->get("show_ad");?></td>
			<td><?=$rs->get("click");?></td>
			<td style="width:100px">
				<a class="scrooll" href="?act=edit&id=<?=$rs->get('id');?>"><img src="/img/pic/edit_16.gif" title="�������������" alt="�������������"/></a>
				<a class="scrooll " onclick="return confirm('�����?')" href="?act=reset&id=<?=$rs->get('id')?>"><img src="/img/pic/undo_16.gif" title="�����" alt="�����"/></a>
				<a class="scrooll" onclick="return confirm('�� ������������� ������ ������� ������?')" href="?act=remove&id=<?=$rs->get('id');?>"><img src="/img/pic/trash_16.gif" title="������� ������" border="0" alt=""/></a>
			</td>
		</tr>	
	<?}?>
</table>
<?$this->displayPageControl();?>
<?}else{?>
<div>������ ����</div>
<?}?>
</form>
</div>