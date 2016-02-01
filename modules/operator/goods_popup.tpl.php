<form id="goods_popup_form" method="POST">
	<input name="search" value="<?=$search?>"/><button type="submit">�����</button> <?=$data['CATALOG_SELECT'];?><?=$data['PAGE_SELECT'];?><br>
	<?$pg->display();?>
	<?if($rs->getCount()>0){?>
		<table class="grid">
			<thead>
			<tr>
				<th>���-��</th>
				<th><?$this->sort('name','��������');?></th>
<!--				<th>��������</th>-->
				<th><?$this->sort('price','���������');?></th>
				<th><?$this->sort('in_stock','�&nbsp;�������');?></th>
			</tr>
			</thead>
			<tbody>
			<?while($rs->next()){?>
			<tr id="goods_item<?=$rs->get('id')?>">
				<td><input class="count <?=$rs->get('weight_flg')==1?'weight':''?>" name="sel_item[<?=$rs->get('id')?>]" style="width:30px;text-align:center" value="1"/></td>
				<td><?=$this->popupSearch?preg_replace('='.preg_quote($this->popupSearch).'=i','<span style="color:red">\0</span>',$rs->get('name')):$rs->get('name')?></td>
				<?/*td><small><?=$rs->get('description');?></small></td*/?>
				<td class="price"><?=$rs->get('price')?></td>
				<td>
					<strong><?if(!$rs->get('in_stock')){?><span style="color:red">���</span><?}else{?><span style="color:green">��</span><?}?></strong>
				</td>
			</tr>	
			<?}?>
			</tbody>
		</table>
		<?$pg->display();?>
	<?}else{?>
		<div>������ ����</div>
	<?}?>
</form>
<script type="text/javascript" src="/autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/scroll/mousewheel.js"></script>
<script type="text/javascript" src="/modules/catalog/shop.js"></script>
<script type="text/javascript" src="/modules/operator/goods_popup.js"></script>