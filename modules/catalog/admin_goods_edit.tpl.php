<form method="POST" enctype="multipart/form-data" action="?act=goodsSave">
    <input class="button" type="submit" name="save" value="���������"/>
    <input class="button_long" type="submit" name="save_as_new" value="��������� ��� �����"/>
    <input class="button" name="close" type="submit" value="�������"/>
    <label><input type="checkbox" id="tabs_view"> ������������� ���</label>
    <hr />
    <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
    <input type="hidden" name="id" value="<?= $id ?>"/>
    <div id="tb">
        <ul>
            <li><a href="#common-bar">����� ����������</a></li>
            <!--		<li><a href="#nmn-bar">��������</a></li>-->
            <li><a href="#img-bar">�����������</a></li>
            <li><a href="#html-bar">������ ����������</a></li>
            <!--		<li><a href="#in_stock-bar">� �������</a></li>-->
            <li><a href="#prop-bar">��������</a></li>
            <li><a href="#relation-bar">�����</a></li>
        </ul>

        <div id="common-bar">
            <table class="form">

                <? if ($id) { ?>
                    <tr><th>ID:</th><td id="data_id"><?= $id ?></td></tr>
                    <tr><th>������� �� �����:</th><td><a href="/catalog/goods/<?= $id ?>/">/catalog/goods/<?= $id ?>/</a></td></tr>
                <? } ?>

                <tr><th>������� ID:</th><td><input class="input-text" name="ext_id"  value="<?= $ext_id ?>"/></td></tr>

                <tr><th style="width:200px">��������:</th><td><input class="input-text" name="name" value="<?= $name ?>"/></td></tr>
                <tr><th>�������:</th><td><input class="input-text" name="product"  value="<?= $product ?>"/></td></tr>
                <tr><th><a href="?act=Manufacturer">�������������</a>:</th><td>
                        <? if (isset($manList)) { ?>
                            <select name="manufacturer_id">
                                <option value="0">--�� ������</option>
                                <? foreach ($manList as $itm) { ?>
                                    <option value="<?= $itm['id'] ?>" <? if ($manufacturer_id == $itm['id']) { ?>selected<? } ?>><?= $itm['name'] ?></option>
                                <? } ?>

                            </select>
                        <? } else { ?>
                            <input class="input-text" name="manufacturer"  value="<?= $manufacturer ?>"/>
                        <? } ?>


                    </td></tr>

                <tr><th>��������:</th><td><textarea name="description" class="input-text" style="height:100px"><?= $description ?></textarea></td></tr>		
		<tr><th>�������:</th>
			<td><?= $this->displayCatalog($catalog, $category) ?></td>
		</tr>
                <? if (!empty($catalogs)) { ?>
        		<tr><th>��������:<br>(��� �������������� ������ ����������� Ctrl)</th>
        			<td><?= $this->displayCatalogs($catalogs, $categories) ?></td>
        		</tr>
                <? } ?>
		
		<tr><th>����� �������:</th><td><input type="checkbox" value="1" name="weight_flg" value="1" <? if ($weight_flg) { ?>checked<? } ?>></td></tr>
		<tr><th>����:</th><td><input class="input-text num2" name="price"  value="<?= $price ?>"/></td></tr>
		
		
		
		
		<tr><th>������ ����:</th><td><input class="input-text num2" name="old_price"  value="<?= $old_price ?>"/></td></tr>
		<tr><th>� �������:</th><td><input class="input-text num" name="in_stock"  value="<?= $in_stock ?>"/></td></tr>
		<tr><th>�������:</th><td><input class="input-text num" name="sort3"  value="<?= $sort3 ?>"/></td></tr>
		
		</table>
	</div>
	
	<div id="html-bar">
		<label>��������</label>
		<textarea class="tiny" name="html" style="width:100%;height:300px"><?= $html ?></textarea>
		<label>������</label>
		<textarea class="tiny" name="html2" style="width:100%;height:300px"><?= $html2 ?></textarea>
            <? /* label>������ ����������</label>
              <textarea class="tiny" name="html3" style="width:100%;height:300px"><?=$html3?></textarea>
              <label>������ ��������</label>
              <textarea class="tiny" name="html4" style="width:100%;height:300px"><?=$html4?></textarea */ ?>
	</div>
	
	<div id="nmn-bar" style="display:none">
		<table id="nmn" class="grid" style="width:auto">
		<thead>
		<tr>
		<th>����</th>
		<th>��������</th>
		<th>�������</th>
		</tr>
		</thead>
		<tbody>
                    <? foreach ($nmnList as $k => $row) { ?>
        		<tr>
        		<td><input style="width:50px" name="nmn_price[]" value="<?= $row['price'] ?>" class="nmn_price"></td>
        		<td><input style="width:350px" name="nmn_description[]" value="<?= $row['description'] ?>" class="nmn_description"></td>
        		<td><input style="width:50px" name="nmn_sort[]" value="<?= $row['sort'] ?>" class="nmn_sort"></td>
        		<td><td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td></td>
        		</tr>
                    <? } ?>
		</tbody>
		
		<tfoot style="display:none">
		<tr>
		<td><input style="width:50px" name="nmn_price_"  class="nmn_price"></td>
		<td><input style="width:350px" name="nmn_description_"  class="nmn_description"></td>
		<td><input style="width:50px" name="nmn_sort_"  class="nmn_sort"></td>
		<td><td><a href="#" class="del"><img src="/img/pic/trash_16.gif"></a></td></td>
		</tr>
		</tfoot>
		</table>
		
		<a href="javascript:addNmn()"><img src="/img/pic/add_16.gif"></a>

	</div>
	
	
	<div id="img-bar" style="min-height:250px;">
            <? if ($img_format_list) { ?>
    	
    	<select name="unit">
    	<option value="0">--������</option>
                    <? foreach ($img_format_list as $k => $d) { ?>
        		<option value="<?= $k ?>" <? if ($k == $unit) { ?>selected<? } ?>><?= $d ?></option>
                    <? } ?>
    	</select>
            <? } ?>
				<input type="file" name="upload[]" multiple><button name="img_del" class="img_del">�������</button>
				<hr>
				<div id="img-tpl" style="display:none;margin-right:30px">
					<input type="hidden" name="img_" value=""/>
					<input type="checkbox" name="itm_" value="1"/>
					<input name="sort_" class="sort"/>
					<div style="height:200px">
					<img src="/img/admin/no_image.png"/>
					</div>
				
				</div>
				<div id="img-list"></div>
	</div>
        <? /* tr><th>� �������:</th><td><input name="in_stock" value="<?=$in_stock?>"/></td></tr */ ?>
	<div id="in_stock-bar" style="display:none">
	
		<table class="grid" style="width:auto">
		<tr>
		<th>������</th>
                    <? /* th>����</th */ ?>
		<th>� �������</th>
		</tr>
                <? foreach ($offerList as $reg => $row) { ?>
        			<tr>
        			<td><?= $row['reg'] ?></td>
                        <? /* td><input style="width:50px" name="price[<?=$reg?>]" value="<?=(float)$row['price']?>"></td */ ?>
        			<td><input style="width:30px" name="in_stock[<?= $reg ?>]" value="<?= (int) $row['in_stock'] ?>"></td>
        			</tr>
                <? } ?>
		</table>
		
	</div>
		
	<div id="prop-bar">		
	<div id="goods-props">
                <? include('admin_goods_edit_prop.tpl.php') ?>
	
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
	</div>
	</div>
		
	<div id="relation-bar">
		
		<select id="relation">
		<option>--������� ���������</option>
                <? foreach ($shop_relation as $k => $d) { ?>
        			<option value="<?= $k ?>"><?= $d ?></option>
                <? } ?>
		</select>
		<hr>
		
		<table id="relation_container" class="grid"  style="width:auto"></table>
		<input class="button" type="button" name="relation_remove" value="�������"/>
		
	</div>
	
	
</div>
	
	<hr/>
	<input class="button" type="submit" name="save" value="���������"/>
	<input class="button_long" type="submit" name="save_as_new" value="��������� ��� �����"/>
	<input class="button" name="close" type="submit" value="�������"/>		
</form>





<script type="text/javascript">
    var PROP_GRP =<?= $data['prop_grp'] ?>;
    var IMG_LIST =<?= printJSON($data['imgList']) ?>

</script>

<script type="text/javascript" src="/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/tiny_mce/jquery.tiny.js"></script>


<script type="text/javascript" src="/modules/catalog/catalog_goods_edit.js"></script>