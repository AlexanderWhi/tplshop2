
<?//include('modules/cabinet/menu.tpl.php')?>


<?include('function.tpl.php')?>

<?$pay_system=$this->enum('sh_pay_system');?>
<form id="user_orders" class="common_block" action="?act=review">


<?if($rs){?>
	
<table class="order_list">
<thead class="head">
<tr>
<th>� ������</th>
<th>���� ������</th>
<th style="width:120px;text-align:right;">����� ������</th>
<th>���������</th>
<th>���������</th>
</tr>
</thead>


	<?foreach ($rs as $item){?>

<thead id="head<?=$item['id']?>" class="item_head">
<tr>
<th>
<a class="expand" href="#<?=$item['id']?>">
<?=$item['id']?>
</a>
</th>
<th><?=dte($item['create_time'])?></th>
<th style="text-align:right;"><?=price($item['total_price'])?></th>
<th>
<?=$item['order_status_desc']?>
</th>
<th>

<a class="repeat" href="?act=makeBasket&id=<?=$item['id']?>">���������</a>
</th>
</tr>
</thead>
<tbody id="body<?=$item['id']?>">
<tr>
<td colspan="5" class="item_content">
<?=$item['orderContent']?>

</td>
</tr>
</tbody>	

<?}?>


</table>
<?$this->displayPageNav($pg)?>

<?}else{?>
������ �� �������
<?}?>
</form>

<!--<a href="?act=exit" class="exit">����� �� ������� ��������</a>-->

<script type="text/javascript">
var ORDER_ID=<?=$orderId?>;

</script>
<script type="text/javascript" src="/modules/shop/order.js"></script>