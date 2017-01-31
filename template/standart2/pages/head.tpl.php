<? $no_cache = '0' ?>
<title><?= $this->getTitle() ?> <?= $this->cfg('title') ?> </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="title" content="<?= htmlspecialchars($this->getTitle()) ?> <?= $this->cfg('title') ?>">
<meta name="keywords" content="<?= $this->getKeywords() ?>"/>
<meta name="description" content="<?= $this->getDescription() ?>"/>
<meta http-equiv="Content-Type" content="text/html;charset=windows-1251;">
<? if (isset($_GET['p']) || ($this->getUriVal('manid') && count(explode(',', $this->getUriVal('manid'))) > 1) || $this->getURIVal('ord')) { ?>
    <meta name="robots" content="noindex"/>
    <meta name="robots" content="nofollow" />
<? } ?>

<link href="/favicon.ico?<?= $no_cache ?>" rel="shortcut icon" />

<link rel="alternate" type="application/rss+xml" title="�������� �� �������" href="http://<?= $_SERVER['HTTP_HOST'] ?>/news/rss/" >
<? /* link rel="alternate" type="application/rss+xml" title="�������� �� �������" href="http://<?=$_SERVER['HTTP_HOST']?>/action/rss/" */ ?>
<? /* link rel="alternate" type="application/rss+xml" title="�������� �� ������" href="http://<?=$_SERVER['HTTP_HOST']?>/awards/rss/" / */ ?>

<link type="text/css" href="/template/common/style/coin.css"  rel="stylesheet"  media="none" onload="media = 'all'">
<? /*

  <link type="text/css" href="/template/<?= $this->theme() ?>/style/default.css"  rel="stylesheet">
  <link type="text/css" href="/template/<?= $this->theme() ?>/style/style.css"  rel="stylesheet">
  <link type="text/css" href="/template/<?= $this->theme() ?>/style/shop.css"  rel="stylesheet">
  <link type="text/css" href="/template/<?= $this->theme() ?>/style/common.css?<?= $no_cache ?>" rel="stylesheet">
 */ ?>
<?
$style=array("template/{$this->theme()}/style/default.css",
    "template/{$this->theme()}/style/style.css",
    "template/{$this->theme()}/style/shop.css",
    "template/{$this->theme()}/style/common.css");
    if($this->getUri() == '/'){
        $style[]="template/{$this->theme()}/style/main.css";
        $style[]="template/{$this->theme()}/style/left_bar.css";
    }
?>

<link type="text/css" href="/<?=ClosureCompile::css2min($style)?>"  rel="stylesheet">

<link type="text/css" href="/datepicker/datepicker.css" rel="stylesheet" media="none" onload="media = 'all'">
<link type="text/css" href="/autocomplete/jquery.autocomplete.css" rel="stylesheet"  media="none" onload="media = 'all'">
<link type="text/css" href="/template/<?= $this->theme() ?>/style/easyslider.css"  rel="stylesheet"  media="none" onload="media = 'all'">
<link type="text/css" href="/ui-1.11.1/jquery-ui.min.css" rel="stylesheet"  media="none" onload="media = 'all'">
<link type="text/css" href="/colorbox1.5.13/example1/colorbox.css" rel="stylesheet"  media="none" onload="media = 'all'">