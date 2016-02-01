<div id="partners">
<ul>
<?while($rs->next()){?>
<li>
<a href="<?if($rs->get('url')){?><?=$rs->get('url')?><?}?>" class="title" title=" <?=htmlspecialchars($rs->get('name'))?>">
<img src="<?=scaleImg($rs->get('img'),'h50')?>" alt=" <?=htmlspecialchars($rs->get('name'))?>">
</a>

<?//=$rs->get('name')?>

<span><?=$rs->get('description')?></span>
</li>
<?}?>
</ul>
<div style="clear:both"></div>
</div>