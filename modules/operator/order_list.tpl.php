<form method="POST">		
<?$pg->display();?>
<?if($rs){?>
<input class="button" type="button" name="print" value="��������"/>
<table class="grid data">
	<tr>
		<th><input type="checkbox" name="all"/></th>
		<th>�����</th>
		<th>��������</th>
		<th>����/����� ��������</th>
		<th>����</th>
		<th>���������,���.</th>
		<th>������,%</th>
		<th>��������,���.</th>
		<th>����,���.</th>
		<th>������</th>
	</tr>
	<?foreach($rs as $k=>$item){?>
	<tr>
		<td><input class="item" type="checkbox" name="item[]" value="<?=$item["id"];?>"/></td>
		<td><a href="<?=$this->mod_uri?>order/?id=<?=$item['id'];?>" title="��������">�<strong style="font-size:14px"><?=$item['id'];?></strong>
		<br /><?=str_replace(' ','&nbsp;',dte($item["create_time"],'d.m H:i:s'))?></a>
		</td>
		<td><i><?=$item["fullname"];?></i><br /><strong><?=$item["phone"];?></strong>
		<?if($item['type']=='user_1'){?><br /><i style="color:red"><?=$item['name']?></i><?}
		elseif($item['type']!='user'){?><br /><i style="color:green"><?=$item['name']?></i><?}?>
		</td>
		<td style="white-space:nowrap"><strong><?=dte($item['date']);?></strong><br /><i><?=$item['time']?></i></td>
		<td><i><?=$item['address'];?></i></td>
		<td><?=$item['price']?></td>
		<td><?=$item['discount']?></td>
		<td><?=$item['delivery']?><br />
		<strong><?=$this->delivery_type_list[$item['delivery_type']]?></strong>
		</td>
		<td><?=$item['total_price']?></td>
		<td><strong style="color:<?=$order_status_color[$item['order_status']]?>"><?=$order_status_list[$item['order_status']]?></strong></td>
	</tr>
	<?}?>
</table>
<?$pg->display();?>
<?}else{?>
<div>������ ����</div>
<?}?>
</form>
<script type="text/javascript" src="/modules/shop/admin_order.js"></script>