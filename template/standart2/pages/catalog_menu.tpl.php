<ul><? foreach ($this->getCatalog() as $item) { ?>
    <li class="<? if ($this->getUriVal('catalog') == $item['id'] || (isset($item['children'][$this->getUriVal('catalog')]))) { ?>act<? } ?>" ><a href="/catalog/<?= $item['id'] ?>/"><?= $item['name'] ?></a> 
            <? if (isset($item['children'])) { ?>

                <ul>
                    <? foreach ($item['children'] as $i) { ?>
                        <li><a  class="<? if ($this->getUriVal('catalog') == $i['id']) { ?>act<? } ?>" href="/catalog/<?= $i['id'] ?>/" style="<? if ($this->getUri() == '/') { ?>background-image:url(<?= scaleImg($i['img'], 'h70') ?>)<? } ?>"><?= $i['name'] ?></a> 
                        <? } ?>
                </ul>

            <? } ?>
        </li>
    <? } ?>
</ul>