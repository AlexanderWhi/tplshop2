<ul>
<?
$i=0;
foreach ($this->getMenu() as $item) {
	?>
	<li class="mark<?=$i++?>" id="menu_<?=str_replace('/','_',trim($item['mod_alias'],'/'))?>"><a class="<?if($item['mod_alias']==$this->mod_uri){?>act<?}?>" href="<?=$item['mod_alias']?>"><?=$item['mod_name']?></a></li>
<?

}?>
</ul>


