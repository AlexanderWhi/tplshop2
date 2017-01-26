<?

function __in_stock($in_stock) {
    ?> <div>�������: <? if ($in_stock) { ?><span class="in_stock">���� � �������</span><? } else { ?><span class="no_in_stock">��� � �������</span><? } ?></div><?
}

function propListGrp($id, $propGrp, $onlyPSort = false) {
    $out = '';
    foreach ($propGrp as $item) {
        ?>
        <tr><td><?= $item['name'] ?></td><td><?= $item['value'] ?></td></tr>
        <?
    }
}

function propList($prop, $lim = 0, $list = null) {
    if ($prop) {
        ?>
        <? if ($list) { ?>
            <?
            foreach ($list as $id) {
                if (isset($prop[$id]) && $propGrp = $prop[$id]) {
                    ?>
                    <? propListGrp($id, $propGrp, true) ?>	
                <? } ?>
            <? } ?>
        <? } else { ?>
            <?
            $n = 0;
            foreach ($prop as $id => $propGrp) {
                ?>
                <h3><?= $propGrp['name'] ?></h3>
                <table class="prop-list">
                    <? propListGrp($id, $propGrp['p']) ?>	
                </table>
                <?
                if ($lim && ++$n >= $lim) {
                    break;
                }
            }
            ?>
        <? } ?>
        <?
    }
}
?>
<div id="goods">
    <?
    if ($this->isAdmin()) {
        echo '<a href="/admin/catalog/goodsEdit/?id=' . $data['id'] . '" class="coin-text-edit" title="�������������"></a><br>';
    }
    ?>


    <? if ($comment_rait || $comment_count) { ?>
        <div class="rait_blk">
            <div class="rait r<?= $comment_rait ?>"></div>
            <a class="comment_count" href="javascript:comments()"><?= $comment_count ?> <?= morph($comment_count, '�����', '������', '�������') ?></a>
        </div>
        <br>
    <? } ?>

    <div class="left_bar">
        <div class="img_list">
            <? $img = ($img ? $img : $this->cfg('NO_IMG')) ?>
            <a  itemprop="image" rel="gallery" href="<?= Img::scaleImg($img, 'w800') ?>" style="background-image:url('<?= Img::scaleBySize($img, $unit, array(620, 360)) ?><? //=(scaleImg($img,'w380h320'))    ?>')"></a>
        </div>


        <? if (($rs = $imgList) && count($rs) > 1) { ?>
            <div id="preview" class="">

                <a href="javascript:previewScrool(-160);" class="left" ></a>
                <a href="javascript:previewScrool(160);" class="right"></a>
                <ul>
                    <? foreach ($rs as $n => $item) { ?>
                        <li>
                            <a href="<?= scaleImg($item, 'w800') ?>" rel='<? if ($n) { ?>gallery<? } ?>' rel2="<?= Img::scaleBySize($item, $unit, array(620, 360)) ?><? //=scaleImg($item,'w400')   ?>" style="background-image:url(<?= Img::scaleBySize($item, $unit, array(110, 80)) ?><? //=scaleImg($item,'h80')   ?>)"></a>
                        </li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>






    </div><!--
    
    
    
    --><div class="right_bar" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <?= $description ?>
        <? if ($product) { ?><div>�������: <?= $product ?></div><? } ?>
        <? if ($m_name) { ?><div>�������������: <?= $m_name ?></div><? } ?>

        <!--�������� �������-->
        <? __in_stock($in_stock) ?>
        <br>
        <br>
        <? if (false) { ?>
            ���-��: 
            <!--<a class="down"></a>--><input id="count" class="field count <? if ($weight_flg) { ?>weight<? } ?>" value="1"><!--<a class="up"></a>-->
            <br>
            <a class="add title" href="javascript:shop.add(<?= $id ?>,$('#count').val())" title=" � �������">� �������</a>
        <? } else { ?>

            <a class="add title" href="javascript:shop.add(<?= $id ?>,'+1')" title=" � �������">� �������</a><br>
        <? } ?>
        <? if ($old_price) { ?>
            <span class="old_price"><strike><?= price($old_price, $unit) ?></strike></span>
        <? } ?>
        <strong class="price"  itemprop="price"><?= price($price, $unit) ?></strong><br>



        <br>

        <? /* if(isset($rel10)){?>
          <a class="present" href="/catalog/<?=$this->cfg('CAT_IS_PRESENT')?>/rel/<?=$id?>,10/">�������</a>
          <?} */ ?>


        </br>
        <div class="discount"><?= $this->getText('msg_discount') ?></div>
        <? include('template/common/social.tpl.php') ?>



    </div>

    <br>
    <br>



    <div id="tabs">
        <ul class="tabs">
            <? if (!empty($data['html'])) { ?><li><a href="#t1">��������</a></li><? } ?>
            <? if (!empty($prop)) { ?><li><a href="#t2">��������������</a></li><? } ?>
            <? if (!empty($rel1)) { ?><li><a href="#t11">�������</a></li><? } ?>
            <? if (!empty($rel2)) { ?><li><a href="#t12">��������� ������</a></li><? } ?>
            <? if (!empty($rel3)) { ?><li><a href="#t13">�������������</a></li><? } ?>
            <? if ($comment_enabled) { ?>
                <li><a href="#t4">������ <span><?= $comment_count ?></span></a></li>
            <? } ?>
        </ul>


        <? if (!empty($data['html'])) { ?>
            <div id="t1" class="tabs"  itemprop="description">
                <?= $data['html'] ?>
            </div>
        <? } ?>

        <div id="t2" class="tabs">
            <? propList($prop); ?>
        </div>

        <? if (!empty($rel1)) { ?>
            <div id="t11" class="tabs">
                <?= $this->render(array('catalog' => $rel1), dirname(__FILE__) . "/catalog_view_table.tpl.php") ?>
            </div>
        <? } ?>
        <? if (!empty($rel2)) { ?>
            <div id="t12" class="tabs">
                <?= $this->render(array('catalog' => $rel2), dirname(__FILE__) . "/catalog_view_table.tpl.php") ?>
            </div>
        <? } ?>
        <? if (!empty($rel3)) { ?>
            <div id="t13" class="tabs">
                <?= $this->render(array('catalog' => $rel3), dirname(__FILE__) . "/catalog_view_table.tpl.php") ?>
            </div>
        <? } ?>
        <? /* div id="t3" class="tabs"><?=$data['html3']?></div */ ?>
        <? if ($comment_enabled) { ?>
            <div id="t4" class="tabs goods_comment">
                <? include('comment.tpl.php') ?>
            </div>
        <? } ?>
    </div>



    <? /* if(isset($rel1)){?>
      <h2>�������� �����:</h2>
      <?=$this->render(array('catalog'=>$rel1),dirname(__FILE__)."/catalog_view_table.tpl.php")?>

      <?} */ ?>

    <script defer src="/colorbox1.5.13/jquery.colorbox-min.js"></script>
    <script defer type="text/javascript" src="/modules/catalog/goods.js"></script>
    
    <? include('template/common/yametrica_goods.tpl.php') ?>