<!DOCTYPE html>
<html>
    <head>
        <? include('head.tpl.php') ?>
          </head>
    <body>
        <? include('modules/ceo/ceo.tpl.php') ?>
        
        <div id="wrap" >
            <? include('header.tpl.php') ?>


            <? include('banner.tpl.php') ?>

            <div id="middle" class="border">



                <? //=$this->getCeoText('1')?>
                <?= $CONTENT; ?>
                <? //=$this->getCeoText('2')?>

                <? /* div style="padding-top:30px;color:#aaa">
                  Увидели неточность? Будьте любезны - Выделите и нажмите Ctrl+Enter
                  </div */ ?>

            </div><!--middle-->
        </div>
        <!--/#wrap-->

        <? include('footer.tpl.php') ?>

<!--        <div id="notice"></div>
        <div id="debug"></div>-->
    </body>
</html>
