<?if($metric=$this->cfg('METRIC_YANDEX')){?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
window.dataLayer = window.dataLayer || [];
window.yaParams=window.yaParams||{ };
//window.yaParams.scope={};

window.yaParams.Client_ID='<?=$this->getClientId()?>';
//window.yaParams.scp.clid='<?=$this->getClientId()?>';
<?if($this->getUserId()){?>
window.yaParams.User_ID='<?=$this->getUserId()?>';
<?}?>

//window.yaParams.scp.uid='<?=$this->getUserId()?>';

<?if(!empty($data['metrica']['order_id'])){?>
window.yaParams.Ord_ID='<?=$data['metrica']['order_id']?>';
<?if($r=$this->getFirstReferer()){?>
	window.yaParams.scope={Ord_Ref:{'<?=$data['metrica']['order_id']?>':'<?=$r?>'}};
<?}?>


//window.yaParams.scp.oid='<?=$data['metrica']['order_id']?>';
<?}?>

<?if(!empty($data['metrica']['order_price'])){?>
window.yaParams.Ord_prc=<?=$data['metrica']['order_price']?>;
//window.yaParams.scp.oprice=<?=$data['metrica']['order_price']?>;
<?}?>

<?if($r=$this->getReferer()){?>
window.yaParams.Referer='<?=$r?>';
//window.yaParams.scp.ref='<?=$r?>';
<?}?>

(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter<?=$metric?> = new Ya.Metrika({id:<?=$metric?>,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    ecommerce: true,
                    params:window.yaParams||{ }});
        } catch(e) { }
    });
 
    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
 
    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/<?=$metric?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?}?>