


<form id="goods_item" method="POST" action="?act=relationRemove">

<div class="form" >
<button type="submit" class="button" name="delete">Разорвать</button>
</div>
<?$this->page->display()?>

<br>
	
	<?if($rs){?>

	<br>
    
		<table class="grid">
			<thead>
			<tr>
				<th>
				<input type="checkbox" name="all"/>
				</th>
				
				<th colspan="2">Название</th>
				
				<th colspan="2">Связан</th>
				<th>Тип</th>
			</tr>
			</thead>
			<tbody>
			<?foreach ($rs as $row) {?>
			<tr id="goods_item<?=$row['id']?>">
				<td>
				<input type="checkbox" name="item[]" value="<?=$row['r_id']?>" >
				</td>
				<td>
					<?if($row['img']){?>
					
					<img src="<?=scaleImg($row['img'],'w100h50')?>">
					<?}else{?>
					<img src="/img/pic/photo.gif"/>
					
					<?}?>
				</td>
				<td><a href="<?=$this->mod_uri?>goodsEdit/?id=<?=$row['id'];?>" title="Редактировать"><?=htmlspecialchars($row["name"]);?></a></td>
				<td>
					<?if($row['ch_img']){?>
					
					<img src="<?=scaleImg($row['ch_img'],'w100h50')?>">
					<?}else{?>
					<img src="/img/pic/photo.gif"/>
					
					<?}?>
				</td>
				
				<td><a href="<?=$this->mod_uri?>goodsEdit/?id=<?=$row['ch_id'];?>" title="Редактировать"><?=htmlspecialchars($row["ch_name"]);?></a></td>
				<td><small><?=$row['type_desc'];?></small></td>

			</tr>	
			<?}?>
			</tbody>
		</table>
		
	<?}else{?>
		<div>Список пуст</div>
	<?}?>
</form>
<script type="text/javascript" src="/modules/catalog/admin_relation.js"></script>