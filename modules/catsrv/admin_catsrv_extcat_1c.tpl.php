<?function displayCatalog($catalog,&$lnk,$deep=0,$count){//
	foreach ($catalog->{u('������')} as $group) {
		$d=array(
			'id'=>u2w($group->{u('��')}),
			'name'=>u2w($group->{u('������������')}),
		);
		?><tr>	
			<td style="padding-left:<?=$deep*20?>px;">
					<?=$d['name']?>
					
					<?//=print_r($count)?>
					<?if(!empty($count[$d['id']])){?> [<?=$count[$d['id']]?>]<?}?>
				</td>
				<td>
					<input style="font-size:7pt;width:50px" name="lnk[<?=$d['id']?>]" value="<?=!empty($lnk[$d['id']])?$lnk[$d['id']]:''?>">
				</td>
				<td style="white-space:nowrap">
					<?=$d['id']?>
				</td>
				<td>
					<?/*a class="move" rel="<?=$d['id']?>" title="����������� �������� ����" href="#"><img src="/img/pic/move_16.gif"/></a*/?>
					<a class="move_to" rel="<?=$d['id']?>" title="����������� �������� ���� � ������� �������" href="#"><img src="/img/pic/move_16.gif"/></a>
				</td>
			</tr>
		<?
		if(!empty($group->{u('������')})){
			displayCatalog($group->{u('������')},$lnk,$deep+1,$count);
		}
	}
}?>
<form id="catsrv_form" method="POST" action="?act=SaveExtCat">
<table class="grid">
	<tr><th>��������</th><th>�� �� �����</th><th>��</th></tr>
	<?displayCatalog($catalog,$lnk,0,$count);?>
</table>
<button type="submit">���������</button>
</form>
<script type="text/javascript" src="/modules/catsrv/admin_catsrv_extcat.js"></script>