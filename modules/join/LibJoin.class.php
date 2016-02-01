<?
class LibJoin{
	static function checkMail($mail,$userid=0){
		if(!check_mail($mail)){
			return 'Введите правильный e-mail';
		}elseif($userid!==null){
			$rs=DB::select("SELECT * FROM sc_users WHERE (mail=:mail OR login=:mail) AND u_id!=:u_id  LIMIT 1",
			array(
				'mail'=>$mail,
				'login'=>$mail,
				'u_id'=>$userid,
			));
			if($rs->next()){
				return 'e-mail уже зарегистрирован';
			}
		}
		return '';
	}
	static function checkLogin($login){
		$err='';
		if(!trim($login)){
			$err='Введите логин';
		}elseif(strlen($login)>16){
			$err='Логин должен содержать не более 16 символов';
		}else{
			$rs=DB::select("SELECT * FROM sc_users WHERE login=:login LIMIT 1",array('login'=>$login));
			if($rs->next()){
				$err='Логин уже зарегистрирован';
			}
		}
		return $err;
	}

	
	static function checkPassword($password,$cpassword=null){
		$err='';
		if(!preg_match('!^([0-9a-z]{6,16})$!i',$password)){
			$err='Пароль должен содержать латинские символы и цифры длиной не менее 6 не более 16';
		}elseif($password!=$cpassword && $cpassword!=null){
			$err='Пароль и подтверждение не совпадают';
		}
		return $err;
	}
}
?>