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
					<a class="move" rel="<?=$tree['id']?>" title="����������� �������� ����" href="#"><img src="/img/pic/move_16.gif"/></a>
					<a class="move_to" rel="<?=$tree['id']?>" title="����������� �������� ���� � ������� �������" href="#"><img src="/img/pic/move_16.gif"/></a>
				</td>
			</tr>
		
		<?foreach ($tree['ch'] as $node){
			displayCatalog($lnk,$node,$deep+1);
		}
}?>

<form id="catsrv_form" method="POST" action="?act=SaveExtCat">
<?if($tree){?>
<table class="grid">
	<tr><th>��������</th><th>�� �� �����</th><th>��</th></tr>
	<?displayCatalog($lnk,$tree[0],0)?>
</table>
<button type="submit">���������</button>
<?}else{?>
���� �������� �������� �� ������!
<?}?>
</form>
<script type="text/javascript" src="/modules/catsrv/admin_catsrv_extcat.js"></script>