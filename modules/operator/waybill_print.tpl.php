<?if($rs){$i=0;foreach ($rs as $item){?>
<div class="<?=++$i!=count($rs)?'pagebreak':''?>" style="width:600px">
<h1>������� ���� �<?=$item['id']?></h1>
<h2>��������</h2>
<?=$item['name']?> [<?=$item['car']?>]
________________________
		
<h2>������</h2>
<?=$item['waybillOrders']?>
</div>
<hr />
<?}}else{?>
������ �� �������
<?}?>