<table style="width:100%">
<tr>
<?foreach ($rs as $i=>$row) {?>
	<td style="vertical-align:top;text-align:center">
	<a href="http://<?=Cfg::get('SITE')?>/catalog/goods/<?=$row['id']?>/">
	<img src="<?=scaleImg($row['img'],'w200h100')?>"><br>
	<?=$row['name']?></a>
	</td>

<?if($i && ($i%3)==0){?></tr><tr><?}?>
<?}?>
</tr>
</table>

<a href="http://<?=Cfg::get('SITE')?>/catalog/?last_time=<?=$last_date?>">Все новинки на сайте</a>