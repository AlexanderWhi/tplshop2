<?include('function.tpl.php')?>
<form method="POST" action="?act=search">

<br>


	<table class="form">
		<tr>
			<td>
			<a href="<?=$this->getUri(array('show'=>'order'))?>">Заказы</a> | <a href="<?=$this->getUri(array('show'=>'perf'))?>">На исполнении</a> | 
<!--<a href="/admin/shop/deliv/">Управление доставкой</a>  <a href="/admin/shop/statistic/">Статистика</a>-->
			</td>
			</tr><tr>
			<td>
			<strong>Способ оплаты:</strong> <a href="<?=$this->getUri(array('pay_system'=>null))?>">Все</a>
<? foreach ($pay_system_list as $k=>$desc) {?>
	, <a href="<?=$this->getUri(array('pay_system'=>$k))?>"><?=$desc?></a>
<?}?>
			</td>
		</tr>
		<tr>
			<td>Статус заказа<?=$data['status_list']?>
			с<input class="date" name="from" value="<?=$this->getFilter('from')?>"/>
			по<input class="date" name="to" value="<?=$this->getFilter('to')?>"/>
			<button type="submit" name="search" >Показать</button><button type="button" name="print" >Печатать</button><button type="submit" name="export" >Экспорт XSL</button></td>
		</tr>
	</table>
	<?$pg->display()?> | <?$pg->displayPageSize()?>
		<?if($rs){?>
			<table class="grid data">
				<tr>
					<th><input type="checkbox" name="all"/></th>
					<th>Заказ</th>
<!--					<th>№</th>-->
<!--					<th>Исполнитель</th>-->
					<th>Заказчик</th>
					<th>Дата доставки</th>
				
					<th>Куда</th>
					<th>Стоимость,руб.</th>
					<?/*th>Скидка,%</th*/?>
					<th>Доставка,руб.</th>
					<th>Итог,руб.</th>
<!--					<th>-Бонус</th>-->
					<th>Статус</th>
					<th></th>
					
				</tr>
				<?foreach($rs as $k=>$item){?>
					<tr>
						<td><input class="item" type="checkbox" name="item[]" value="<?=$item["id"];?>"/></td>
						<td><a href="?act=orderItem&id=<?=$item['id'];?>" title="Просмотр"><strong style="font-size:14px">№<?=$item['id'];?></strong></a>
						<br /><?=dte($item["create_time"],'d.m.Y H:i')?>
						</td>
<!--						<td>№<?=$item['ordernum'];?></td>-->
						
						<td><i><?=$item["fullname"];?></i> <strong><?=$item["phone"];?></strong>
						<?=$item["mail"];?>
						
						</td>
						<td><strong><?=dte($item['date']);?></strong><br /><small><?=$item['time']?></small></td>
						
						
						<td><i><?=$item['city']?> <?=$item['address']?></i></td>

						<td><?=$item['price']?></td>
						<td><?=$item['delivery']?></td>
						<td><?=$item['total_price']?> <div><small><?=$item['pay_system']?></small> </div></td>						
						<?/*td><?=$item['pay_bonus']?></td*/?>						
						<td class="order_status<?=$item['order_status']?>">
						<?$ord_st=intval($item['order_status']);
						echo isset($order_status[$ord_st])?$order_status[$ord_st]:''?>
						
						<div><small><?=$item['delivery_type']?></small></div>
						<?=$item['pay_status']?>
						</td>
						<td>
						<?if(!in_array($this->getUser('type'),array('courier'))){
			?><a onclick="return confirm('Вы действительно хотите удалить запись?')" href="?act=remove&id=<?=$item['id'];?>"><img src="/img/pic/trash_16.gif" title="Удалить запись" border="0" alt=""/></a>
						<?
		}?>
							</td>
					</tr>
					
					
					
					
				<?}?>
			</table>
			<?$pg->display()?> | <?$pg->displayPageSize()?>
			<?}else{?>
			<div>Список пуст</div>
			<?}?>
			</form>
		</div>
<script type="text/javascript" src="/autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/modules/shop/admin_order.js"></script>