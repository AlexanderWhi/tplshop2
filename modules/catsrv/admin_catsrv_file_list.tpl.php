<form action="?act=Upload" method="POST"  enctype="multipart/form-data">
<table class="grid" style="width:auto">
<?foreach ($list as $f){?>
<tr>
<td>
<a href="<?=$this->mod_uri?>ExtCat/fill/1/?file=<?=$f['name']?>"><?=$f['name']?></a>
</td>
<td><?=date('Y.m.d H:i:s',$f['time'])?></td>
<td>
<?=$f['size']?>
</td>
<td>
<a href="?act=remove&name=<?=$f['name']?>" title="Удалить">[X]</a>
</td>

</tr>
<?}?>
</table>


<?=$this->getText('adm_catsrv_file')?>

<input type="file" name="file[]" multiple>
<button name="upload">Загрузить</button>
</form>

<form id="img_imp-form" action="?act=ImpImgZip" method="POST"  enctype="multipart/form-data" target="fr">
<iframe name="fr" style="display:none"></iframe>
Загрузить изображение (ZIP архив с изображениями)<br>

<?=$this->getText('adm_catsrv_img')?>
<select name="link">
<option value="ext_id">Внешний ИД</option>
<option value="id">ИД</option>
<option value="product">Артикул</option>

</select>
<input type="file" name="img_zip">
<button name="upload">Загрузить</button>
</form>
<script type="text/javascript" src="/modules/catsrv/catsrv_import.js"></script>