<?function displayCatalog($catalog,$tree,$par,$deep){
	if(isset($tree[$par])){
		foreach ($tree[$par] as $id){?>
			<tr>			
				<td style="padding-left:<?=$deep*20?>px;width:300px">
					<?=$catalog[$id]['name']?>
				</td>
				<td>
					<input style="font-size:7pt" name="lnk[<?=$id?>]" value="<?=$catalog[$id]['lnk']?>">
				</td>
			</tr>
			<?displayCatalog($catalog,$tree,$id,$deep+1);
		}
	}	
}?>

<form id="catsrv_form" method="POST" action="?act=SaveExtCat">
<?if($catalog){?>
<table class="grid">
	<tr><th>Название</th></tr>
	<?displayCatalog($catalog,$tree,0,0)?>
</table>
<button type="submit">Применить</button>
<?}else{?>
Файл внешнего каталога не найден!
<?}?>
</form>
<script type="text/javascript" src="/modules/catsrv/admin_catsrv_extcat.js"></script>