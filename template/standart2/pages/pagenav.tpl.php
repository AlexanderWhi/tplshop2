<?if($page->countPages()>1){?>
<table class="page_nav">
<td><div class="page"><?=$page->render1(array('<<'=>'','<'=>'Предыдущая','>'=>'Следующая','>>'=>'',))?></div></td>
<td class="right">
Показано c <?=$page->getBegin()+1?> по <?=min($page->per+$page->getBegin(),$page->all)?> 
(всего <?=$page->countPages()?> <?=morph($page->countPages(),'страница','страницы','страниц')?>)</td>
</table>
<?}?>