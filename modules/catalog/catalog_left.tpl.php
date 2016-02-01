<?global $get;?>
<?if($act=$this->getAction()){?>

<div class="left_menu">
<h3>Акции</h2>
<div class="goods-menu">
<ul>
<?foreach ($act as $item) {?>

<li class="<?if($this->getUriVal('action')==$item['id']){?>act<?}?>" ><a href="/catalog/action/<?=$item['id']?>/"><?=$item['title']?></a></li>
<?}?>

</ul>

</div>
</div>
<?}?>


<div class="left_menu">
<h3>Все категории</h3>
<form id="catalog-left" action="<?=$this->getUri(array('show'=>null,'prop'=>null))?>">
<?/*if($this->getUriVal('manid') || $get->get('p')){?>Наложен фильтр:
<a href="<?=$this->getUri(array('manid'=>null),false)?>">показать&nbsp;все</a><br>
<?}*/?>
<?if(!$this->getUriVal('goods')){?>
<div class="goods-menu">
<?if(isset($parentname)){?>
<h4><a href="#" class="unexpand"><?=@$parentname?></a></h4>
<?}?>

<?foreach ($this->getCatalog() as $node) {?>
<h4><?=$node['name']?></h4>
<?if(!empty($node['children'])){?>
<ul><?
foreach ($node['children'] as $item) {
	$href="/catalog/{$item['id']}";
	if($this->getUri()!='/'){
		$href=$this->getUri(array('catalog'=>(int)$item['id']));
	}
	if($item['c']||true){?><li class="<?if($this->getUriVal('catalog')==$item['id']){?>act<?}?>" ><a href="<?=$href?>"><?=$item['name']?></a> <?=$item['c']?></li><?}
}
?></ul>
	
<?}?>
<?}?>


</div>
<?}?>





