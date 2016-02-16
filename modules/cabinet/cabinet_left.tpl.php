<div class="left_menu">
<div class="goods-menu">
<ul>
<?foreach (
	array(
		'/shop/'=>'Активные заказы',
		'/shop/archives/'=>'Архив заказов',
		'/cabinet/'=>'Персональные данные',
            '/cabinet/reflink/'=>'Реферальная ссылка',
//		'/cabinet/password/'=>'Изменить пароль',
		'/catalog/basket/'=>'Корзина',
	) 

	as $href=>$desc) {?>
	<li><a href="<?=$href?>" class="menu <?if($this->getUri()==$href){?>act<?}?>"><?=$desc?></a></li>
<?}?>
<li><a href="?act=exit">Выход</a></li>
</ul>
</div>
</div>