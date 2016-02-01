<ul>
<?foreach ($srv as $href=>$desc){?>
<li>
<a href="<?=$this->mod_uri.$href?>/"><?=$desc?></a>
</li>
<?}?>
</ul>