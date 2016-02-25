<?php

include_once 'core/component/Component.class.php';

class Join extends Component {

    protected $mod_name = 'Вход';
    protected $mod_title = 'Вход';

    function actDefault() {
        global $get;
        $data = array('error' => false);
        if ($get->get('error') == 'true') {
            $data['error'] = true;
        }
        $this->setTitle('Вход в систему');
        $this->setHeader('Вход в систему');
        $this->display($data, dirname(__FILE__) . '/login.tpl.php');
    }

    function actLogin() {
        global $ST, $post;

        $q = "SELECT u_id,type,password FROM sc_users 
			WHERE (login='" . $post->get('login') . "'";

        $q.=") AND (password=MD5('" . $post->get('password') . "') 
				OR password=PASSWORD('" . $post->get('password') . "') 
				OR password=OLD_PASSWORD('" . $post->get('password') . "')
			)";

        $rs = $ST->select($q);

        if ($rs->next()) {
            if ($rs->get('password') != md5($post->get('password'))) {
                $ST->update('sc_users', array('password' => md5($post->get('password'))), "u_id={$rs->getInt('u_id')}");
            }

            my_session_start($post->exists('save'));

            $_SESSION['_USER']['u_id'] = $rs->getInt('u_id');
            $this->setUser($rs->getRow());
            if (preg_match('|/login|', $this->getURI())) {
                if ($this->isAdmin() || $this->getUser('type') == 'operator') {
                    if ($post->exists('ref') && $post->get('ref')) {
                        header("Location: {$post->get('ref')}");
                        exit;
                    }
                    header('Location: /admin/');
                    exit;
                }
                header('Location: /shop/');
                exit;
            }
            if ($this->isAdmin() || $this->getUser('type') == 'operator') {
                header('Location: /admin/');
                exit;
            }
            header('Location: /shop/');
            exit;
        } else {
            header('Location: /join/?error=true');
            exit;
        }
        header('Location: /join/?error=true');
        exit;
    }

    /**
     * http://ulogin.ru/constructor.php
     *
     */
    function actULogin() {
        $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $user = getJSON($s);
        //$user['network'] - соц. сеть, через которую авторизовался пользователь
        //$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
        //$user['first_name'] - имя пользователя
        //$user['last_name'] - фамилия пользователя

        my_session_start();

        $login = $user['network'] . $user['uid'];
        $rs = DB::select("SELECT * FROM sc_users WHERE login='$login'");

        $data = array(
            'name' => "{$user['first_name']} {$user['last_name']}",
        );

        if ($rs->next()) {
            DB::update('sc_users', $data, "u_id={$rs->getInt('u_id')}");

            $_SESSION['_USER']['u_id'] = $rs->getInt('u_id');
            $this->setUser($rs->getRow());
        } else {
            $data['login'] = $login;
            $data['type'] = 'user';
            $id = DB::insert('sc_users', $data);
            $_SESSION['_USER']['u_id'] = $id;
            $this->setUser($data);
        }
        header('Location: /');
        exit;
    }

    function actSignup() {
        global $post;
        $data = array(
            'mail' => $post->get('login'),
            'password' => $post->get('password'),
        );
        $this->mod_name = 'Регистрация';
        $this->mod_title = 'Регистрация';
        $this->setCommonCont();
        $this->display($data, dirname(__FILE__) . '/signup.tpl.php');
    }

