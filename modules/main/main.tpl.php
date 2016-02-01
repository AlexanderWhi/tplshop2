<div class="wrapper">
			<div id="left-bar">
			
			<?include('modules/catalog/catalog_left.tpl.php')?>
			
			</div>
			<div id="center-bar" class="border">
				<div id="content" class="bar">
				
					<?if($catalog=$goods){?>
					<h2>Спецпредложения</h2>
					<?include("modules/catalog/catalog_view_table.tpl.php")?>
					<?}?>	
				
					<?if($manufacturer){?>
					<h2>Бренды</h2>
					<div class="manufacturer">
					<?foreach ($manufacturer as $item) {?>
						<a href="/catalog/manid/<?=$item['id']?>" title="<?=$item['name']?>">
						<img src="<?=scaleImg($item['img'],'w80h40')?>">
						</a>
					<?}?>
					</div>
					<?}?> 
				
					<?=$this->getText('/index/')?>
					
				</div>
			</div>
		
		</div>





<div class="wrapper">


<?/*div class="main_catalog">
	<?
	$i=0;
	foreach ($catalog as $item) {?>
	<div class="item">
	<h2><a href="/catalog/<?=$item['id']?>/"><?=$item['name']?></a></h2>
	<?if(!empty($item['children'])){?>
	<ul>
	<? $j=0;
	foreach ($item['children'] as $ch) {?>
		<li>
		<a href="/catalog/<?=$ch['id']?>/"><?=$ch['name']?></a>
		</li>
	<?if($j++>10)break;}?>
	</ul>
	<?}?>
	<a class="more" href="/catalog/<?=$item['id']?>/">Все разделы</a>
	</div>	
	<?if($i++>3)break;}?>
	</div>
</div*/?>




<div class="main_blk">
<div class="wrapper">

<?if($faq){?>
<div class="main_faq">
<h2>Частые вопросы</h2>
<div class="slider">
<ul>
<?foreach ($faq as $item) {?>
	<li><a href="/faq/#quest<?=$item['id']?>"><?=$item['question']?></a></li>
<?}?>
</ul>
</div>
<div class="common_more2"><a href="/faq/">Все вопросы</a></div>
</div>
<?}?>



</div>
</div>

<div class="wrapper">
	<?if(false && $news){?>
	<div class="main_news">
	<div class="common_more"><a href="/news/">Все новости компании</a></div>
	<h2>Наши новости</h2>
	<?foreach ($news as $item) {?>
	<div class="item">
	<span class="date"><?=fdte($item['date'])?></span>
	<a href="/news/<?=$item['id']?>/"><?=$item['title']?></a>
	</div>	
	<?}?>
	</div>
	<?}?>
	
	
	
	<?if (false && $rs=$public){?>
	<div class="main_public">
	<div class="common_more"><a href="/public/">Читать все публикации</a></div>
	<h2>Популярные публикации</h2>
		<?include('modules/article/public_list.tpl.php')?>
	</div>
	<?}?>
	
	<?if(false){?>
	<form id="main-subscribe" action="/cabinet/subscribe/">
	<?=$this->getText('main_subscribe')?>
	<input type="hidden" name="type" value="news public">
	<input name="mail" title="e-mail">
	<button type="submit">Отправить</button>
	</form>
	<?}?>
	
	
	<?if(false && $fb){?>
	<div class="main_feedback">
	<div class="common_more"><a href="/feedback/">Читать все отзывы</a></div>
	<h2>Отзывы</h2>
	<?foreach ($fb as $item) {?>
	<div class="item">
	<span class="author"><?=$item['name']?></span>
	<span class="date"><?=fdte($item['time'])?></span>
	
	<?=$item['comment']?>
	</div>	
	<?}?>
	</div>
	<?}?>
	
	
</div>
<?//=$this->getText('/main/bottom')?>


<?if(false && $fb){?>
<div class="slider" id="main-feedback">
<ul>
	<?foreach ($fb as $row) {?>
		<li>
			<div class="item">
			<?if($row['mail']){?>
			<img src="<?=scaleImg($row['mail'],'w150h200')?>">
			
			<?}?>
				<div class="autor"><?=$row['name']?> <?=$row['phone']?> <span class="date"><?=dte($row['time'])?></span></div>
				
				<div class="description">
				<?=$row['comment']?>
				</div>
			</div>	
		</li>
<?}?>
</ul>
</div>
<a class="main_more" href="/feedback/">Читать все отзывы о нас</a>
			
<?}?>

<?/*div class="main_man">
<?foreach ($main_man as $row) {?><a href="/catalog/<?=$row['id']?>/" style="background-image:url(<?=scaleImg($row['img'],'w130')?>)"></a> <?}?>
</div*/?>