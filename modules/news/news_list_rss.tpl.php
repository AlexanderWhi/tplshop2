<form method="POST">
<?foreach ($src_list as $k=>$v) {?>
	<div>
	<a href="/admin/news/rss/?url=<?=urlencode($v)?>"><?=$v?></a>
	</div>
<?}?>
<div>
<a href="/admin/enum/news_rss_src/">Редактировать источники</a>
</div>

<?//$this->displayPageControl();?>
		<?if($news_list){?>
			<table class="grid">
			<tr><th>Изображение</th><th>Название</th><th>Дата</th><th>Действие</th></tr>
			<?foreach ($news_list as $item){?>
				<tr>
					<td style="width:110px">
						<?if($item['img']){?><img src="<?=$item['img']?>" style="width:100px"><?}else{?><img src="/img/admin/no_image.png"/><?}?>	
					</td>
					<td style="text-align:left">
						<?=$item['title']?>
					</td>
					<td>
						<small>[<?=dte($item['date'])?>]</small> 
					</td>

					<td style="width:120px">
						<a href="/admin/news/edit/?url=<?=urlencode($url)?>&guid=<?=urlencode($item['guid'])?>"><img src="/img/pic/edit_16.gif" title="Редактировать" alt=""/></a>
					</td>
				</tr>	
			<?}?>
			</table>
		
		<?}else{?>
			<div>Записей не найдено</div>
		<?}?>
	</form>
<script type="text/javascript" src="/modules/news/admin_news.js"></script>