<?if($rs){?>
	<div class="noprint" style="display:none">
		<a href="#" class="expand-all"><img src="/img/pic/add_16.gif" title="Свернуть развернуть все"/></a>
	</div>
	<?$i=0;foreach ($rs as $item){?>
		<div class="<?=++$i!=count($rs)?'pagebreak':''?>" style="width:600px">
		<div class="noprint" style="display:none">
		<a href="#" class="expand"><img src="/img/pic/add_16.gif" title="Свернуть развернуть"/></a>
		<a href="#" class="up"><img src="/img/pic/up_16.gif" title="На одну позицию выше"/></a>
		<a href="#" class="down"><img src="/img/pic/down_16.gif" title="На одну позицию ниже"/></a>
		<a href="#" class="remove"><img src="/img/pic/trash_16.gif" title="Удалить" ></a>		
		</div>
		
		<h1>Заказ №<?=$item['ordernum']?></h1>
		<div class="order">
		<?=$item['orderContent']?>
		<?if($item['orderOldContent']){?>
		<h2>Первоначальный заказ</h2>
		<?=$item['orderOldContent']?>
		<?}?>
		<h2>Заказчик</h2>
		<table style="width:100%" class="grid">
		<tr><th style="width:200px">ФИО</th><td><?=$item['fullname']?></td></tr>
		<tr><th>Адрес</th><td><?=$item['address']?></td></tr>
		<tr><th>Телефон</th><td><?=$item['phone']?></td></tr>
		<?/*tr><th>E-Mail</th><td><?=$item['mail']?></td></tr*/?>
		</table>
		
		<h2>Описание заказа</h2>
		<table style="width:100%" class="grid">
		<tr><th style="width:200px">Время заказа</th><td><?=dte($item['create_time'],'d.m.Y H:i:s')?></td></tr>			
		<tr><th>Тип доставки</th><td><?=$item['delivery_type']?></td></tr>
		<tr><th>Дата доставки</th><td><?=dte($item['date'])?></td></tr>
		<tr><th>Время доставки</th><td><?=$item['time']?></td></tr>
		<tr><th>Дополнительно</th><td><?=$item['additionally']?></td></tr>
		</table>
		<?if($type=='collect'){?>
		<h2>Информация по заказу</h2>
		<table style="width:100%" class="grid">			
		<tr><th style="width:200px">ответственный за сборку</th><td><?=$item['name']?></td></tr>
		<tr><th>Передан в сборку</th><td><?=dte($item['time1'],'d.m.Y H:i:s')?></td></tr>
		<tr><th>Получен обратно</th><td><?=dte($item['time2'],'d.m.Y H:i:s')?></td></tr>
		</table>
		<?}?>
		<br /><br /><br /><br />
		<table >
		<tr>
		<td>
		Заказчик________________________
		</td>
		<td style="padding-left:50px">
		Исполнитель________________________
		</td>
		</tr>
		</table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
</div>
<hr />

		</div>
		<?
	}
}else{?>
Ничего не найдено	
	
<?}?>
<script type="text/javascript" src="/modules/shop/admin_order_print.js"></script>