<?
class LibJoin{
	static function checkMail($mail,$userid=0){
		if(!check_mail($mail)){
			return '������� ���������� e-mail';
		}elseif($userid!==null){
			$rs=DB::select("SELECT * FROM sc_users WHERE (mail=:mail OR login=:mail) AND u_id!=:u_id  LIMIT 1",
			array(
				'mail'=>$mail,
				'login'=>$mail,
				'u_id'=>$userid,
			));
			if($rs->next()){
				return 'e-mail ��� ���������������';
			}
		}
		return '';
	}
	static function checkLogin($login){
		$err='';
		if(!trim($login)){
			$err='������� �����';
		}elseif(strlen($login)>16){
			$err='����� ������ ��������� �� ����� 16 ��������';
		}else{
			$rs=DB::select("SELECT * FROM sc_users WHERE login=:login LIMIT 1",array('login'=>$login));
			if($rs->next()){
				$err='����� ��� ���������������';
			}
		}
		return $err;
	}

	
	static function checkPassword($password,$cpassword=null){
		$err='';
		if(!preg_match('!^([0-9a-z]{6,16})$!i',$password)){
			$err='������ ������ ��������� ��������� ������� � ����� ������ �� ����� 6 �� ����� 16';
		}elseif($password!=$cpassword && $cpassword!=null){
			$err='������ � ������������� �� ���������';
		}
		return $err;
	}
}
?>