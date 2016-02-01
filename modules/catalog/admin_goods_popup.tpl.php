<?
$mode=$this->getUriVal('mode');
?>

<form id="goods_item_popup" method="POST" action="?act=search">
	<?$this->displayPageControl();?>
	<?if($rs->getCount()>0){?>
	
		<table class="grid">
		<thead>
			<tr>
				<?if($mode!='one'){?>
				<th><input type="checkbox" name="all"/></th>
				<?}?>
				<th><?$this->sort('name','Название');?></th>
				<th>Описание</th>
				<th><?$this->sort('price','Стоимость');?></th>
				<th><?$this->sort('in_stock','В&nbsp;наличии');?></th>
			
			</tr>
			</thead>
			<tbody>
			<?while($rs->next()){?>
			<tr id="goods_item<?=$rs->get('id')?>">
				<?if($mode!='one'){?><td><input type="checkbox" name="sel_item[]" value="<?=$rs->get('id')?>" class="sel_item"/></td><?}?>
				<td>
				<?if($mode!='one'){?><strong><?=htmlspecialchars($rs->get("name"));?></strong>
				<?}else{?>
				<a href="javascript:select(<?=$rs->get('id')?>)"><strong><?=htmlspecialchars($rs->get("name"));?></strong></a>
				<?}?>
				</td>
				
				<td><small><?=$rs->get('description');?></small></td>
				<td class="price"><?=$rs->get('price')?></td>
				<td>
					<strong><?if(!$rs->get('in_stock')){?><span style="color:red">НЕТ</span><?}else{?><span style="color:green">ДА</span><?}?></strong>
				</td>
			</tr>	
			<?}?>
			</tbody>
		</table>
		<?//$this->displayPageControl();?>
	<?}else{?>
		<div>Список пуст</div>
	<?}?>
</form>
<script type="text/javascript" src="/modules/catalog/catalog_goods_popup.js"></script>