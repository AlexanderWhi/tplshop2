<?
global $CONFIG;
$type_list=$this->getPropType()?>

<form id="dialog-add" method="POST" action="?act=PropsSave" title="������� ��������"  style="display:none;">
<input name="id" type="hidden">
<label>�������� ��������</label><br>
<input name="name"><br>

<?if($PropGroup=$this->getPropGroup()){?>
<label>
<a href="/admin/enum/sh_prop_grp/">
������
</a>

</label><br>
<select name="grp">
<option value="0">--�� �������</option>
<?foreach ($PropGroup as $v=>$dsc) {?>
	<option value="<?=$v?>"><?=$dsc?></option>
<?}?>
</select>

<a href="/admin/enum/sh_prop_grp/">
���.
</a>
<br>
<?}?>


<label>���</label><br>
<select name="type">
<?foreach ($type_list as $v=>$dsc) {?>
	<option value="<?=$v?>"><?=$dsc?></option>
<?}?>
</select>
</form>


<form id="props_item" method="POST" action="?act=search">
<a href="#" class="add"><img src="/img/pic/add_16.gif"></a>
	
	<?if($rs->getCount()){?>
	<input type="button" name="delete" value="�������" class="button"/>
	<input type="button" name="apply" value="���������" class="button"/>
		<table class="grid">
			<thead>
			<tr>
				<th>
				<input type="checkbox" name="all"/>
				</th>
				<th><?$this->sort('id','ID');?></th>

				
				<th><?$this->sort('name','��������');?></th>
				<th><?$this->sort('grp_desc','���������');?></th>
				<th><?$this->sort('type','���');?></th>
				<th><?$this->sort('sort','���������');?></th>
				<th><?$this->sort('cnt','���������� �������');?></th>
				<th style="text-align:right">��������</th>
			</tr>
			</thead>
			<tbody>
			<?while ($rs->next()) {?>
			<tr id="prop_item<?=$rs->get('id');?>">
				<td>
				<input type="checkbox" name="item[]" value="<?=$rs->get('id');?>" class="item"/>
				</td>
				<td><?=$rs->get('id')?></td>							
				
				<td><input name="name[<?=$rs->get('id');?>]" value="<?=htmlspecialchars($rs->get("name"));?>" style="width:300px"></td>
				<td><small><?=$rs->get('grp_desc');?></small></td>
				<td>
				<?if($rs->get('type')==4){?>
				<a href="/admin/enum/sh_prop_list_<?=$rs->get('id')?>/?&autoval&title=<?=htmlspecialchars($rs->get("name"));?>"><?=@$type_list[$rs->get('type')];?></a>
				<?}else{?>
				<small><?=@$type_list[$rs->get('type')];?></small>
				<?}?>
				
				</td>
				<td><input name="sort[<?=$rs->get('id');?>]" value="<?=$rs->get('sort');?>" style="width:30px; <?if($rs->getInt('sort')>0){?>background:#66ff66<?}?>"/></td>
				<td class="price"><a title="������ � ������ ���������" href="/admin/catalog/goods/?prop=<?=$rs->get('id');?>&category=0"><?=$rs->get('cnt')?></a></td>
				
				<td style="vertical-align:top;text-align:right">
					<?/*a class="scrooll" href="<?=$this->mod_uri?>goodsEdit/?id=<?=$rs->get('id');?>"><img src="/img/pic/edit_16.gif" title="�������������" alt="�������������"/></a*/?>
					<?if($this->isSu()){?>
					<a class="merge"  rel="<?=$rs->get('id');?>" href="#<?=$rs->get('id');?>" title="���������� ������ ������� ������ � ��������� ������">M</a>
					<?if($rs->get('type')==3){?>
					<a class="to_num" href="?act=PropToNum&id=<?=$rs->get('id');?>" title="������������ �������� � �����">[0..9]</a>
					
					<?}?>
					<?}?>
					<a href="javascript:edit(<?=$rs->get('id');?>,'<?=$rs->get('name');?>','<?=$rs->get('type')?>','<?=$rs->get('grp')?>')" title="�������������" ><img src="/img/pic/edit_16.gif"></a>
					<a class="remove" rel="<?=$rs->get('id');?>" href="#<?=$rs->get('id');?>"><img src="/img/pic/trash_16.gif" title="������� ������" alt="������� ������"></a>
				</td>
			</tr>	
			<?}?>
			</tbody>
		</table>
	<?}else{?>
		<div>������ ����</div>
	<?}?>
	<button type="button" name="close" class="button">�������</button>
</form>
<script type="text/javascript" src="/modules/catalog/admin_props.js"></script>