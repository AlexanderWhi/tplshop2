<?//include('modules/cabinet/menu.tpl.php')?>
<?if($catalog=$rs){?>
<?include('catalog_view_table.tpl.php')?>
<?}else{?>
<div>������ �� ���������</div>
<?}?>
<script type="text/javascript" src="/modules/catalog/favorite.js"></script>