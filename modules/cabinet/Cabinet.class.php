<?php

include_once("core/component/Component.class.php");

class Cabinet extends Component {

    function __construct() {
//		$this->setCommonCont();
        $this->tplContainer = "template/www/pages/common_container_lc.tpl.php";
        $this->tplLeftComponent = dirname(__FILE__) . "/cabinet_left.tpl.php";
    }

    protected $mod_name = 'Кабинет';
    protected $mod_title = 'Кабинет';

    function actDefault() {

        $this->actEdit();
        return;
        global $ST;
        $this->needAuth();
        $field = array(
            "login" => "",
            "name" => "",
            "address" => "",
            "street" => "",
            "house" => "",
            "flat" => "",
            "porch" => "",
            "floor" => "",
            "mail" => "",
            "phone" => ""
        );
        if ($this->getUserId()) {
            $queryStr = "SELECT " . join(',', array_keys($field)) . " FROM sc_users WHERE u_id =" . $this->getUserId();
            $rs = $ST->select($queryStr);
            if ($rs->next()) {
                $field = $rs->getRow();
            }
        }
        $this->display($field, dirname(__FILE__) . '/cabinet.tpl.php');
    }

    function actEdit() {

        $this->needAuth();
        $field = array(
            "login" => "",
            "name" => "",
            "address" => "",
            "mail" => "",
            "phone" => "",
        );
        if ($this->getUserId()) {
            $queryStr = "SELECT " . join(',', array_keys($field)) . " FROM sc_users WHERE u_id =" . $this->getUserId();
            $rs = DB::select($queryStr);
            if ($rs->next()) {
                $field = $rs->getRow();
                $this->setPageTitle('Редактировать данные');

                $this->display($field, dirname(__FILE__) . '/cabinet_edit.tpl.php');
            }
        }
    }

    function checkAll($args) {
        $err = array();
        if ($args->exists('login')) {
            if ($e = $this->checkLogin($args->get('login'))) {
                $err['login'] = $e;
            }
        }
        if ($e = $this->checkMail($args->get('mail'))) {
            $err['mail'] = $e;
        }

        if (!trim($args->get('name'))) {
            $err['name'] = "Введите фио!";
        }

        if (!trim($args->get('phone'))) {
            $err['phone'] = 'Введите телефон';
        }
//		if(!trim($args->get('address'))){
//			$err['town']="Введите адрес!";
//	       
//		}

        if (trim($args->get('password'))) {
            if ($e = $this->checkPassword($args->get('password'), $args->get('cpassword'))) {
                $err['password'] = $e;
            }
        }
        return $err;
    }

    function checkMail($mail) {
        global $ST;
        if (!check_mail($mail)) {
            return 'Введите правильный e-mail';
        } else {
            $rs = $ST->select("SELECT * FROM sc_users WHERE mail='" . SQL::slashes($mail) . "' AND u_id!=" . $this->getUserId() . " LIMIT 1");
            if ($rs->next()) {
                return 'e-mail уже зарегистрирован';
            }
        }
    }

    function checkPassword($password, $cpassword = null) {
        if (!preg_match('!^([0-9a-z]{6,16})$!i', $password)) {
            return 'Пароль должен содержать латинские символы и цифры длиной не менее 6 не более 16';
        } elseif ($password != $cpassword && $cpassword !== null) {
            return 'Пароль и подтверждение не совпадают';
        }
    }

