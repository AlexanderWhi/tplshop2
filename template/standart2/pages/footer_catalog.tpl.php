<div id="footer-menu-catalog">
    <div class="wrapper">
        <div class="sc-desktop">
            <table>
                <td>
                    <?
                    $cat = $this->getMenuCatalog();
                    $c = count($cat);
                    $div = ceil($c / 4);
                    $i = 0;
                    foreach ($cat as $item) {
                        ?>
                        <? if ($i && ($i % $div == 0)) { ?>
                        </td><td>
                        <? }$i++; ?>
                        <a class="root" href="/catalog/<?= $item['id'] ?>/"><?= $item['name'] ?></a>

                        <? if (isset($item['children'])) { ?>
                            <div class="ch">
                                <?
                                $j = 0;
                                foreach ($item['children'] as $item) {
                                    ?><a href="/catalog/<?= $item['id'] ?>/"><?= $item['name'] ?></a><? } ?>
                            </div><? } ?>  

                    <? } ?>

                </td>
            </table>
        </div>
        <div class="sc-mobile">
            <table>
                <td>
                    <?
                   
                    $div = ceil($c / 2);
                    $i = 0;
                    foreach ($cat as $item) {
                        ?>
                        <? if ($i && ($i % $div == 0)) { ?>
                        </td><td>
                        <? }$i++; ?>
                        <a class="root" href="/catalog/<?= $item['id'] ?>/"><?= $item['name'] ?></a>

                        <? if (isset($item['children'])) { ?>
                            <div class="ch">
                                <?
                                $j = 0;
                                foreach ($item['children'] as $item) {
                                    ?><a href="/catalog/<?= $item['id'] ?>/"><?= $item['name'] ?></a><? } ?>
                            </div><? } ?>  

                    <? } ?>

                </td>
            </table>
        </div>
    </div>
</div>

