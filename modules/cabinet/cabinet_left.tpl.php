<div class="left_menu">
<div class="goods-menu">
<ul>
<?foreach (
	array(
		'/shop/'=>'�������� ������',
		'/shop/archives/'=>'����� �������',
		'/cabinet/'=>'������������ ������',
//		'/cabinet/password/'=>'�������� ������',
		'/catalog/basket/'=>'�������',
	) 

	as $href=>$desc) {?>
	<li><a href="<?=$href?>" class="menu <?if($this->getUri()==$href){?>act<?}?>"><?=$desc?></a></li>
<?}?>
<li><a href="?act=exit">�����</a></li>
</ul>
</div>
</div>