<?
$xml=file_get_contents('12121299_1.XML');

$url="http://www.a-yabloko.ru/import/import.php";//� ����� ������
$url="http://yabloko.loc/import/import.php";//� ���� ������


$u = curl_init();
curl_setopt($u, CURLOPT_POST, True);
curl_setopt($u, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($u, CURLOPT_URL, $url);
curl_setopt($u, CURLOPT_POSTFIELDS, $xml);

//curl_setopt($u, CURLOPT_USERPWD, '111'.":".'111');//�� ������� �������������� �� HTTP

$result = curl_exec($u);
exit;

?>



<?

$xml="<����_���>";

$url="http://www.a-yabloko.ru/import/import.php";//� ����� ������

$u = curl_init();
curl_setopt($u, CURLOPT_POST, True);
curl_setopt($u, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($u, CURLOPT_URL, $url);
curl_setopt($u, CURLOPT_POSTFIELDS, $xml);

curl_setopt($u, CURLOPT_USERPWD, '111'.":".'111');//�� ������� �������������� �� HTTP

$result = curl_exec($u);

?>