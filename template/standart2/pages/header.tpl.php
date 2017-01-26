<div id="top" class="sc-desktop">
    <div class="wrapper">

        <div class="social">
            <?
            foreach ($this->enum('social') as $n => $url) {
                if (empty($url))
                    continue;
                ?>
                <?
                $pic = "/img/soc/soc_{$n}.png";
                if (!file_exists(ROOT . $pic)) {
                    continue;
                }
                ?>
                <a href="<?= $url ?>" class="<?= $n ?>" style="background-image: url(<?= $pic ?>)"></a>
            <? } ?>
        </div>
        <? /* div id="block-info-work"><?=$this->getText('block_info_work')?></div */ ?>
        <div id="block-info-phone"><?= $this->getText('block_info_phone') ?></div>
        <? include_once('modules/join/login_box.tpl.php') ?>
    </div>
</div>


<div class="sc-mobile menu-mobile">
    <div class="top-menu-mobile-buttons">
        <div id="top-menu-mobile">
            <button rel="top-menu-rel-mobile"></button>
        </div>
        <div id="top-catalog-mobile">
            <button rel="top-catalog-rel-mobile">Каталог</button>
        </div>
        <ul class="top-buttons">
            <li id="m-phones">
                <button rel="phones-rel-mobile"></button>
            </li>
            <li id="m-delivery">
                <a href="/delivery/">Доставка</a>
            </li>
            <li id="m-basket">
                <a href="/catalog/basket/">Корзина <? if ($b = $this->getBasket()) { ?><span><?= count($b) ?></span><? } ?></a>
            </li>
            <li id="m-login">
                <a href="/join/login/">Вход</a>
            </li>
            <li id="m-search">
                <button rel="search-rel-mobile"></button>
            </li>

        </ul>
    </div>
    <div id="phones-rel-mobile" class="submenu-mobile">
        <a class="close" href="#">Закрыть</a>
        <?= $this->getText('block_info_phone') ?>
    </div>
    <div id="search-rel-mobile" class="submenu-mobile">
        <a class="close" href="#">Закрыть</a>
        <? if ($this->cfg('SEARCH_FORM') == 'true') { ?>
            <!--search-->
            <form id="search-bar-mobile" action="/catalog/">
                <input class="inptitle" name="search" title="<?= $this->cfg('SEARCH_TITLE') ?>" value="<?= isset($_GET['search']) ? my_htmlspecialchars(trim(urldecode($_GET['search']))) : '' ?>">
                <button type="submit">Найти</button>
            </form>
        <? } ?>
    </div>
    <div id="top-menu-rel-mobile" class="submenu-mobile">
        <a class="close" href="#">Закрыть</a>
        <ul>
            <? foreach ($this->getMenu('top') as $item) { ?>
                <li class="<?= trim($item['mod_alias'], '/') ?> <? if ($item['mod_alias'] == $this->mod_uri) { ?>act<? } ?>">
                    <a href="<?= $item['mod_alias'] ?>"><?= $item['mod_name'] ?></a>
                </li>
            <? } ?>
            <li class="">
                <a href="/action/">Предложения и акции</a>
            </li>
            <li class="">
                <a href="/cabinet/">Кабинет</a>
            </li>
        </ul>
        <?/*div id="block-info-phone-mobile-menu">
            <?= $this->getText('block_info_phone') ?>
        </div>
        <div id="block-info-pay-mobile-menu">
            <?= $this->getText('block_info_pay') ?>
            <a class="name" href="javascript:calcDelivery()">Расcчитать стоимость доставки</a>
        </div*/?>
    </div>
    <div id="top-catalog-rel-mobile" class="submenu-mobile">
        <a class="close" href="#">Закрыть</a>
        <? include 'catalog_menu.tpl.php';  ?>
    </div>

</div>


<div id="header">

    <div class="wrapper">
        <a href="/" id="logo" style="" title="<?= $this->cfg('HEADER') ?>">
            <img src="<? if ($l = $this->cfg('LOGO_PATH')) { ?><?= $l ?><? } else { ?>/template/<?= $this->theme() ?>/style/img/logo.png<? } ?>"><br>
            <?= $this->cfg('HEADER_TITLE') ?>
        </a>
        <? /* div id="contacts"><?=$this->getText('contacts')?></div */ ?>

        <? if ($this->cfg('SEARCH_FORM') == 'true') { ?>
            <!--search-->
            <form id="search-bar" action="/catalog/" class="sc-desktop">
                <input name="search" placeholder="<?= $this->cfg('SEARCH_TITLE') ?>" value="<?= isset($_GET['search']) ? Html::encode(trim($_GET['search'])) : '' ?>"><!--
                --><button type="submit">Найти</button>
            </form>
        <? } ?>
        <!--/search-->
        <div class="sc-desktop">
        <a href="/catalog/basket/" id="basket-blk"><? include_once('modules/catalog/basket_content_container.php') ?></a>
        <a id="favorites" href="/catalog/favorite/">Избранное <?= count(LibCatalog::getFav()) ?></a>
        </div>
    </div>


</div>

<div id="top-menu" class="sc-desktop">
    <div class="wrapper">
        <ul>
            <? foreach ($this->getMenu('top') as $item) { ?><!--
                --><li class="<?= trim($item['mod_alias'], '/') ?> <? if ($item['mod_alias'] == $this->mod_uri) { ?>act<? } ?>" id="tmenu_<?= str_replace('/', '_', trim($item['mod_alias'], '/')) ?>">
                    <a href="<?= $item['mod_alias'] ?>"><?= $item['mod_name'] ?></a>
                </li><!--
                --><? } ?>
        </ul>
    </div>
</div>