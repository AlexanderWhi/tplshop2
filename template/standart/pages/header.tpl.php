<? $no_cache = '0' ?>
<title><?= $this->getTitle() ?> <?= $this->cfg('title') ?> </title>
<meta name="title" content="<?= htmlspecialchars($this->getTitle()) ?> <?= $this->cfg('title') ?>"/>
<meta name="keywords" content="<?= $this->getKeywords() ?>"/>
<meta name="description" content="<?= $this->getDescription() ?>"/>
<meta http-equiv="Content-Type" content="text/html;charset=windows-1251;"/>
<? if (isset($_GET['p']) || ($this->getUriVal('manid') && count(explode(',', $this->getUriVal('manid'))) > 1) || $this->getURIVal('ord')) { ?>
    <meta name="robots" content="noindex"/>
    <meta name="robots" content="nofollow" />
<? } ?>

<? if ($f = $this->cfg('FAVICON_PATH')) { ?>
    <link href="<?= $f ?>?<?= $no_cache ?>" rel="shortcut icon" />
<? } else { ?>
    <link href="/favicon.ico?<?= $no_cache ?>" rel="shortcut icon" />
<? } ?>




<link rel="alternate" type="application/rss+xml" title="Подписка на новости" href="http://<?= $_SERVER['HTTP_HOST'] ?>/news/rss/" >
<? /* link rel="alternate" type="application/rss+xml" title="Подписка на события" href="http://<?=$_SERVER['HTTP_HOST']?>/action/rss/" */ ?>
<? /* link rel="alternate" type="application/rss+xml" title="Подписка на отзывы" href="http://<?=$_SERVER['HTTP_HOST']?>/awards/rss/" / */ ?>

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

<link type="text/css" href="/autocomplete/jquery.autocomplete.css"  rel="stylesheet"/>

<link rel="stylesheet" type="text/css" href="/template/<?= $this->theme() ?>/style/common.css?<?= $no_cache ?>" />


<? if ($this->getUri() == '/') { ?><link rel="stylesheet" type="text/css" href="/template/<?= $this->theme() ?>/style/main.css?<?= $no_cache ?>" /><? } ?>

<link rel="stylesheet" type="text/css" href="/datepicker/datepicker.css" />

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