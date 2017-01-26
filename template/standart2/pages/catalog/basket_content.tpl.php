<? if (!empty($basket)) { ?>
    <input type="hidden" name="sort" value="<?= isset($sort) ? $sort : '' ?>">
    <input type="hidden" name="ord" value="<?= isset($ord) ? $ord : '' ?>">
    <table class="goods" style="width:100%">
        <thead>
            <tr>
            <!--<th class="first">№</th>-->
                <th colspan="2">Товар</th>
                <th><? $this->bsort('count', 'Кол-во') ?><? $this->bsort('price', 'Цена') ?></th>
                
                <th><? $this->bsort('sum', 'Сумма') ?></th>
                <? /* th>Комментарий к товару</th */ ?>
                <th class="last"></th>
            </tr>
        </thead>
        <tbody>
            <?
            $price = 0;
            $n = 0;
            foreach ($basket as $item) {
                if (empty($item['item_comment']))
                    $item['item_comment'] = '';
                $price += $item['sum'];
                ?>

                <tr id="basket<?= $item['key'] ?>" class="<? if ($n % 2) { ?>even<? } ?>">
                    <? /* td><?=++$n?>.</td */ ?>
                    <td >
                        <?
                        $img = ($item['img'] ? $item['img'] : $this->cfg('NO_IMG'));
                        $url = "http://" . $_SERVER['HTTP_HOST'] . "/catalog/goods/" . $item['id'] . "/";
                        ?>
                        <div class="sc-desktop">
                            <a class="img" style="background-image:url('<?= scaleImg($img, 'w120h120') ?>')" href="<?= $url ?>"  class="title" title=" <?= htmlspecialchars($item['description']) ?>"></a>
                        </div>
                    </td>
                    <td class="name">
                        <a title=" <?= htmlspecialchars($item['description']) ?>" class="name title" href="<?= $url ?>"><?= $item['name'] ?></a>
                        <? /* if(empty($is_letter)){?>
                          <div style="display:none"><?=$item['description']?></div>
                          <?} */ ?>
                        <? if (!empty($item['nmn'])) { ?><div class="nmn"><?= $item['nmn'] ?></div> <? } ?>
                        <div class="sc-mobile">
                            <a class="img" style="background-image:url('<?= scaleImg($img, 'w120h120') ?>')" href="<?= $url ?>"  class="title" title=" <?= htmlspecialchars($item['description']) ?>"></a>
                        </div>
                    </td>
                    <td style="white-space:nowrap">
                        <? if (empty($is_order)) { ?>

                            <!--<a href="#" class="b_down">-</a>--><input name="count[<?= urlencode($item['key']) ?>]" value="<?= $item['count'] ?>" class="count <?= $item['weight_flg'] ? 'weight' : '' ?>" /><!--<a href="#" class="b_up">+</a>-->
                        <? } else { ?>
                            <?= $item['count'] ?> 
                            <?
                        }
                        if ($item['weight_flg']) {
                            $item['unit'] = 1;
                        }
                        ?> 
                        <?= $this->getUnit($item['unit']) ?>
                            
                            <strong class="price"><?= price($item['price'], $item['weight_flg']) ?></strong>

                        <? if (!empty($item['discount'])) { ?>
                            <span style="color:red">(-<?= price($item['discount']) ?>)</span>
                        <? } ?>
                    </td>
                   
                    <td><strong class="price sum"><?= price($item['sum']) ?></strong></td>

                    <? /* td>
                      <?if(empty($is_order)){?>
                      <input style="width:200px" class="field" name="item_comment[<?=urlencode($item['key'])?>]" value="<?=htmlspecialchars($item['item_comment'])?>">
                      <?}else{?>
                      <?=htmlspecialchars($item['item_comment'])?>
                      <?}?>
                      </td */ ?>


                    <? if (empty($is_order)) { ?>
                        <td style="width:25px;text-align:right"><a class="remove" title="Удалить" href="javascript:shop.remove('<?= urlencode($item['key']) ?>')"></a></td>
                    <? } ?>
                </tr>


            <? } ?>


            <?
            if ($this->getUserId() && false) {//Бонусы>
                $b = $this->getUser('bonus') / 10;
                if ($bonus) {
                    $price -= $bonus;
                }
                ?>

                <tr>
                    <td colspan="3"></td>
                    <td>С учётом бонусов</td>
                    <td><strong class="price">-<?= price($b) ?></strong></td>
                    <td></td><? if (empty($is_order)) { ?><td></td><? } ?>

                </tr>

                <tr>
                    <td colspan="3"></td>
                    <td>
                        <? /* input type="checkbox" name="not_use_bonus" value="1" id="not_use_bonus" onchange="shop.refresh()" <?if(!empty($not_use_bonus)){?>checked<?}?>><label for="not_use_bonus">Не использовать бонусы</label */ ?>
                        <input type="checkbox" name="use_bonus" value="1" id="use_bonus" onchange="shop.refresh()" <? if (!empty($use_bonus)) { ?>checked<? } ?>><label for="use_bonus">Использовать бонусы</label>
                    </td>
                    <td>
                        <?
                        $bonus_status = ShopBonus::getBonusStatus($this->getUserId());
                        $bonus_status_desc = $this->enum('bonus_status', $bonus_status);
                        ?>

                        <span title="Статус: <?= $bonus_status_desc ?>" class="title bonus_status <?= $bonus_status ?>">Статус: <?= $bonus_status_desc ?></span>


                    </td>
                    <td></td><? if (empty($is_order)) { ?><td></td><? } ?>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td>
                        <a href="/bonus/">Узнать о бонусной программе</a>
                    </td>
                    <td></td><? if (empty($is_order)) { ?><td></td><? } ?>
                </tr>
            <? }//<<Бонусы?>

            <?
            if (!empty($delivery)) {
                $price += $delivery;
                ?>
                <tr class="delivery_line <? if ($n % 2) { ?>even<? } ?>">
                    <td colspan="2">

                        <?
                        if ($price_condition) {
                            $delivery_info = BaseComponent::getText('delivery_info');
                            ?>
                            <?= str_replace('{sum}', $price_condition, $delivery_info) ?>
                        <? } ?>


                    </td>
                    <td colspan="2">
                        Доставка:

                        <? if (isset($delivery_zone)) { ?>
                            <?= BaseComponent::getText('delivery_zone_' . $delivery_zone) ?>
                        <? } ?>


                    </td>
                    <td id="delivery_price" delivery="<?= $delivery ?>">
                        <strong class="price title <? if ($delivery === false) { ?>pickup<? } else { ?>delivery<? } ?>" title="<? if ($delivery === false) { ?>
                                    <?= $this->enum('delivery_title', 'pickup') ?><? } else { ?><?= $this->enum('delivery_title', 'delivery') ?><? } ?>">

                            <? if (is_null($delivery)) { ?>
                                <?= $this->enum('delivery_title', 'confirm') ?>
                            <? } elseif ($delivery === false) { ?>
                                <?= $this->enum('delivery_title', 'pickup') ?>
                            <? } else { ?><?= price($delivery); ?>
                            <? } ?>
                        </strong>

                        <? if ($delivery === false) { ?>
                            <?= BaseComponent::getText('delivery_info_pickup') ?>
                        <? } else { ?>
                            <?= BaseComponent::getText('delivery_info_delivery') ?>
                        <? } ?>
                    </td>
                    <td></td><? if (empty($is_order)) { ?><td></td><? } ?>
                </tr>
            <? } ?>

        </tbody>
        <tfoot>

            <tr class="total_line">
                <td colspan="2">
                    <? if (empty($is_order)) { ?>
                        <!--	<a class="refresh" href="/catalog/">Продолжить покупки</a>-->
                        <!--	<a class="refresh" href="javascript:shop.refresh()">Пересчитать</a>-->
                        <a class="clear" href="javascript:shop.clear()" ><span>Очистить корзину</span></a>
                    <? } ?>
                    &nbsp;</td>
               
                <td colspan="3">Итого:<strong class="total_price"><span id="basket_total_summ"><?= price($price); ?></strong>
                    <?= BaseComponent::getText('total_info') ?>
                </td>
                <td></td><? if (empty($is_order)) { ?><td></td><? } ?>
            </tr>

        </tfoot>
    </table>

<? } else { ?>
    <div>Ваша корзина заказов пуста</div>
    <?
}?>