<form id="driver-form" method="POST" action="?act=saveDriver&cb=_cb" enctype="multipart/form-data" target="save_fr">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<input type="hidden" name="id" value="<?=$id?>"/>
<table class="form">
<tr>
<th style="width:150px">���</th>
<td><input name="name" value="<?=$name?>"/></td>
</tr>
<tr><th>���������� �������</th>
<td><input name="phone" value="<?=$phone?>"/></td>
</tr>
<tr>
<th>������</th>
<td><input name="car" value="<?=$car?>"/></td>
</tr>
<tr>
<th>�����������</th>

<td><input type="hidden" name="img" value="<?=$img?>"/>
<img id="img" src="<?=$img?scaleImg($img,'w100h100'):'/img/admin/no_image.png'?>">
<input type="file" name="img_upload"><button type="button" name="clear">�������</button>
</td>
</tr>
<tr>
<th>����������� ����</th>
<td><input type="hidden" name="img_car" value="<?=$img_car?>"/>
<img id="img_car" src="<?=$img_car?scaleImg($img_car,'w100h100'):'/img/admin/no_image.png'?>">

<input type="file" name="img_car_upload"><button type="button" name="clear">�������</button>
</td>
</tr>
<tr>
<th>������</th>
<td>
<?foreach (array(0=>'���������',1=>'�������') as $key=>$desc) {?>
	<input type="radio" name="state" value="<?=$key?>" <?=$key==$state?'checked':''?>><label><?=$desc?></label>
<?}?>
</td>
</tr>
</table>


<hr/>
<button type="submit" name="save" class="button">���������</button> 
<button name="close" type="button" class="button">�������</button>
</form>
<iframe name="save_fr" style="display:none"></iframe>
<script type="text/javascript" src="/modules/operator/admin_drivers_edit.js"></script>