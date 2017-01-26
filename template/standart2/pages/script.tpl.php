<script type="text/javascript" src="/<?= ClosureCompile::compile('js/jquery/jquery-1.6.4.min.js')?>"></script>
<!--<script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="/<?= ClosureCompile::compile('js/common.js')?>"></script>
<script type="text/javascript" src="/<?= ClosureCompile::compile('js/site.js')?>"></script>

<script type="text/javascript" src="/<?= ClosureCompile::compile('scroll/mousewheel.js')?>"></script>
<script type="text/javascript" src="/<?= ClosureCompile::compile('scroll/scrollable.js')?>"></script>

<script type="text/javascript" src="/<?= ClosureCompile::compile('js/slider.js')?>"></script>
<script type="text/javascript" src="/ui-1.11.1/jquery-ui.min.js"></script>

<!--<script type="text/javascript" src="/<?= ClosureCompile::compile('feedback/feedback.js')?>"></script>-->

<script type="text/javascript" src="/<?= ClosureCompile::compile('autocomplete/jquery.autocomplete.js')?>"></script>

<!--Интернет магазин, функционал каталога-->
<script type="text/javascript" src="/<?= ClosureCompile::compile('modules/catalog/shop.js')?>"></script>
<!--<script type="text/javascript" src="/consultant/consultant.js"></script>-->

<script type="text/javascript">
    var SLIDE_SPEED =<?= intval($this->cfg('SLIDE_SPEED')) ?>;
    $(function () {
        $('.coin-text-edit,.coin-place-edit,.coin-banner-edit').fadeTo(0, 0.7).hover(function () {
            $(this).fadeTo(0, 1);
        }, function () {
            $(this).fadeTo(0, 0.7);
        });
    });
</script>
<script src="/<?= ClosureCompile::compile('modules/contacts/common_contacts.js')?>"></script>