<?php
  // ������� ������� ������ � ��������� � ���������
function encodestring($st){
    // ������� �������� "��������������" ������.
    $st=strtr($st,"������������������������",
    "abvgdeeziyklmnoprstufh'ie");
    $st=strtr($st,"�����Ũ������������������",
    "ABVGDEEZIYKLMNOPRSTUFH'IE");
    $st=strtr($st," /.,%",
    "_____");
    
    $st=str_replace('"','',$st);
    $st=str_replace("'",'',$st);
    // ����� - "���������������".
    $st=strtr($st, 
                    array(
                        "�"=>"zh", "�"=>"ts", "�"=>"ch", "�"=>"sh", 
                        "�"=>"shch","�"=>"", "�"=>"yu", "�"=>"ya",
                        "�"=>"ZH", "�"=>"TS", "�"=>"CH", "�"=>"SH", 
                        "�"=>"SHCH","�"=>"", "�"=>"YU", "�"=>"YA",
                        "�"=>"i", "�"=>"Yi", "�"=>"ie", "�"=>"Ye"
                        )
             );
    // ���������� ���������.
    return $st;
}
?>
