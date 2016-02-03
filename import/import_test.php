<?
$xml=file_get_contents('12121299_1.XML');

$url="http://www.a-yabloko.ru/import/import.php";//в вашем случае
$url="http://yabloko.loc/import/import.php";//в моем случае


$u = curl_init();
curl_setopt($u, CURLOPT_POST, True);
curl_setopt($u, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($u, CURLOPT_URL, $url);
curl_setopt($u, CURLOPT_POSTFIELDS, $xml);

//curl_setopt($u, CURLOPT_USERPWD, '111'.":".'111');//Ќе забудем авторизоватьс€ по HTTP

$result = curl_exec($u);
exit;

?>



<?

$xml="<ваша_хмл>";

$url="http://www.a-yabloko.ru/import/import.php";//в вашем случае

$u = curl_init();
curl_setopt($u, CURLOPT_POST, True);
curl_setopt($u, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($u, CURLOPT_URL, $url);
curl_setopt($u, CURLOPT_POSTFIELDS, $xml);

curl_setopt($u, CURLOPT_USERPWD, '111'.":".'111');//Ќе забудем авторизоватьс€ по HTTP

$result = curl_exec($u);

?>