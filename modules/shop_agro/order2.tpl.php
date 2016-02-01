<div id="tabs">
<?include('modules/cabinet/menu.tpl.php')?>
<div id="t1" class="tabs">

<?include('function.tpl.php')?>

<?$pay_system=$this->enum('sh_pay_system');?>
<form id="user_orders" class="common_block" action="?act=review">


<?if($rs){foreach ($rs as $item){?>
	<h2  id="order<?=$item['id']?>">Заказ №<?=$item['id']?><?//=$item['ordernum']?></h2>
		
	<?=$item['orderContent']?>
				
	<table>
		<tr><td>Получатель</td><td><strong><?=$item['fullname']?></strong></td></tr>		
		<tr><td>Адрес</td><td><strong>
		<?/*<?=$item['country']?>,
		<?=$item['region']?>,
		,*/?>
		<?=$item['city']?> <?=parsAddr($item['address']);?>
		
		</strong></td></tr>
		
		
		<?if(!empty($delivery_type_list[$item['delivery_type']])){?><tr><td>Тип доставки</td><td><strong><?=@$delivery_type_list[$item['delivery_type']]?></strong></td></tr><?}?>
		
		
		<?if(!empty($pay_system_list[$item['pay_system']])){?><tr><td>Тип оплаты</td><td><strong><?=@$pay_system_list[$item['pay_system']]?></strong>
		<?if($item['pay_system']==1){?>
		<a href="/prnt/SBERpdf/?id=<?=$item['id']?>">Распечатать счёт</a>
		<?}?>
		
		</td></tr><?}?>
		
		<?if(dte($item['date'])){?><tr><td>Дата доставки</td><td><strong><?=dte($item['date'])?></strong></td></tr><?}?>
		
		<?if($item['time']){?><tr><td>Время доставки</td><td><strong><?=$item['time']?></strong></td></tr><?}?>
		<tr><td>Дополнительно</td><td><strong><?=$item['additionally']?></strong></td></tr>
		<tr><td>Статус</td><td>
			<strong style="color:green"><?=$item['order_status_desc']?></strong>
		</td></tr>
		<?if(false && $item['order_status']!=0){?>
			<tr>
			<td>Оплата <?/*small style="color:green">(<?=$pay_system[$item['pay_system']]?>)</small*/?></td>
			<td>
			
			<?if($this->getUser('company')){?>
			<a href="/prnt/SBERpdf/?id=<?=$item['id']?>">Распечатать счёт</a>
			<?}?>
			<?if(empty($item['pay_status'])){?>
				
				<?foreach ($data['ps'] as $nme=>$ps){?>
				<?if($nme=='qiwi'){?><a href="<?=$ps->getUrl()?>"><?=$ps->getPsDesc()?></a><br><?}?>
				<?if($nme=='payonline'){
					$ps->params['OrderNum']=$item['id'];	
					
					?><?=$ps->getForm(array('Amount'=>$item['total_price'],'OrderId'=>$item['ordernum']))?><br>
				<?}?>
				
				<?}?>
			
			<?}else{?>
				<strong style="color:green">оплачен <?=dte($item['pay_time'],'d.m.Y H:i')?></strong>
			<?}?>
		
			</td>
			
			</tr>
		
		<?}?>
	</table>
	<?if(false && $arh){?>
	<button class="open_review" type="button">Оставить отзыв</button>
	<div id="review<?=$item['id']?>" style="display:none">
	<textarea name="review"><?=htmlspecialchars($item['review'])?></textarea>
	<button class="save_review" type="button">Сохранить отзыв</button>
	</div>
	<?}?>
	<?/*
	<a class="button_long" href="?act=makeBasket&id=<?=$item['id']?>">Повторить заказ</a>*/?>
	<br />
	<br />
	<br />
	<br />
	<br />
<?}?>
<div class="page"><?$pg->display();?></div>

<?}else{?>
Ничего не найдено
<?}?>
</form>


</div>

</div>
<script type="text/javascript">
var ORDER_ID=<?=$orderId?>;

</script>
<script type="text/javascript" src="/modules/shop/order.js"></script>