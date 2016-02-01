<?=$this->getText($this->getType())?>
<?if($category_list){?>
<div class="public_category">
<a href="<?=$this->getUri(array('category'=>null))?>" class="<?=$this->getUriIntVal('category')==0?'act':''?>">Все</a>
<?foreach ($category_list as $k=>$d){?>
<a href="<?=$this->getUri(array('category'=>"$k"))?>" class="<?=$this->getUriIntVal('category')==$k?'act':''?>"><?=$d?></a>
<?}?>
</div>
<?}?>
<?if ($rs){?>
	<?include('public_list.tpl.php')?>
	<div class="page"><?=$this->displayPageNav($pg)?></div>
<?}else{?>
<div>Список пуст</div>
<?}?>