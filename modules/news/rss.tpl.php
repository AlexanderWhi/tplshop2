<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title>Новости акции на сайте <?=$_SERVER['HTTP_HOST']?></title>
<link>http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?></link> 
<description>Новости акции на сайте <?=$_SERVER['HTTP_HOST']?>.</description>
<image>
    <url>http://<?=$_SERVER['HTTP_HOST'].'/img/'?>logoRSS.gif</url>
	<title>Новости акции на сайте <?=$_SERVER['HTTP_HOST']?></title>
	<link>http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/'?></link>
	</image> 
<pubDate><?=date('D, d M Y H:i:s', time()-$minusdate)?> GMT</pubDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<language>ru</language>
<?foreach ($this->rs as $item) {?>
<item>
<title><?=$item['title'];?></title>
<link>http://<?=$_SERVER['HTTP_HOST'].'/'.$this->type.'/view/'.$item['id']?></link>
<description>
<![CDATA[
<?=$item['descroption'];?>
]]>
</description>
<pubDate><?=date('D, d M Y H:i:s', strtotime($item['date'])-$minusdate)?> GMT</pubDate>
</item>
<?}?>
</channel>
</rss>