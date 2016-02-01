<?function __render_menu_catalog($catalog,$deep=0){?>
	<ul>
<?
$i=0;
foreach ($catalog as $item) {
	?>
	<li class="mark<?=$i++?>"><a class="" href="<?=isset($item['mod_alias'])?$item['mod_alias']:"/catalog/".$item['id']."/"?>"><?=isset($item['mod_name'])?$item['mod_name']:$item['name']?> <span><?=@$item['cm']?></span></a>
	
	<?if(!empty($item['children'])){?>
	<?__render_menu_catalog($item['children'],$deep+1)?>
	<?}?>
	
	</li>
<?

}?>
</ul>
<?}?>



<?__render_menu_catalog($this->getMenuCatalog())?>