<ul class="tabs">
<?foreach (
	array(
		'/cabinet/'=>'�������',
		'/shop/'=>'������� �������',
//		'/shop/archives/'=>'����� �������',
		'/shopnote/'=>'������ �������',
		'/cabinet/reflink/'=>'����������� ������',
		
//		'/cabinet/password/'=>'�������� ������',
		'/catalog/basket/'=>'�������',
	) 

	as $href=>$desc) {?>
	<li><a href="<?=$href?>" class="menu <?if($this->getUri()==$href){?>act<?}?>"><?=$desc?></a></li>
<?}?>
<!--<a href="?act=exit">�����</a>-->
</ul>