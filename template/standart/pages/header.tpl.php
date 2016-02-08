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

<link type="text/css" href="/autocomplete/jquery.autocomplete.css"  rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="/template/<?= $this->theme() ?>/style/common.css?<?= $no_cache ?>" />


<? if ($this->getUri() == '/') { ?><link rel="stylesheet" type="text/css" href="/template/<?= $this->theme() ?>/style/main.css?<?= $no_cache ?>" /><? } ?>

<link rel="stylesheet" type="text/css" href="/datepicker/datepicker.css" />

