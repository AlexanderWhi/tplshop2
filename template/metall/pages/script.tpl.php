<script type="text/javascript" src="/js/jquery/jquery-1.6.4.min.js?<?= $no_cache ?>"></script>
<!--<script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="/js/common.js?<?= $no_cache ?>"></script>
<script type="text/javascript" src="/js/site.js?<?= $no_cache ?>"></script>

<script type="text/javascript" src="/scroll/mousewheel.js"></script>
<script type="text/javascript" src="/scroll/scrollable.js"></script>


<script type="text/javascript" src="/js/slider.js"></script>
<script type="text/javascript" src="/ui-1.11.1/jquery-ui.min.js"></script>

<!--<script type="text/javascript" src="/feedback/feedback.js"></script>-->

<script type="text/javascript" src="/autocomplete/jquery.autocomplete.js"></script>

<!--Интернет магазин, функционал каталога-->
<script type="text/javascript" src="/modules/catalog/shop.js?<?= $no_cache ?>"></script>
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
<script src="/modules/contacts/common_contacts.js?<?= $no_cache ?>"></script>