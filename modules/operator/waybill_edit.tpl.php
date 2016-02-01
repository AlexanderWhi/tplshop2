<div id="dialog-orders" title="Заказы" style="display:none;width:90%;height:90%" ></div>
<form id="waybill-form" method="POST" action="?act=waybillSave">
		<input type="hidden" name="id" value="<?=$id?>"/>
		<h1>Номер - <?=$id?></h1>
		<div id="orders"><?=$data['orders']?></div>
		<button name="add">Добавить</button>
		<button name="delete">Удалить</button>
		<button name="print_orders">Печатать</button>
		<h2>Водитель</h2>
		<div>
		<select name="driver">
		<option value="0">Не выбран</option>
		<?foreach ($driver_list as $item){?>
		<option value="<?=$item['id']?>" <?=$driver==$item['id']?'selected':''?>><?=$item['name']?>-[<?=$item['car']?>]</option>
		<?}?>
		</select>
		</div>
		<h2>Установить статус</h2>
		<select name="order_status">
		<option value="0">Не выбран</option>
		<?foreach ($status_list as $item){?>
		<option value="<?=$item?>"><?=$order_status_list[$item]?></option>
		<?}?>
		</select>
		<hr>		
		<input class="button" type="submit" name="save" value="Сохранить"/> 
		<input class="button" name="close" type="submit" value="Закрыть"/>
		<input class="button" type="button" name="print" value="Печать"/>		
</form>
<script type="text/javascript" src="/modules/operator/waybill_edit.js"></script>