    function actSave() {
        global $ST, $post;
        $this->needAuth();
        if (!$err = $this->checkAll($post)) {
            $field = array(
                "name" => $post->get('name'),
                "address" => $post->get('address'),
                "street" => $post->get('street'),
                "house" => $post->get('house'),
                "flat" => $post->get('flat'),
                "porch" => $post->get('porch'),
                "floor" => $post->get('floor'),
                "phone" => $post->get('phone'),
                "mail" => $post->get('mail'),
            );

            if (!trim($field['address'])) {
                $field['address'] = $field['street'] . ', ' . $field['house'] . '-' . $field['flat'] . ', подъезд ' . $field['porch'] . ', этаж ' . $field['floor'];
            }

            if (trim($post->get('password'))) {
                $field['password'] = md5($post->get('password'));
            }
            $field['other_info'] = array_values($post->getArray('addr_data'));
            if (isset($field['other_info'][$post->get('addr')])) {
                $field['address'] = $field['other_info'][$post->get('addr')];
            }
            $field['other_info'] = printJSON($field['other_info']);

            $ST->update('sc_users', $field, "u_id=" . $this->getUserId());

            //уведомление о изменении данных
//          	$this->sendTemplateMail($field['mail'],'notice_new_user_pre',
//          		array('FROM_SITE'=>FROM_SITE,'LOGIN'=>$field['login'])
//          	);

            echo printJSON(array('status' => 'ok'));
            exit;
        } else {
            echo printJSON(array('error' => $err));
            exit;
        }
    }

    function actPassword() {
        global $ST, $get;
        $this->setPageTitle('Сменить пароль');

        if ($get->exists('key')) {
            $rs = $ST->select("SELECT * FROM sc_users WHERE key_unlock='" . SQL::slashes($get->get('key')) . "'");
            if ($rs->next()) {
                my_session_start();
                $_SESSION['_USER']['u_id'] = $rs->getInt('u_id');
                $this->setUser($rs->getRow());
            }
        }
        $this->needAuth();
        $data = array(
            'login' => $this->getUser('login')
        );
        $this->display($data, dirname(__FILE__) . '/cabinet_password.tpl.php');
    }

    function actSavePassword() {
        global $ST, $post;
        $this->needAuth();
        if ($this->checkPassword($post->get('password'), $post->get('cpassword'))) {
            $field = array("password=MD5('" . SQL::slashes($post->get('password')) . "')");
            $ST->update('sc_users', $field, "u_id=" . $this->getUserId());

            //уведомление о изменении данных
//          	$this->sendTemplateMail($field['mail'],'notice_new_user_pre',
//          		array('FROM_SITE'=>FROM_SITE,'LOGIN'=>$field['login'])
//          	);

            echo printJSON(array('status' => 'ok'));
            exit;
        } else {
            echo printJSON(array('error' => $this->error));
            exit;
        }
    }

    function actUnlock() {
        $this->setPageTitle('Забыли пароль');
        $this->display(array(), dirname(__FILE__) . '/cabinet_unlock.tpl.php');
    }

    function actSendUnlock() {
        global $ST, $post;

        if ($post->get('mail') == "") {
            $this->error['mail'] = "Введите e-mail!";
        } else {
            $rs = $ST->select("select * from sc_users where mail='" . SQL::slashes($post->get('mail')) . "'");
            if ($rs->next()) {
                $key = md5($rs->get("password") . $rs->get("login"));
                $ST->executeUpdate("update sc_users set key_unlock='" . $key . "' where mail='" . $post->get('mail') . "'");
                $to = $post->get('mail');
                $link = 'http://' . FROM_SITE . '/cabinet/password/?key=' . $key;
                if (trim($to) != "") {
                    $this->sendTemplateMail($to, 'unlock_account', array('FROM_SITE' => FROM_SITE, 'LINK' => $link));
                }
            } else {
                $this->error['mail'] = "Введённый e-mail не найден в базе!";
            }
        }
        if ($this->error['mail']) {
            echo printJSON(array('error' => $this->error));
            exit;
        } else {
            echo printJSON(array('status' => 'ok'));
            exit;
        }
    }

