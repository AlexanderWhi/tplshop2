<div id="dialog-goods" title="Товары" style="display:none;width:90%;height:90%" ></div>

<form id="order-form" method="POST" action="?act=saveOrder">
		<input type="hidden" name="id" value="<?=$id?>"/>
		<?/*input type="hidden" name="mail" value="<?=$mail?>"/>
		<input type="hidden" name="name" value="<?=$name?>"/*/?>
		
<!--		<h2>Корзина заказа</h2>-->
		<div id="basket"><?=$data['orderContent']?></div>
		<button name="add">Добавить</button> <button name="refresh">Применить</button>
		
		
		<?if($data['orderOldContent']){?>
		<h2>Изначальное содержимое заказа</h2>
		<div><?=$data['orderOldContent']?></div>
		<?}?>
		
		

		<h2>Заказчик</h2>
		<table style="width:100%" class="form2 grid">
		<tr><th style="width:200px">ФИО</th><td><input style="width:400px;" name="fullname" value="<?=$fullname?>"></td></tr>
		<tr><th>Адрес</th><td><input style="width:400px;" name="address" value="<?=$address?>"> <a href="#" id="update-distance">Уточнить стоимость доставки</a> <span id="update-distance-msg" style="color:blue"></span></td></tr>
		<tr><th>Телефон</th><td><input name="phone" value="<?=$phone?>"></td></tr>
		<?if(!empty($mail)){?><tr><th>E-Mail</th><td><?=$mail?></td></tr><?}?>
		</table>
		
		<h2>Описание заказа</h2>
		<table style="width:100%" class="form2 grid">
		<tr><th style="width:200px">Время заказа</th><td><?=dte($create_time,'d.m.Y H:i:s')?></td></tr>
		<tr><th>Тип доставки</th><td>
		<select name="delivery_type">
			<?foreach ($delivery_type_list as $key=>$val) {?>
				<option value="<?=$key?>" <?=$key==$delivery_type?'selected="selected"':''?>><?=$val?></option>
			<?}?>
		</select>
		</td></tr>
		<tr><th>Дата доставки</th><td><input name="date" value="<?=dte($date)?>"></td></tr>
		<tr><th>Время доставки</th><td><input style="width:400px;" name="time" value="<?=$time?>"></td></tr>
		<tr><th>Дополнительно</th><td>
		<textarea style="width:400px;height:100px" name="additionally"><?=$additionally?></textarea>
		</td></tr>
		</table>
		<br />
		
		<?if($order_log){?>
		<h2>История заказа</h2>
		<table class="grid" style="width:auto">
		<?foreach ($order_log as $row) {?>
			<tr><td><?=$row[0]?></td><td>
			<strong style="color:<?=$order_status_color[$row[1]]?>"><?=$order_status_list[$row[1]]?></strong>
			</td></tr>
		<?}?>
		</table>
		<?}?>
		<h2>Статус заказа</h2>
		<table style="width:100%" class="grid">
		<tr><th style="width:200px">Стадия</th><td>
		<select name="order_status">
		<?foreach ($order_status_list as $key=>$val) {?>
			<option value="<?=$key?>" <?=$key==$order_status?'selected="selected"':''?>><?=$val?></option>
		<?}?>
		</select></td></tr>

		<?/*if($performer){?>
		<tr><th>Исполнитель</th><td><?=$performer?>
		</td></tr>
		<?}*/?>
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
					<tr>
						<td><?=$row['id'];?></td>
						<td><?=dte($row['date']);?> <small><?=$row['time'];?></small></td>
						<td><?=$row['address'];?></td>
						<td><?=$row['price']?> р.</td>
						<td>
						<strong style="color:<?=$order_status_color[$row['order_status']]?>"><?=$order_status_list[$row['order_status']]?></strong>
						</td>
					</tr>
				<?}?>
			</table>
		<?}?>

		<br />
		
		<table class="grid">
		<tr>
		<td><h2>Комментарий по заказу</h2></td>
		<?if(isset($user_comment)){?><td><h2>Комментарий по пользователю</h2></td><?}?>
		<td><h2>Отзыв</h2></td>
		</tr>
		<tr>
		<td><textarea name="comment" style="width:300px;height:100px"><?=$comment?></textarea></td>
		<?if(isset($user_comment)){?><td><textarea name="user_comment" style="width:300px;height:100px"><?=$user_comment?></textarea></td><?}?>
		<td><textarea name="review" style="width:300px;height:100px"><?=$review?></textarea></td>
		</tr>
		</table>
		
		<br/>

		<input class="button" type="submit" name="save" value="Сохранить"/> 
<!--		<input class="button_long" type="button" name="save_with_notice" value="Сохранить с уведомлением"/> -->
		
		<input class="button" name="close" type="submit" value="Закрыть"/>
		<input class="button" type="button" name="print" value="Печать"/>		
		<input class="button_long" type="button" name="print1" value="Печать для сборки"/>		
</form>
<script type="text/javascript">
var delivery_list=<?=$data['delivery_list']?>;
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript" src="/modules/operator/order.js"></script>