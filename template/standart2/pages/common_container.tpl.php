<!DOCTYPE html>
<html>
    <head>
        <? include('head.tpl.php') ?>
    </head>
    <body>
        <? include('modules/ceo/ceo.tpl.php') ?>
      

        <div id="wrap"><!--#wrap-->	
            <? include('header.tpl.php') ?>	

            <div id="middle" class="border">	
                <div class="wrapper">
                    <div id="center-bar" class="border">
                        <div id="tips"><?= $this->renderExplorer(' / ') ?></div>

                        <div id="content" class="bar">

                            <? //=$this->getCeoText('1')?>
                            <h1><?= $this->getHeader() ?></h1>
                            <?= $CONTENT; ?>

                        </div>
                    </div>
                </div>
            </div>
            <? //include('footer_catalog.tpl.php')?>

        </div>
        <!--/#wrap-->
        <? include('footer.tpl.php') ?>

        <div id="debug"></div>

    </body>
</html>