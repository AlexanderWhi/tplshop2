<?
global $CONFIG;
$type_list=$this->getPropType()?>

<form id="dialog-add" method="POST" action="?act=ManSave" title="�������"  style="display:none;" enctype="multipart/form-data">
<input name="id" type="hidden">
<label>�������������</label><br>
<input name="name"><br>
<label>�����������</label><br>
<input type="file" name="image">

<br>
<label>��������</label><br>
<textarea name="description" class="tiny" style="width:100%;height:300px"></textarea>
</form>


<form id="props_item" method="POST" action="?act=applyMans" enctype="multipart/form-data" target="fr">
<iframe name="fr" style="display:none"></iframe>
<table class="form">
<td><a href="#" class="add"><img src="/img/pic/add_16.gif">��������</a></td>
<td style="text-align:right">
<input type="button" name="apply" value="���������" class="button"/>
	<input type="button" name="delete" value="�������" class="button"/>
	<?if($this->isSu()){?>
	<input type="button" name="delete_all" value="������� ���!" class="button"/>
	<?}?>
</td>

</table>

	<br>
	<?if($man){?>
	
		<table class="grid">
			<thead>
			<tr>
				<th>
				<input type="checkbox" name="all"/>
				</th>
				<th><?$this->sort('id','ID');?></th>
				<th>�����������</th>
				<th><?$this->sort('name','��������');?></th>
				<th>��������</th>
				<th><?$this->sort('sort','���������');?></th>
				<th>�������</th>
			</tr>
			</thead>
			<tbody>
			<?foreach ($man as $row) {?>
			<tr id="prop_item<?=$row['id'];?>">
				<td>
				<input type="checkbox" name="item[]" value="<?=$row['id'];?>" class="item">
				</td>
				<td><?=$row['id']?></td>							
				<td>
				<?$file='storage/catalog/manufecturer/'.$row['id'].'.png';?>
				<?$file=ltrim($row['img'],'/');?>
				<input class="upload" type="file" name="img[<?=$row['id'];?>]" id="img<?=$row['id'];?>" style="position:absolute;left:-9999px">
				<label title="������ �����������" for="img<?=$row['id'];?>">
				������
				<?if(file_exists($file)){?>
				<br><img id="image<?=$row['id'];?>" src="/<?=scaleImg($file,"w100")?>">
				<?}else{?>
				<br><img id="image<?=$row['id'];?>"  src="/img/no_image.png" style="display:none">
				<?}?>
				
				</label>
				
				</td>
				<td><input name="name[<?=$row['id'];?>]" value="<?=htmlspecialchars($row["name"]);?>" style="width:300px"></td>
				<td><?=htmlspecialchars($row["description"]);?></td>
				
				<td><input name="sort[<?=$row['id'];?>]" value="<?=$row['sort'];?>" style="width:30px; <?if($row['sort']>0){?>background:#66ff66<?}?>"/></td>
				<td>
				<a href="/admin/catalog/goods/man/<?=$row['id'];?>/">
				<?=intval($row['c'])?>
				</a>
				
				</td>
				<td>
				<a class="edit" href="<?=$this->mod_uri?>manEdit/?id=<?=$row['id'];?>"><img src="/img/pic/edit_16.gif" title="�������������" alt="�������������"/></a>
				</td>
				
			</tr>	
			<?}?>
			</tbody>
		</table>
	<?}else{?>
		<div>������ ����</div>
	<?}?>
</form>
<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>
<script type="text/javascript" src="/modules/catalog/admin_manufecturer.js"></script>