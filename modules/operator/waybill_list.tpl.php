<form method="POST" action="?act=searchWaybill">
<a href="<?=$this->mod_uri?>waybillEdit/"><img src="/img/pic/add_16.gif"/>��������</a><?$pg->display();?><input class="button" type="button" name="print" value="��������"/><?=$data['driver_select']?><input class="button" type="submit" name="search" value="�����"/>
<?if($rs){?>

<table class="grid">
	<tr>
		<th><input type="checkbox" name="all"/></th>
		<th>�����</th>
		<th>������</th>
		<th>��������</th>
		<th>�������</th>
		<th>������������</th>
		<th>��������������</th>
	</tr>
	<?foreach($rs as $k=>$item){?>
		<tr>
<td><input class="item" type="checkbox" name="item[]" value="<?=$item["id"];?>"/></td>
<td><a href="<?=$this->mod_uri?>waybillEdit/?id=<?=$item['id'];?>" title="��������">� <strong style="font-size:14px"><?=$item['id'];?></strong></td>
<td>
<i><?=dte($item['time'],'d.m H:i')?></i>
</td>
<td>
<?if($item['name']){?>
<i><?=$item['name']?> - [<?=$item['car']?>]</i>
<?}else{?>
<strong>�� ������</strong>
<?}?>
</td>
<td><?=$item['a']?></td>
<td><?=$item['c1']?></td>
<td><?=$item['c2']?></td>

</tr>
<?}?>
</table>
<a href="<?=$this->mod_uri?>waybillEdit/"><img src="/img/pic/add_16.gif"/>��������</a><?$pg->display();?>
<?}else{?>
<div>������ ����</div>
<?}?>
</form>
<script type="text/javascript" src="/modules/operator/waybill_list.js"></script>