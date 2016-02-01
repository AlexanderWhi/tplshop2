<?
$summ=0;$count=0;
if(empty($basket)){
	$basket=$this->getBasket();
}
foreach ($basket as $item){
	$summ+=$item['sum'];$count+=$item['count'];
	
}?>

<div id="basket" class="<?=$summ?'full':'clear'?>" >
<span>ќформить заказ</span>
<small class="cnt"><?=$count?></small> <strong><?=price($summ)?></strong>

</div>
