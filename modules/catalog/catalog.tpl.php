<?function in_stock($in_stock){
	if($in_stock){?>
<span class="in_stock">На складе</span>
<?}else{?>
<span class="not_in_stock">Нет в наличии</span>
<?}
}?>


<div id="catalog-content">


<?if($action){?>
<?if($action['img']){?>
<img src="<?=scaleImg($action['img'],'w880')?>">
<?}?>
<div class="catalog_action">
	С <?=fdte1($action['date'])?> по <?=fdte1($action['date_to'])?>
		<strong>
		<?$days=ceil((strtotime($action['date_to'])-time())/(3600*24) )+1 ?>
		<?if($days>-1){?>
		Осталось 
		<?=$days?> <?=morph($days,'день','дня','дней')?>
		<?}?>
		</strong>
</div>


<?=@$action['content']?>


<?if($catalog){?><h2>Товары, участвующие в акции</h2><?}?>
<?}?>



<!--наложен фильтр-->
<?
global $get;
if($this->getUriVal('manid') || ($get->getArray('p') && !empty($data['prop_list']))){?>
<div class="filter" style="display:none">

<table>
<?
$br=array();
$manid=explode(',',$this->getUriVal('manid'));
foreach ($data['manufacturer_list'] as $item) {
	if(in_array($item['id'],$manid)){
		$br[]=$item['name'];
	}
}?>
<?if($br){?>
<td>
<h4>Марка:</h4>
<h2 style="color:red">
<?=implode(', ',$br)?>
</h2>
</td>
<?}?>
<?
if(!empty($data['prop_list'])){
	$property=$get->getArray('p');
	?>	
<?foreach ($data['prop_list'] as $pid=>$prop) {
	$pr=array();
	foreach ($prop['v'] as $item) {
		if(@in_array($item,$property[$pid])){$pr[]=$item;}
	}
	
	?>
	<?if($pr){?>
		<td>
		<h4><?=$prop['name']?>:</h4>
			<h2 style="color:red"><?=implode(', ',$pr)?></h2>
		</td>	
	<?}?>
<?}?>
<?}?>
</table>
<a class="close" href="<?=$this->getUri(array('manid'=>null),false)?>"><span>очистить</a></a>
</div>
<?}?>

<!--/наложен фильтр-->


<?if(count(explode(',',$this->getUriVal('manid')))==1){?>
<div class="first_block">

	<?// echo $this->getText("/catalog/manid/{$this->getUriVal('manid')}/");?>
</div>
	
<?if($children_man){?>
<table class="children">
<tr><td>
<?$i=0;
foreach ($children_man as $item){?>
<?if($i++==ceil(count($children_man)/2)){?>
</td><td>
<?}

if($item['cmr'] || true){?>
	<div><a href="/catalog/<?=$item['id']?>/manid/<?=$this->getUriVal('manid')?>/"><?=$item['name']?></a><?=(int)$item['cmr']?></div>
	<?}}?>
</td></tr>
</table>
<?}?>


<?}?>

<?if($data['id'] && $this->isAdmin() ){
	echo '<a href="/admin/catalog/CatalogEdit/?id='.$data['id'].'" class="coin-text-edit" title="Редактировать описание каталога" style="margin-left:50px"></a>';
}?>
<div class="">
<?=$data['description']?>
</div>

<form>

<?

$ch_exists=false;
if($children){
foreach ($children as $item) {
	if($item['cr']){
		$ch_exists=true;
		break;
	}
}
}
if( $ch_exists){?>
<ul class="children">
<!--<?print_r($children)?>-->
<?$i=0;
foreach ($children as $item){?>
<?
if($item['cr'] ){?>
	<li><a href="<?=$this->getUri(array('catalog'=>(int)$item['id']),true)?>"><?=$item['name']?></a><span><?=(int)$item['cr']?></span></li>
	<?}}?>

</ul>
<?}?>
<!--------------------------->


<?if($catalog){?>
<table class="page_top" >
<td class="sort">
Сортировать: <?$this->displaySort()?>
</td>
<td class="view">
Вид:
<a class="cat_table <?if($this->getView()=='table'){?>act<?}?>"  href="javascript:filter('?act=chView&mode=table')"><span>таблица</span></a>
<a class="cat_list <?if($this->getView()=='list'){?>act<?}?>" href="javascript:filter('?act=chView&mode=list')"><span>список</span></a>
<!--<a class="cat_full <?if($this->getView()=='full'){?>act<?}?>"  href="?act=chView&mode=full">подробно</a>-->
</td>
<td class="right page_size">Показывать: <?$page->displayPageSize()?></td>
</table>
<?$this->displayPageNav($page)?>


<?include($this->getTpl('catalog_view_'.$this->getView().'.tpl.php'))?>


<?$this->displayPageNav($page)?>

<?if($catalog=$this->getLastView()){?>

<h2>Вы смотрели</h2>
<?include($this->getTpl('catalog_view_'.$this->getView().'.tpl.php'))?>
<?}?>



<?}elseif(!$action){?><div>Список пуст</div><?}?>
</form>
</div>
<?if($text=BaseComponent::getText("/catalog/{$id}")){?>
<div class="blk2">
<?=$text?>
</div>
<?}else{?>
<?=$this->getText("/catalog/{$id}")?>
<?}?>
<?/*form  class="common search" action="/catalog/?act=searchGoods" method="POST">

<input name="search" class="field" value="<?=isset($_GET['search'])?htmlspecialchars(trim(urldecode($_GET['search']))):''?>">
<button type="submit" class="button">Найти</button>
</form*/?>


<script type="text/javascript" src="/modules/catalog/catalog.js"></script>