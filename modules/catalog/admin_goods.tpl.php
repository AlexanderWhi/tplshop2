<?
$offer=$this->getUriIntVal('offer');
$imgmode=$this->getUriIntVal('imgmode');
?>


<div id="dialog-propgoods" title="��������� ��������" style="display:none;">
<form>

<div class="edit_prop"></div>
	<?
	/*$popup_mode=true;
	include('admin_goods_edit_prop.tpl.php')*/?>

	<br>
	<table id="add_prop" class="grid" style="width:auto">
	<tbody></tbody>
	
	<tfoot style="display:none">
	<tr>
	<td><input name="new_prop_name_">	</td>
	<td><input name="new_prop_value_" style="width:250px">	</td>
	<td><a href="#" class="remove">�������</a></td>
	</tr>
	</tfoot>
	
	</table>
	<a href="javascript:addProp()">�������� ��������</a>

</form>
</div>

<div id="dialog-mangoods" title="��������� �������������" style="display:none;">
<form>
<select name="manufecturer">
<option value="0">--�� �������</option>
<?foreach ($this->getGoodsManList() as $row) {?>
	<option value="<?=$row['id']?>"><?=$row['name']?></option>
<?}?>
</select>
</form>
</div>

<div id="dialog-actiongoods" title="��������� �����" style="display:none;">
<form>
<select name="action">
<option value="0">--�� �������</option>
<?foreach ($this->getGoodsActionList() as $row) {?>
	<option value="<?=$row['id']?>"><?=$row['title']?></option>
<?}?>
</select>
</form>
</div>


<form id="dialog-goods" title="������� ������" style="display:none;width:90%;height:90%" >
<select name="relation">
<?foreach ($shop_relation as $k=>$d) {?>
	<option value="<?=$k?>"><?=$d?></option>
<?}?>
</select>
<?if($this->isSu()){?>
<a href="/admin/enum/shop_relation/">�������������</a>
<?}?>
<div id="frame-content" style="height:450px"></div>
</form>


<div id="dialog-movegoods" title="����������� ��������� �������" style="display:none;">
<? 
   $tempcatalog = $this->getCatalog();
?>
<? function displayCatalog2($catalog,$selected,$deep=0,$n=0){
		foreach($catalog as $item){?>
			<option style="<?=($n++ % 2)?'background-color:#EEEEEE':''?>" value="<?=$item['id']?>" 
			<?=$item['id']==$selected?'selected':''?>>
			<?=str_repeat('&nbsp;&nbsp;&nbsp;|&nbsp;--&nbsp;',$deep)?>
			<?=$item['name']?>
			</option>
			<?if($item['children']){displayCatalog2($item['children'],$selected,$deep+1,$n);	}?>
		<? } ?>	
<? }
$temp_n=0;
?>
<p>�������� ���������:</p>

<select class="input" name="catalog-category" id="catalog-category">
<option style="<?=(($temp_n++ % 2)?'background-color:#EEEEEE':'')?>" value="0">[������ �� ������]</option>
<? displayCatalog2($catalog,$selected,0,$temp_n);?>
</select>
</div>




<div id="dialog-price-expr" title="��������� ����" style="display:none">
	PRICE = <input name="price_expr" value="<?=$price_expr?>">
	<br>
	��� <strong>PRICE</strong> - ������� ��������� ������
</div>


<form id="goods_item" method="POST" action="?act=search">








<!--<div>
| <a href="?act=goodsImg" target="_blank">������� ������</a>|
</div>-->

	<input type="button" name="sort" value="���������" class="button">
	<input type="button" name="delete" value="�������" class="button">
    <input type="button" name="removetocategory" value="�����������" class="button">
    <input type="button" name="propgoods" value="��������" class="button">
    <input type="button" name="price" value="����" class="button">
    <input type="button" name="Related" value="���������" class="button">
    <input type="button" name="mangoods" value="�������������" class="button_long">
    <input type="button" name="actiongoods" value="�����" class="button">
