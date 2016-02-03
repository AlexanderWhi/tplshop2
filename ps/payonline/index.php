<?
$MrchLogin="Cupicup";
$MrchPass1="qwer1234";
$OutSum="30.00";
$InvId=time();
$Desc="opisanie+pokupki";
$Encoding="windows-1251";
$IncCurrLabel="PCR";
$Culture="ru";

$Shp_demo=0;
$Shp_item=2;

//$SignatureValue=strtoupper(md5("$MrchLogin:$OutSum:$InvId:$MrchPass1:$Shp_demo:2"));
$SignatureValue=strtoupper(md5("$MrchLogin:$OutSum:$InvId:$MrchPass1:Shp_demo=$Shp_demo:Shp_item=$Shp_item"));


//header("Location: http://test.robokassa.ru/Index.aspx?MrchLogin=$MrchLogin&OutSum=$OutSum&InvId=$InvId&Desc=$Desc&Shp_demo=$Shp_demo&Shp_item=$Shp_item&SignatureValue=$SignatureValue&Culture=$Culture&IncCurrLabel=$IncCurrLabel&Encoding=$Encoding");
//exit;
?>

<script language='javascript' type='text/javascript' 
src='https://merchant.roboxchange.com/Handler/MrchSumPreview.ashx?MrchLogin=<?=$MrchLogin?>&OutSum=<?=$OutSum?>&InvId=<?=$InvId?>&Desc=<?=$Desc?>&Shp_demo=<?=$Shp_demo?>&Shp_item=<?=$Shp_item?>&SignatureValue=<?=$SignatureValue?>&Culture=<?=$Culture?>&IncCurrLabel=<?=$IncCurrLabel?>&Encoding=<?=$Encoding?>'></script>

<!--
<script language='javascript' type='text/javascript' 
src='https://test.roboxchange.com/Handler/MrchSumPreview.ashx?MrchLogin=<?=$MrchLogin?>&OutSum=<?=$OutSum?>&InvId=<?=$InvId?>&Desc=<?=$Desc?>&Shp_demo=<?=$Shp_demo?>&Shp_item=<?=$Shp_item?>&SignatureValue=<?=$SignatureValue?>&Culture=<?=$Culture?>&IncCurrLabel=<?=$IncCurrLabel?>&Encoding=<?=$Encoding?>'></script>
-->