<?function displayCatalog($lnk,$tree,$deep){
	
		?><tr>			
				
			<td style="padding-left:<?=$deep*20?>px;width:300px">
					<?=$tree['name']?>
				</td>
				<td>
					<input style="font-size:7pt;width:60px" name="lnk[<?=$tree['id']?>]" value="<?=!empty($lnk[$tree['id']])?$lnk[$tree['id']]:''?>">
				</td>
				<td>
					<?=$tree['id']?>
				</td>
				<td>
					<a class="move" rel="<?=$tree['id']?>" title="Переместить дочерние узлы" href="#"><img src="/img/pic/move_16.gif"/></a>
					<a class="move_to" rel="<?=$tree['id']?>" title="Переместить дочерние узлы в текущий каталог" href="#"><img src="/img/pic/move_16.gif"/></a>
				</td>
			</tr>
		
		<?foreach ($tree['ch'] as $node){
			displayCatalog($lnk,$node,$deep+1);
		}
}?>

<form id="catsrv_form" method="POST" action="?act=SaveExtCat">
<?if($tree){?>
<table class="grid">
	<tr><th>Название</th><th>Ид на сайте</th><th>ид</th></tr>
	<?displayCatalog($lnk,$tree[0],0)?>
</table>
<button type="submit">Применить</button>
<?}else{?>
Файл внешнего каталога не найден!
<?}?>
</form>
<script type="text/javascript" src="/modules/catsrv/admin_catsrv_extcat.js"></script>