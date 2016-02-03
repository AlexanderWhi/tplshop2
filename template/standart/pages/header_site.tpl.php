<div id="top">
    <div class="wrapper">

        <div class="social">
            <?
            foreach ($this->enum('social') as $n => $url) {
                if (empty($url))
                    continue;
                ?>
                <a href="<?= $url ?>" class="<?= $n ?>"></a>
        <? } ?>
        </div>
        <? /* div id="block-info-work"><?=$this->getText('block_info_work')?></div */ ?>
        <div id="block-info-phone"><?= $this->getText('block_info_phone') ?></div>
<? include_once('modules/join/login_box.tpl.php') ?>
    </div>
</div>
<div id="header">

    <div class="wrapper">
        <a href="/" id="logo" style="<?if($l=$this->cfg('LOGO_PATH')){?>background-image:url(<?=$l?>)<?}?>" title="<?= $this->cfg('HEADER') ?>"><?= $this->cfg('HEADER_TITLE') ?></a>
        <? /* div id="contacts"><?=$this->getText('contacts')?></div */ ?>

<? if ($this->cfg('SEARCH_FORM') == 'true') { ?>
            <!--search-->
            <form id="search-bar" action="/catalog/">
                <input name="search" placeholder="<?= $this->cfg('SEARCH_TITLE') ?>" value="<?= isset($_GET['search']) ? htmlspecialchars(trim(urldecode($_GET['search']))) : '' ?>"><!--
                --><button type="submit">Найти</button>
            </form>
<? } ?>
        <!--/search-->
        <a href="/catalog/basket/" id="basket-blk"><? include_once('modules/catalog/basket_content_container.php') ?></a>
        <a id="favorites" href="/catalog/favorite/">Избранное</a>	
    </div>


</div>

<div id="top-menu">
    <div class="wrapper">
        <ul>
<? foreach ($this->getMenu('top') as $item) { ?><li class="<?= trim($item['mod_alias'], '/') ?> <? if ($item['mod_alias'] == $this->mod_uri) { ?>act<? } ?>" id="tmenu_<?= str_replace('/', '_', trim($item['mod_alias'], '/')) ?>">
                    <a href="<?= $item['mod_alias'] ?>"><?= $item['mod_name'] ?></a>
                </li><? } ?>
        </ul>
    </div>
</div>