<?if($page->countPages()>1){?>
<table class="page_nav">
<td><div class="page"><?=$page->render1(array('<<'=>'','<'=>'����������','>'=>'���������','>>'=>'',))?></div></td>
<td class="right">
�������� c <?=$page->getBegin()+1?> �� <?=min($page->per+$page->getBegin(),$page->all)?> 
(����� <?=$page->countPages()?> <?=morph($page->countPages(),'��������','��������','�������')?>)</td>
</table>
<?}?>