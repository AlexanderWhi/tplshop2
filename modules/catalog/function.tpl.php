<?
function parsAddr($str){
	if($data=@unserialize($str)){
		$str='';
		if(!empty($data['salon_obl'])){$str.=$data['salon_obl'];}
		if(!empty($data['salon_city'])){$str.=', '.$data['salon_city'];}
		if(!empty($data['salon_name'])){$str.=', '.$data['salon_name'];}
		if(!empty($data['region'])){$str.=$data['region'];}
		if(!empty($data['city'])){$str.=', '.$data['city'];}
		if(!empty($data['district'])){$str.=', '.$data['district'];}
		if(!empty($data['street'])){$str.=', '.$data['street'];}
		if(!empty($data['house'])){$str.=', ��� '.$data['house'];}
		if(!empty($data['flat'])){$str.=', �������� '.$data['flat'];}
		if(!empty($data['porch'])){$str.=', ������� '.$data['porch'];}
		if(!empty($data['floor'])){$str.=', ���� '.$data['floor'];}
		return $str;
	}
	return $str;
}

function parsJur($str){
	/*
	<label class="i">��������:</label><input name="jur[name]" class="field"><br>
<label class="i">��.�����:</label><input name="jur[addr_jur]" class="field"><br>
<label class="i">����.�����:</label><input name="jur[addr]" class="field"><br>
<label class="i">���:</label><input name="jur[inn]" class="field"><br>
<label class="i">���:</label><input name="jur[kpp]" class="field"><br>
<label class="i">����:</label><input name="jur[ogrn]" class="field"><br>
	*/
	if($data=@unserialize($str)){
		$str='';
		if(!empty($data['name'])){$str.=$data['name'];}
		if(!empty($data['addr_jur'])){$str.=', ��.�����:'.$data['addr_jur'];}
		if(!empty($data['addr'])){$str.=', ����.�����:'.$data['addr'];}
		
		if(!empty($data['inn'])){$str.=', ���:'.$data['inn'];}
		if(!empty($data['kpp'])){$str.=', ���:'.$data['kpp'];}
		if(!empty($data['ogrn'])){$str.=', ����:'.$data['ogrn'];}
		
		return $str;
	}
	return $str;
}
?>