<table class="order">
<tr>
<th>�����</th>
<th>�������</th>
<th>������</th>
<th>���������</th>
<th>��� ������</th>
<th>����� ������</th>
<th>��������</th>
<th>����������</th>
<th>��� ��</th>
<th>�����</th>
<th>������</th>
</tr>
<?foreach ($rs as $row) {?>
<tr>
<td><?=$row['orderid']?> <?=dte($row['create_time'])?></td>
<td><a href="/catalog/goods/<?=$row['p_id']?>/"><?=$row['name']?></a></td>
<td><?=dte($row['begin_date'])?></td>
<td><?=dte($row['end_date'])?></td>

<td class="center"><?=$row['volume_min']?></td>
<td class="center"><?=$row['volume_total']?></td>
<td><?=dte($row['delivery_date'])?></td>
<td><?=$row['phone']?></td>
<td class="center"><?=$row['count']?></td>
<td><?=$row['price']*$row['count']?></td>
<td><?=$row['pay_status']==1?'��':'���'?></td>
<td>
<?if($row['pay_status']==1){?>
<?if($row['time']){?>
������ <?=$row['time']?>
<?}else{?>

<a href="?act=confirm&id=<?=$row['id']?>" class="confirm">������</a>
<?}?>

<?/*a href="?act=return&id=<?=$row['id']?>">�������</a*/?>
<?}?>

</td>
</tr>	
<?}?>
</table>
<script type="text/javascript" src="/modules/vendor/order.js"></script>
