<?php
//6334, A0923B6DD3A02E0E92BA2F98BFBD890F, 324976553, 150
strtoupper(md5('2nguttx'));
strtoupper(md5('324976553' . strtoupper(md5('2nguttx'))));
include("IShopServerWSService.php");

$service = new IShopServerWSService('IShopServerWS.wsdl', array('location'      => 'http://mobw.ru/services/ishop', 'trace' => 1));
$params = new cancelBill();
$params->login = LOGIN;
$params->password = 'PASSWORD';
$params->txn = '-123456789';
$res = $service->cancelBill($params);

print($res->cancelBillResult);

print($service->__getLastRequest());

?>