<?if(!empty($prop_list2)){?>

<h4>Фильтры</h4>
<a class="expand" href="#" alt="Свернуть"><span>Раскрыть</span></a>
<?/*
<strong>Стоимость:</strong><br>
<br/>
<div id="price-slider" min="<?=$min_max_price[2]?>" max="<?=$min_max_price[3]?>"></div>
<br/>
<label>от</label>
<input name="minp" value="<?=$min_max_price[0]?>" class="mpr">
<label>до</label>
<input name="maxp" value="<?=$min_max_price[1]?>" class="mpr">
руб.


<br/>
*/?>
<?if(!empty($type_list)){?>
<br/>
<strong>Тип:</strong><br>
<?foreach ($type_list as $id=>$itm) {?>
	<input type="checkbox" id="type<?=$id?>" name="type[]" value="<?=$id?>" <?if(isset($_GET['type']) && @in_array($id,$_GET['type'])){?>checked<?}?>><label for="type<?=$id?>"><?=$itm?></label><br>
<?}?>
<?}?>






<div id="prop_sel" class=" <?if(empty($_GET['p']) && empty($_GET['manid']) && false){?>h<?}?>">

<?
$pnum=0;
foreach ($prop_list2 as $pid=>$itm) {?>

	<?if(!(count($itm['v'])==1 && ($vals=array_keys($itm['v'])) && in_array($vals[0],array('есть','true')))){
		$exists=isset($_GET['p'][$pid]);
		?>
	<div class="prop_item <?if($itm['sort']<1 && !$exists){?>h<?}?> type<?=$itm['type']?>">
	
	<?
	$prp=$_GET;
	if($exists && $prp['p'][$pid]){
		
		unset($prp['p'][$pid]);
		
		?>
	<a class="cls" href="<?=$this->getUri(array(),$prp)?>" title="очистить">X</a>
	<?}?>
	
	<?if($itm['type']!=1){?><h5><?=$itm['name']?></h5><?}?>
	<?if(false && preg_match('/shpt_/',$itm['type'])){?>
		<?foreach ($itm['v'] as $row) {?>
			<input id="p_<?=$pid?>_<?=$row?>" name="p[<?=$pid?>][]" type="checkbox" value="<?=$row?>" <?if(isset($_GET['p'][$pid]) && in_array($row,$_GET['p'][$pid])){?>checked<?}?>>
		
			<label for="p_<?=$pid?>_<?=$row?>">
			
			<?if(isset($itm['value_list'][$row])){?>
			<?=$itm['value_list'][$row]?>
			<?}else{?>
			<?=$row?>
			<?}?>
			
			</label><br>
		
		<?}?>
	<?}else{?>
	<?/*label><?=$itm['name']?></label><br>
	<select class="" name="p[<?=$pid?>]" id="p<?=$pid?>" <?if(isset($_GET['min'][$pid])){?>from="<?=$_GET['min'][$pid]?>"<?}?> <?if(isset($_GET['max'][$pid])){?>to="<?=$_GET['max'][$pid]?>"<?}?>>
	<option value="">--Не выбрано</option>
	<?foreach ($itm['v'] as $row) {?>
		<option value="<?=$row?>" <?if(isset($_GET['p'][$pid]) && $_GET['p'][$pid]==$row){?>selected<?}?>>
		<?if(isset($itm['value_list'][$row])){?>
		<?=$itm['value_list'][$row]?>
		<?}else{?>
		<?=$row?>
		<?}?>
		</option>
	<?}?>
	</select*/?>
	<?if($itm['type']==3 && count($itm['v'])>1){
		$vals=array_keys($itm['v']);
		sort($vals,SORT_NUMERIC);
		?>
	<div rel="<?=$pid?>" class="slider" values="<?=implode('|',$vals)?>"></div>
	<input name="p[<?=$pid?>]" id="p-<?=$pid?>" value="<?=@$_GET['p'][$pid]?>" type="hidden">
	<?}elseif($itm['type']==1){?>
		<input id="p<?=$pnum?>" type="checkbox" name="p[<?=$pid?>][]" value="1" <?if(isset($_GET['p'][$pid]) && is_array($_GET['p'][$pid]) && in_array(1,$_GET['p'][$pid])){?>checked<?}?> >
		<label for="p<?=$pnum?>" title="Всего товаров <?=count($itm['v'][1])?>"><?=$itm['name']?></label><br>
		<?$pnum++;?>
	<?}else{?>
		<?foreach ($itm['v'] as $v=>$c) {?>
		<input id="p<?=$pnum?>" type="checkbox" name="p[<?=$pid?>][]" value="<?=$v?>" <?if(isset($_GET['p'][$pid]) && is_array($_GET['p'][$pid]) && in_array($v,$_GET['p'][$pid])){?>checked<?}?> >
		<label for="p<?=$pnum?>" title="Всего товаров <?=$c?>"><?=$v?></label><br>
		<?$pnum++;}?>
	<?}?>
		
	
	
	
	
	<?}?>
	</div>
	<?}?>
<?}?>
<?
$hidden_exist=false;
foreach ($prop_list as $pid=>$itm) {?>
	<?if(count($itm['v'])==1 && in_array($itm['v'][0],array('есть','true'))){
		$checked=isset($_GET['p'][$pid]) && $_GET['p'][$pid]==$itm['v'][0];
		?>
	<div class="prop_item <?if($itm['sort']<1 && !$checked){?>h<?$hidden_exist=true;}?>">
	<input type="checkbox" id="pid<?=$pid?>" name="p[<?=$pid?>]" value="<?=$itm['v'][0]?>" <?if($checked){?>checked<?}?>><label for="pid<?=$pid?>"><?=$itm['name']?></label>
	</div>
	<?}?>
<?}?>




<?if( !empty($manufacturer_list)){?>
<div class="prop_item">
<h5>Производитель:</h5>
<?foreach ($manufacturer_list as $itm) {?>
	<input type="checkbox" id="manid<?=$itm['id']?>" name="manid[]" value="<?=$itm['id']?>" <?if(isset($_GET['manid']) && @in_array($itm['id'],$_GET['manid'])){?>checked<?}?>><label for="manid<?=$itm['id']?>"><?=$itm['name']?></label><br>
<?}?>
</div>
<?}?>




<?if($hidden_exist){?>
<br>
<a href="#" class="show_all">Показать все свойства</a>
<?}?>
</div>



<button type="submit">Найти</button>




<?}?>



<?if(false && !empty($data['manufacturer_list'])){?>	
<div class="goods-menu">
<h4><a href="" class="<?if(!$this->getUriVal('manid')){?>expand<?}else{?>unexpand<?}?>">Бренд</a></h4>
<ul class=" checkbox"><?
	foreach ($data['manufacturer_list'] as $item) {
		$manid=$cmanid=explode(',',$this->getUriVal('manid'));
		if(!in_array($item['id'],$manid)){
			$manid[]=$item['id'];
		}else{
			$manid=array_diff($manid,array($item['id']));
		}
		if(empty($manid)){
			$manid=null;
		}else{
			$manid=trim(implode(',',$manid),",");
		}
		?><li class="<?if(in_array($item['id'],$cmanid)){?>act<?}?>" ><a href="<?=$this->getUri(array('manid'=>$manid),true)?>"><?=$item['name']?></a> <?=$item['c']?></li><?
	}
	?></ul>
</div>
<?}?>	
	
	

</form>
</div>

<?if(!empty($manufacturer) && $this->getUri()!='/'){?>
<div class="left_menu man">
<h3>Бренды</h3>
<?foreach ($manufacturer as $item) {?>
<a href="/catalog/manid/<?=$item['id']?>" title="<?=$item['name']?>">
	<img src="<?=scaleImg($item['img'],'w80')?>">
</a>
<?}?>
</div>
<?}?>