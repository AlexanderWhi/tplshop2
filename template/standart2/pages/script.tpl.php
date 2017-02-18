<script type="text/javascript" src="/js/jquery/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-migrate-1.3.0.js"></script>
<script type="text/javascript" src="/<?= ClosureCompile::compile('js/common.js','cp1251')?>"></script>
<script type="text/javascript" src="/<?= ClosureCompile::compile('js/site.js','cp1251')?>"></script>

<script type="text/javascript" src="/<?= ClosureCompile::compile('scroll/mousewheel.js')?>"></script>
<script type="text/javascript" src="/<?= ClosureCompile::compile('scroll/scrollable.js')?>"></script>

<script type="text/javascript" src="/<?= ClosureCompile::compile('js/slider.js','cp1251')?>"></script>
<script type="text/javascript" src="/ui-1.11.1/jquery-ui.min.js"></script>

<script type="text/javascript" src="/<?= ClosureCompile::compile('autocomplete/jquery.autocomplete.js','cp1251')?>"></script>

<!--Интернет магазин, функционал каталога-->
<script type="text/javascript" src="/<?= ClosureCompile::compile('modules/catalog/shop.js','cp1251')?>"></script>
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
<script src="/<?= ClosureCompile::compile('modules/contacts/common_contacts.js','cp1251')?>"></script>