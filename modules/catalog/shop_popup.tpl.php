<!--#popup_favorite_box-->
<div id="favorite_box" class="popup_form">					
	<form>
	<span class="h1">� ��������� <strong></strong>&nbsp; <span></span></span>
		<a class="close_popup_form" href="#"></a>
		<a href="#" class="continue close">����������</a>  <a href="/catalog/favorite/">������� � ���������</a>
	</form>
</div>
<!--#/popup_favorite_box-->

<!--#basket_box-->
<div id="basket_box" class="popup_form">
	<form>
	<a class="close_popup_form" href="#"></a>			
	<div class="dialog_content">
	<span class="h1">�� ��������</span>
<!--	<div class="img"></div>-->
		<a class="desc" href=""></a>
		
		<a href="/catalog/basket/#content" class="ord">�������� �����</a>
		<a href="#" class="continue close">���������� �������</a>
	</div>
	</form>
</div>
<!--#/basket_box-->



<!--#order_box-->
<div id="order-report" class="popup_form">
	<form>
	<a class="close_popup_form" href="/shop/"></a>
	<div class="dialog_content">
	
		<span class="h1">��� ����� <strong id="order-num1">{order_num1}</strong></span>
		<?=$this->getText('order_report')?>

		<a href="/cabinet/" class="continue">������ �������</a>
		
	</div>
	</form>
</div>
<!--#/order_box-->

<!--#popup_compare-->
<div id="compare_box" class="popup_form">					
	<form>
	<span class="h1">����� �������� � ������ ���������</span>
		<a class="close_popup_form" href="#"></a>
		<span class="compare_desc">����� ������� � ��������� <strong></strong></span>
		<a href="#" class="continue close">����������</a>  <a href="/catalog/compare/<?if($cat=$this->getUriIntVal('catalog')){?>cat/<?=$cat?>/<?}?>">������� � ������ ���������</a>
	</form>
</div>
<!--#/popup_compare-->