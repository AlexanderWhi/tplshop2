<!--#popup_favorite_box-->
<div id="favorite_box" class="popup_form">					
	<form>
	<span class="h1">В избранном <strong></strong>&nbsp; <span></span></span>
		<a class="close_popup_form" href="#"></a>
		<a href="#" class="continue close">продолжить</a>  <a href="/catalog/favorite/">перейти в избранное</a>
	</form>
</div>
<!--#/popup_favorite_box-->

<!--#basket_box-->
<div id="basket_box" class="popup_form">
	<form>
	<a class="close_popup_form" href="#"></a>			
	<div class="dialog_content">
	<span class="h1">Вы добавили</span>
<!--	<div class="img"></div>-->
		<a class="desc" href=""></a>
		
		<a href="/catalog/basket/#content" class="ord">Оформить заявку</a>
		<a href="#" class="continue close">продолжить </a>
	</div>
	</form>
</div>
<!--#/basket_box-->



<!--#order_box-->
<div id="order-report" class="popup_form">
	<form>
	<a class="close_popup_form" href="/shop/"></a>
	<div class="dialog_content">
	
		<span class="h1">Ваш заказ <strong id="order-num1">{order_num1}</strong></span>
		<?=$this->getText('order_report')?>

		<a href="/cabinet/" class="continue">Личный кабинет</a>
		
	</div>
	</form>
</div>
<!--#/order_box-->

<!--#popup_compare-->
<div id="compare_box" class="popup_form">					
	<form>
	<span class="h1">Товар добавлен в список сравнения</span>
		<a class="close_popup_form" href="#"></a>
		<span class="compare_desc">всего товаров в сравнении <strong></strong></span>
		<a href="#" class="continue close">продолжить</a>  <a href="/catalog/compare/<?if($cat=$this->getUriIntVal('catalog')){?>cat/<?=$cat?>/<?}?>">перейти в список сравнения</a>
	</form>
</div>
<!--#/popup_compare-->