<div class="block_info">
Ваша ссылка для привлечения рефералов<br>
<strong>http://<?=$_SERVER['HTTP_HOST']?>/?refid=<?=$this->getUserId()?></strong>
<br>

<script type="text/javascript">(function() {
          if (window.pluso)if (typeof window.pluso.start == "function") return;
          var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
          s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
          s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
          var h=d[g]('head')[0] || d[g]('body')[0];
          h.appendChild(s);
          })();</script>
        <div class="pluso" data-options="small,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir" data-background="transparent" data-url="http://<?=$_SERVER['HTTP_HOST']?>/?refid=<?=$this->getUserId()?>" data-title="<?=my_htmlspecialchars($this->cfg('HEADER_TITLE'))?>"></div>

<br>
<br>

<?=$this->getText('referal_info')?>

</div>