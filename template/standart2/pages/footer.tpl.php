<? include('footer_catalog.tpl.php') ?>
<div id="footer">
    <div class="wrapper">
        <!--        <a href="/" id="logo_footer"></a>-->
        <div id="footer-menu">
            <? foreach ($this->getMenu('footer') as $item) { ?><!--
                --><a href="<?= $item['mod_alias'] ?>"><?= $item['mod_name'] ?></a><!--
                
                <? /* if(isset($item['children'])){?>
                  <?foreach ($item['children'] as $item){?>
                  <a href="<?=$item['mod_alias']?>"><?=$item['mod_name']?></a>
                  <?}?>
                  <?} */ ?>        
                
                --><? } ?>
        </div>
        <div id="block-info-footer"><?= $this->getText('block_info_footer') ?></div>


        <div class="block">
            <div id="copyrights"><?= $this->getText('copyrights') ?></div>


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

            <noindex>
                <div id="develop">
                    <a href="http://uralsite.ru/">���������� ����� "��������"</a>
                </div>
            </noindex>
        </div>

    </div>
</div><!--/#footer-->

<? include('popup.tpl.php') ?>
<? include('script.tpl.php') ?>
<? include('template/common/yametrica.tpl.php') ?>