<?
$result=print_r($_GET,true)."\n";
$result.=print_r($_POST,true)."\n";
$result.=print_r($_SERVER,true)."\n";

$f=fopen("log.txt","a");
fwrite($f,date("Y-m-d H:i:s")." success\n");
fwrite($f,$result);
fclose($f);

header("Location: /cabinet/");
?>