<br>
<br>
	<?$this->displayPageControl();?>
	
	<?if($rs->getCount()>0){?>
	
<!--	-->
	
<!--	-->
<!--	<input type="button" name="import" value="������" class="button"/>-->
	<br>
    
		<table class="grid">
			<thead>
			<tr>
				<th>
				<input type="checkbox" name="all"/>
				</th>
				<th><?$this->sort('img','����');?></th>
				<th><?$this->sort('name','��������');?></th>
				<th><?$this->sort('c_name','���������');?></th>
				<?if($offer){?>
				<th><?$this->sort('insert_time','��������');?></th>
				<th><?$this->sort('update_time','������');?></th>
				<?}else{?>
				<th><?$this->sort('m_name','�������������');?></th>
				<?}?>
				<?if($offer){?>
				<th>������� id</th>
				<?}?>
				<th><?$this->sort('price','���������');?></th>
				
				<th><?$this->sort('in_stock','�&nbsp;�������');?></th>
				
				<th><?$this->sort('sort','�� �������');?></th>
				<th><?$this->sort('sort1','�����');?></th>
				<?if($offer){?>
				<th>�����</th>
				<th><input type="checkbox" id="export_all"> <?$this->sort('export','�������');?></th>
				<?}?>
				<th><?$this->sort('sort2','���');?></th>
				<th><?$this->sort('sort3','�������');?></th>
				<th style="text-align:right">��������</th>
			</tr>
			</thead>
			<tbody>
			<?while($rs->next()){?>
			<tr id="goods_item<?=$rs->get('id')?>">
				<td>
				<input type="checkbox" name="item[]" value="<?=$rs->get('id')?>" class="item"/>
				</td>
				<td>
					<?/*if($rs->get('img')){?>
						<img src="<?=scaleImg($rs->get('img'),'w60h60')?>" class="img"/>
					<?}else{?>
						<img src="/img/admin/no_image.png"/>
					<?}*/?>
					<?if($rs->get('img')){?>
					<?if($imgmode){?>
					<img src="<?=scaleImg($rs->get('img'),'w100')?>">
					<?}else{?>
					<img src="/img/pic/photo.gif"/>
					<?}?>
					<?}?>
				</td>
				<td><a href="<?=$this->mod_uri?>goodsEdit/?id=<?=$rs->get('id');?>" title="�������������"><?=htmlspecialchars($rs->get("name"));?><?=$rs->get("product")?"/{$rs->get("product")}":''?></a></td>
				<td><small><?=$rs->get('c_name');?></small></td>
				
				<?if($offer){?>
				<td><small><?=$rs->get('insert_time');?></small></td>
				<td><small><?=$rs->get('update_time');?></small></td>
				<?}else{?>
				<td><small><?=$rs->get('m_name');?></small></td>
				<?}?>
				
				<?if($offer){?>
				<td><input name="ext_id[<?=$rs->get('id');?>]" value="<?=$rs->get('ext_id');?>" style="width:100px"/></td>
				<?}?>
				</td>
				<td class="price">
				<?if($offer){?>
					<input name="price[<?=$rs->get('id');?>]" value="<?=$rs->get('price')?>" style="width:50px"/>
					<?}else{?>
					<?=$rs->get('price')?>
				<?}?>
				</td>
				<td>
					<?if($offer){?>
					<input name="in_stock[<?=$rs->get('id');?>]" value="<?=$rs->get('in_stock')?>" style="width:50px"/>
					<?}else{?>
					<strong>
						<?if($rs->get('in_stock')==0){?>
							<span style="color:red">���</span>
						<?}elseif($rs->get('in_stock')==-1){?>
							<span style="color:red">���˨�</span>
						<?}else{?>
							<span style="color:green"><?=$rs->get('in_stock')?> <?=$rs->get('unit')?></span>
						<?}?></strong>
					<?}?>
					
				</td>
				
	
				
				<td>
				<input name="sort[<?=$rs->get('id');?>]" value="<?=$rs->get('sort');?>" style="width:20px"/>
				</td>
				<td>
				<input name="sort1[<?=$rs->get('id');?>]" value="<?=$rs->get('sort1');?>" style="width:20px"/>
				</td>
				<?if($offer){?>
				<td>
				<a href="/admin/catalog/relation/<?=$rs->get('id');?>/"><?=$rs->get('rc');?></a>
				
				</td>
				<td>
				<input type="checkbox" name="export[]" value="<?=$rs->get('id');?>" <?if($rs->get('export')==1){?>checked<?}?>>
				</td>
				<?}?>
				
				<td>
				<input name="sort2[<?=$rs->get('id');?>]" value="<?=$rs->get('sort2');?>" style="width:20px"/>
				</td>
				<td>
				<input name="sort3[<?=$rs->get('id');?>]" value="<?=$rs->get('sort3');?>" style="width:20px"/>
				</td>
				<td style="vertical-align:top;text-align:right">
					<?/*a class="scrooll" href="?method=onChangeStatus&id=<?=$rs->get('id');?>&status=<?=$rs->get('status')==0?'1':'0';?>"><img src="/img/pic/<?=$rs->get('status')==0?'redo_16.gif':'undo_16.gif';?>" title="<?=$rs->get('status')==0?'�� �������':'� �������';?>" alt=""/></a*/?>
					<a href="<?=$this->mod_uri?>goodsEdit/?id=<?=$rs->get('id');?>"><img src="/img/pic/edit_16.gif" title="�������������" alt="�������������"/></a>
					<a class="remove" href="?act=deleteGoods&id=<?=$rs->get('id');?>"><img src="/img/pic/trash_16.gif" title="������� ������" border="0" alt=""/></a>
				</td>
			</tr>	
			<?}?>
			</tbody>
		</table>
		<br>
		<?$this->displayPageControl(false);?>
	<?}else{?>
		<div>������ ����</div>
	<?}?>
</form>
<script type="text/javascript" src="/modules/catalog/catalog_goods.js"></script>