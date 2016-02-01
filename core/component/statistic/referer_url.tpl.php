<table class="grid">
<tr><th>Статистика сайтов, с которых приходят пользователи</th><th width="50">Переходов</th></tr>	
<? foreach ($rs as $key=>$val){
if(!($url=@iconv("UTF-8", "cp1251//IGNORE", urldecode($key)))){
	$url=urldecode($key);
//	$url=preg_replace('|(.{40})|','\1 ',$url);
}
?>
<tr>
	<td><a target="_blank" href="<?=$key?>"><?=$url?></a></td>
	<td><strong><?=$val['all'];?></strong></td>
</tr>
<? }?>
</table>