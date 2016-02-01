<?/*h2><?=$this->getHeader()?></h2*/?>
<?if($basket){?>

<form id="basket-form" action="?act=refresh"  class="common">
	
	<?=$this->getText('/basket/')?>
	
	<div class="basket_content">
	<?include('basket_content.tpl.php')?>
	<span class="error" id="error-basket" style="font-size:14pt"></span>
	</div>
	
	<?include("order_form.tpl.php")?>
	
</form>


<?/*div class="success" id="simple_order_success"><?=$this->getText('msg_basket_success')?></div*/?>
	
	
	<?//=$this->getText('basket_notice')?>
	
	
<?if(isset($related) && $catalog=$related){?>
<div id="catalog-content">
<br><br><br>
<h2>С этим товаром покупают</h2>
<?include('catalog_view_'.$this->getView().'.tpl.php')?>
</div>
<?}?>
	<div style="clear:both;height:50px"></div>
	
<?}else{?>
<div>Ваша корзина заказов пуста</div>
<?}?>
<script type="text/javascript" src="/datepicker/ui.datepicker.js"></script>

<script type="text/javascript">
var CUR_TIME=<?=time()?>;

</script>

<script type="text/javascript" src="/modules/catalog/basket.js"></script>