    function actSign() {
        include_once 'modules/shop/ShopBonus.class.php';
        global $ST, $post;

        if (!$err = $this->checkAll($post)) {
            $field = array(
                "login" => $post->get('mail'),
                "name" => $post->get('name'),
                "address" => $post->get('address'),
//				"street" =>$post->get('street'),
//				"house" =>$post->get('house'),
//				"flat" =>$post->get('flat'),
//				"porch" =>$post->get('porch'),
//				"floor" =>$post->get('floor'),
                "phone" => $post->get('phone'),
                "mail" => $post->get('mail'),
            );
            //Добавим реферала
            if($refid=$post->getInt('refid')){
                $rs=DB::select("SELECT * FROM sc_users WHERE u_id=$refid");
                if($rs->next()){
                    $field['refid']=$post->getInt('refid');
                }else{
                    //Если неправильный refid
                } 
            }
            if($refid=  ShopBonus::getPromoRefId($post->get('promo'))){
                $rs=DB::select("SELECT * FROM sc_users WHERE u_id=$refid");
                if($rs->next()){
                    $field['refid']=$post->getInt('refid');
                }else{
                    //Если неправильный refid
                } 
            }
            if (!$post->exists('address')) {
                $field['address'] = $field['street'] . ', ' . $field['house'] . '-' . $field['flat'] . ', подъезд ' . $field['porch'] . ', этаж ' . $field['floor'];
            }
            $password = substr(md5(time()), 0, 6);
            if ($p = $post->get('password')) {
                $password = $p;
            }
            $field[] = "password=MD5('" . $password . "')";
            $id = $ST->insert('sc_users', $field, 'u_id');

            my_session_start();

            $_SESSION['_USER']['u_id'] = intval($id);
            //уведомление о регистрации
            $this->sendTemplateMail($field['mail'], 'notice_new_user', array('FROM_SITE' => FROM_SITE, 'LOGIN' => $field['login'], 'PASSWORD' => $password)
            );
            //уведомление о регистрации админу
            $this->sendTemplateMail($this->cfg('MAIL'), 'notice_new_user4admin', array('FROM_SITE' => FROM_SITE, 'LOGIN' => $field['login'], 'name' => $field['name'])
            );
            $this->noticeICQ($this->cfg('ICQ'), 'Новый пользователь на сайте');
            //Добавим в подписку
//          	$rs=$ST->select("SELECT * FROM sc_subscribe WHERE mail='{$this->field['mail']}'");
//          	if(!$rs->next()){
//          		$ST->insert('sc_subscribe',array('mail'=>$this->field['mail'],'type'=>'news send'));
//          	}

            echo printJSON(array('status' => 'ok'));
            exit;
        } else {
            echo printJSON(array('error' => $err));
            exit;
        }
    }

    function actQSign() {
        global $ST, $post;

        if ($err = $this->qcheckAll($post)) {
            echo printJSON(array('err' => $err));
            exit;
        } else {

            $field = array(
                "name" => $post->get('name'),
            );

            if (!$password = $post->get('password')) {
                $password = substr(md5(time() . $post->get('mail')), 0, 6);
            }
            $field[] = "password=MD5('" . $password . "')";

            $rs = $ST->select("SELECT * FROM sc_users WHERE login='{$post->get('mail')}' OR mail='{$post->get('mail')}'");
            if ($rs->next()) {
                $id = $ST->update('sc_users', $field, "u_id={$rs->getInt('u_id')}");
                $field = $rs->getRow() + $field;
            } else {
                $field["login"] = $post->get('mail');
                $field["mail"] = $post->get('mail');
                $id = $ST->insert('sc_users', $field, 'u_id');
            }



//          	$_SESSION['_USER']['u_id']=intval($id);
            //уведомление о регистрации
            $this->sendTemplateMail($field['mail'], 'notice_new_user', array('FROM_SITE' => FROM_SITE, 'LOGIN' => $field['login'], 'PASSWORD' => $password)
            );
            //уведомление о регистрации админу
            $this->sendTemplateMail($this->cfg('MAIL'), 'notice_new_user4admin', array('FROM_SITE' => FROM_SITE, 'LOGIN' => $field['login'])
            );
            $this->noticeICQ($this->cfg('ICQ'), 'Новый пользователь на сайте');
            echo printJSON(array('msg' => 'Вы успешно зарегистрировались, логин пароль были отправлены на почту'));
            exit;
        }
    }

    function checkAll($args) {
        //Очистим ошибки
        $err = array();
//		if($args->exists('login')){
//			if(!LibJoin::checkLogin($args->get('login'))){
//				$valid=false;
//			}
////			if(!$this->checkPassword($args->get('password'),$args->get('cpassword'))){
////				$valid=false;
////			}
//		}
        if ($e = LibJoin::checkMail($args->get('mail'))) {
            $err['mail'] = $e;
        }

        if (!trim($args->get('name'))) {
            $err['name'] = "Введите ФИО";
        }


        if (!$this->checkCapture($args->get('capture'))) {
            $err['capture'] = "Введите правильный код";
        }
        return $err;
    }

    function qcheckAll($args) {
        //Очистим ошибки
        $error = array();

        if ($err = LibJoin::checkMail($args->get('mail'), false)) {
            $error['mail'] = $err;
        }
        if (!trim($args->get('name'))) {
            $error['name'] = 'Введите ФИО';
        }

//		if($this->checkCapture()){
//			$this->error['capture']="Введите правильный код!";
//          	$valid=false;
//		}
        return $error;
    }

}

?>