<ul class="tabs">
<?foreach (
	array(
		'/cabinet/'=>'Профиль',
		'/shop/'=>'История заказов',
//		'/shop/archives/'=>'Архив заказов',
		'/shopnote/'=>'Список покупок',
		
//		'/cabinet/password/'=>'Изменить пароль',
		'/catalog/basket/'=>'Корзина',
	) 

	as $href=>$desc) {?>
	<li><a href="<?=$href?>" class="menu <?if($this->getUri()==$href){?>act<?}?>"><?=$desc?></a></li>
<?}?>
<!--<a href="?act=exit">Выход</a>-->
</ul>