    /**
     * Активация бонуса
     *
     */
    function actActivateBonus() {
        global $ST, $post;

        $error = "";
        $summ = 0;

        $rs = $ST->select("SELECT * FROM sc_bonus WHERE code='" . SQL::slashes($post->get('num')) . "' AND pass='" . SQL::slashes($post->get('pass')) . "'");
        if ($rs->next()) {
            if ($rs->get('user') > 0) {
                if ($rs->get('user') == $this->getUserId()) {
                    $error = "Бонус уже активирован вами";
                } else {
                    $error = "Бонус уже активирован кем то";
                }
            } else {
                $ST->update("sc_users", array('bonus' => $rs->getInt('summ') + $this->getUser('bonus')), "u_id={$this->getUserId()}");
                $ST->insert("sc_income", array(
                    'userid' => $this->getUserId(),
                    'sum' => $rs->getInt('summ'),
                    'balance' => $rs->getInt('summ') + $this->getUser('bonus'),
                    'type' => 'activ_bonus',
                    'description' => "Bonus {$post->get('num')} {$post->get('pass')}"
                ));
                $ST->update("sc_bonus", array('user' => $this->getUserId(), 'act_time' => date('Y-m-d H:i:s')), "code='" . SQL::slashes($post->get('num')) . "' AND pass='" . SQL::slashes($post->get('pass')) . "'");
                $summ = $rs->getInt('summ');

                $this->sendTemplateMail($this->cfg('MAIL'), 'notice_activate_bonus', array('id' => $this->getUserId(), 'login' => $this->getUser('login'), 'code' => $post->get('num'), 'pass' => $post->get('pass'),)
                );
            }
        } else {
            $error = "Ошибка ввода данных";
        }

        echo printJSON(array('error' => $error, 'summ' => $summ + $this->getUser('bonus')));
        exit;
    }

    function actAddAddr() {
        global $post;
        $data['addr_data'] = $post->getArray('addr_data');
        $data['address'] = $post->get('address');
        if ($post->get('address')) {
            $data['addr_data'][] = $post->get('address');
        }
        echo $this->render($data, dirname(__FILE__) . "/cabinet_edit_addr.tpl.php");
    }

    function actSubscribe() {
        global $ST, $post;

        if ($post->isMail('mail')) {
            $type = array("news");

            if ($t = $post->get('type')) {
                $type = preg_split('/[,\s]/', $t);
            }

            //Добавим в подписку
            $rs = $ST->select("SELECT * FROM sc_subscribe WHERE mail='" . SQL::slashes($post->get('mail')) . "'");
            if (!$rs->next()) {
                $ST->insert('sc_subscribe', array('mail' => $post->get('mail'), 'type' => implode(' ', $type)));
            } else {
                $t = array();
                if (trim($rs->get('type'))) {
                    $t = explode(' ', trim($rs->get('type')));
                }
                if ($t == array_unique(array_merge($t, $type))) {
                    echo printJSON(array('msg' => 'Уже подписаны'));
                    exit;
                }
                $t = array_unique(array_merge($t, $type));
                $ST->update('sc_subscribe', array('type' => implode(' ', $t)), "mail='" . SQL::slashes($post->get('mail')) . "'");
            }
            echo printJSON(array('msg' => BaseComponent::getText('subscribe_confirm')));
            exit;
        } else {
            echo printJSON(array('err' => 'Введите адрес корректно!'));
            exit;
        }
    }

    function actUnsubscribe() {
        global $get, $ST;
        if ($get->get('key') == md5($get->get('mail') . $get->get('type') . 'unsubscribe')) {
            $rs = $ST->select("SELECT * FROM sc_subscribe WHERE mail='" . SQL::slashes($get->get('mail')) . "'");
            if ($rs->next()) {
                $t = array();
                if (trim($rs->get('type'))) {
                    $t = explode(' ', trim($rs->get('type')));
                    $t = array_diff($t, array($get->get('type')));
                }
                $ST->update('sc_subscribe', array('type' => implode(' ', $t)), "mail='" . SQL::slashes($get->get('mail')) . "'");
            }
        }
        $this->setPageTitle('Отписаться');
        $this->display(array(), dirname(__FILE__) . '/cabinet_unsubscribe.tpl.php');
    }

    function actReflink() {
        global $post;
        $data=array(
            
        );
        $this->display($data, dirname(__FILE__) . "/reflink.tpl.php");
    }
}

?>