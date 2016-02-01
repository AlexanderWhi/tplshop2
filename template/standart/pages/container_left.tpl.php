<div class="left_menu">
<div class="goods-menu">
<ul>
<?foreach ($side_menu as $item) {?>
	<li class="<?if($item['mod_alias']==$this->mod_alias){?>act<?}?>"><a href="<?=$item['mod_alias']?>"><?=$item['mod_name']?></a></li>
<?}?>
</ul>
</div>
</div>