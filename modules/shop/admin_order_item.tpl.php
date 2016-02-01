<?include('function.tpl.php')?>

<div id="dialog-goods" title="Товары" style="display:none;width:90%;height:90%" ></div>


<form id="order-form" method="POST" action="?act=save">
		<input type="hidden" name="id" value="<?=$id?>"/>
		<input type="hidden" name="userid" value="<?=$userid?>"/>
		<input type="hidden" name="ordernum" value="<?=$ordernum?>"/>
		<input type="hidden" name="mail" value="<?=$mail?>"/>
		<input type="hidden" name="name" value="<?=$last_name?> <?=$first_name?> <?=$middle_name?> ">
		<h1>Номер заказа - <?=$id?></h1>
		
		<div id="orderContent"><?=$data['orderContent']?></div>
		<?if($edit){?>
		<button type="button" class="button" name="recount">Пересчёт</button> (Наценка на заказ:<?=$margin?>%)
		<?}?>
		<?include('orderfields.tpl.php');?>
		<table class="form">	
		<tr><th style="width:200px">Стадия</th><td>
		<select name="order_status">
		<!--<option value="0">Не выбрано...</option>-->
		<?foreach ($order_status_list as $key=>$val) {?>
			<option value="<?=$key?>" <?=$key==$order_status?'selected="selected"':''?>><?=$val?></option>
		<?}?>
		</select></td></tr>
		
		<tr><th style="width:200px">Статус доставки</th><td>
		<select name="delivery_type">
		<option value="0">Не выбрано...</option>
		<?foreach ($delivery_type_list as $key=>$val) {?>
			<option value="<?=$key?>" <?=$key==$delivery_type?'selected="selected"':''?>><?=$val?></option>
		<?}?>
		</select></td></tr>
		<tr><th style="width:200px">Доставщик</th><td>
		<select name="driver">
		<option value="0">Не выбрано...</option>
		<?foreach ($driver_list as $row) {?>
			<option value="<?=$row['u_id']?>" <?=$row['u_id']==$driver?'selected="selected"':''?>><?=$row['name']?> [<?=$row['login']?>] (<?=$row['u_id']?>)</option>
		<?}?>
		</select></td></tr>
		
		
		</table>
		
		<?if($client_orders){?>
		<h2 style="cursor:pointer" onclick="if(document.getElementById('orders').style.display!='none'){document.getElementById('orders').style.display='none'}else{document.getElementById('orders').style.display=''}">+ Все заказы пользователя</h2>
			<table class="grid" id="orders" style="display:none">
				<tr>
					<th>Заказ</th>
					<th>Когда</th>
					<th>Куда</th>
					<th>Стоимость</th>
					<th>Статус</th>
				</tr>
				<?$n=0; foreach ($client_orders as $row){?>
					<tr class="text_mainpic2<?=($n++%2)?' sh':''?>">
						<td><a href="/admin/shop/?act=orderItem&id=<?=$row['id'];?>"><?=$row['id'];?></a></td>
						<td><?=$row['date'];?> <?=$row['time'];?></td>
						<td><?=parsAddr($row['address']);?></td>
						<td><?=$row['price']?> р.</td>
						<td><?=$order_status_list[$row['order_status']]?></td>
					</tr>
				<?}?>
			</table>
		<?}?>
		
		<?if($edit){?>
		Установить скидку <input name="user_discount" value="<?=$user_discount?>" style="width:50px"><button name="set_discount">Установить</button>
		<br />
		<?}?>
		<div>
		<h2>Комментарий оператора</h2>
		<textarea name="comment" style="width:400px;height:100px"><?=$comment?></textarea>
		</div>
		<br/>
<?if($edit){?>
		<input class="button" type="submit" name="save" value="Сохранить"/> 
			 
		
		<input class="button_long" type="button" name="save_with_notice" value="Уведомить покупателя"> 
<?}?>
		
		<input class="button" name="close" type="submit" value="Закрыть"/>
		<input class="button" type="button" name="print" value="Печать"/>		
		<input class="button_long" type="button" name="print_collect" value="Сборочная ведомость"/>		
		</form>
<script type="text/javascript" src="/modules/shop/admin_order_item.js"></script>