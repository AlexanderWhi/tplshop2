<?include('function.tpl.php')?>
<?if($rs){?>
	<div class="noprint" style="display:none">
		<a href="#" class="expand-all"><img src="/img/pic/add_16.gif" title="Свернуть развернуть все"/></a>
	</div>
	<?$i=0;foreach ($rs as $item){?>
		<div class="<?=++$i!=count($rs)?'pagebreak':''?>" style="width:600px">
		
		<div class="noprint"  style="display:none">
		<a href="#" class="expand"><img src="/img/pic/add_16.gif" title="Свернуть развернуть"/></a>
		<a href="#" class="up"><img src="/img/pic/up_16.gif" title="На одну позицию выше"/></a>
		<a href="#" class="down"><img src="/img/pic/down_16.gif" title="На одну позицию ниже"/></a>
		<a href="#" class="remove"><img src="/img/pic/trash_16.gif" title="Удалить" ></a>		
		</div>
		
		<h4>Сборочная ведомость к заказу № <?=$item['id']?> от  <?=dte($item['create_time'],'d.m.Y H:i')?></h4>
		
		<table style="width:100%" class="grid">
		<tr><th style="width:200px">ФИО</th><td><?=$item['fullname']?> <?=$item['name']?></td></tr>
		<tr><th>Адрес</th><td><?=parsAddr($item['address']);?></td></tr>
		<tr><th>Дата доставки</th><td><?=dte($item['date'])?></td></tr>
		<tr><th>Время доставки</th><td><?=$item['time']?></td></tr>
		<tr><th>Дополнительно</th><td><?=$item['additionally']?></td></tr>
		</table>
		
		<div class="order">
		<br /><br />
		<?=$item['orderContent']?>
		
		
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
		
		<tr><td>Зона доставки:</td></tr>
		<tr><td>Водитель: <?=$item['d_name']?></td></tr>
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