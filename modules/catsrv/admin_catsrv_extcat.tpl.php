<?
global $fill;
$fill=$this->getUriIntVal('fill');
function displayCatalog($catalog,&$lnk,$deep=0,$count){//
global $fill;
	foreach ($catalog as $id=>$group) {
		if($fill ){
			if(empty($group['c']) && empty($group['cr'])){
				continue;
			}
		}
		
		$d=array(
			'id'=>$id,
			'name'=>$group['name'],
			
		);
		?><tr>	
			<td style="padding-left:<?=$deep*20?>px;">
					<?=$d['name']?>
					<?if(!empty($group['c'])){?> [<?=$group['c']?>]<?}?>
					<?if(!empty($group['cr'])){?> <span style="color:#aaa">[<?=$group['cr']?>]</span><?}?>
				</td>
				<td>
					<input style="font-size:7pt;width:50px" name="lnk[<?=$d['id']?>]" value="<?=!empty($lnk[$d['id']])?$lnk[$d['id']]:''?>">
				</td>
				<td style="white-space:nowrap">
					<?=$d['id']?>
				</td>
				<td>
					<?/*a class="move" rel="<?=$d['id']?>" title="Переместить дочерние узлы" href="#"><img src="/img/pic/move_16.gif"/></a*/?>
					<a class="move_to" rel="<?=$d['id']?>" title="Переместить дочерние узлы в текущий каталог" href="#"><img src="/img/pic/move_16.gif"/></a>
				</td>
			</tr>
		<?
		if(!empty($group['ch'])){
			displayCatalog($group['ch'],$lnk,$deep+1,$count);
		}
	}
}?>
<form id="catsrv_form" method="POST" action="?act=SaveExtCat">
<input type="hidden" name="offer" value="<?=$offer?>">
<input type="hidden" name="file" value="<?=$file?>">
<button type="submit">Применить</button> <button type="button" name="import">Импорт</button> 

<?if(!$this->getUriIntVal('fill')){?>
<a href="<?=$this->getUri(array('fill'=>true),true)?>">Скрыть пустые</a>
<?}else{?>
<a href="<?=$this->getUri(array('fill'=>null),true)?>">Показать пустые</a>
<?}?>
<table class="grid">
	<tr><th>Название</th><th>Ид на сайте</th><th>ид</th></tr>
	<?displayCatalog($catalog,$lnk,0,$count);?>
</table>
<button type="submit">Применить</button> <button type="button" name="import">Импорт</button> 
</form>
<script type="text/javascript" src="/modules/catsrv/admin_catsrv_extcat.js"></script>