<?php

include_once 'core/component/AdminListComponent.class.php';

class Users extends AdminListComponent {

    protected $mod_name = 'Пользователи';
    protected $mod_title = 'Пользователи';
    protected $status = array('Неактивен', 'Активен');

    function actDefault() {
        parent::refresh();
        $cond = " WHERE hide=0";
        if ($this->getURIVal('usertype')) {
            $cond.=" AND type='" . SQL::slashes($this->getURIVal('usertype')) . "'";
            $this->explorer[] = array('name' => $this->getURIVal('usertype'));
        }

        $query = "SELECT count(*) AS c FROM sc_users" . $cond;
        $rs = DB::select($query);
        if ($rs->next()) {
            $this->page->all = $rs->getInt('c');
        }

        $order = 'ORDER BY ';
        $ord = $this->getURIVal('ord') != 'asc' ? 'asc' : 'desc';
        if (in_array($this->getURIVal('sort'), array('login', 'u_id', 'status', 'type', 'company', 'balance', 'discount', 'bonus', 'visit', 'last_visit', 'reg_date'))) {
            $order.=$this->getURIVal('sort') . ' ' . $ord;
        } elseif ($this->getURIVal('sort') == 'fio') {
            $order.='last_name ' . $ord . ',first_name ' . $ord . ',middle_name ' . $ord;
        } else {
            $order.='type,u_id';
        }
        $data = array();

        $data['usertype'] = $this->enum('u_type');
        $queryStr = "SELECT * FROM sc_users $cond $order LIMIT " . $this->page->getBegin() . "," . $this->page->per;
        $this->rs = DB::select($queryStr);

        $this->display($data, dirname(__FILE__) . '/users.tpl.php');
    }

    function actRemove() {
        global $get;
        DB::update('sc_users', array('hide' => 1), "u_id =" . $get->get("id"));
        header('Location: .');
        exit;
    }

    function actDelete() {
        global $get;
        DB::delete('sc_users', "u_id =" . $get->get("id"));
        header('Location: .');
        exit;
    }

    function actEdit() {
        global $get;
        $field = array(
            'u_id' => $get->getInt('id'),
            'login' => '',
            'status' => 1,
            'name' => '',
            'company' => '',
            'phone' => '',
            'city' => '',
            'address' => '',
            'mail' => '',
            'avat' => '',
            'balance' => 0,
            'discount' => 0,
//		'bonus'=>0,
            'type' => '',
        );
        if ($field['u_id']) {
            $rs = DB::select("SELECT " . join(',', array_keys($field)) . " FROM sc_users WHERE u_id=" . $field['u_id']);
            if ($rs->next()) {
                $field = $rs->getRow();
            }
        }
        $field['groups'] = $this->enum('u_type');
        $this->explorer[] = array('name' => 'Редактировать');
        $this->display($field, dirname(__FILE__) . '/users_edit.tpl.php');
    }

    function actSave() {
        global $post;
        $id = $post->getInt('u_id');
        $data = array(
            'login' => $post->get('login'),
            'status' => $post->get('status'),
            'name' => $post->get('name'),
            'company' => $post->get('company'),
            'phone' => $post->get('phone'),
            'city' => $post->get('city'),
            'address' => $post->get('address'),
            'mail' => $post->get('mail'),
            'avat' => $post->get('avat'),
            'balance' => $post->getFloat('balance'),
            'discount' => $post->getFloat('discount'),
            'hide' => 0,
//		'bonus'=>$post->getFloat('bonus'),
            'type' => $post->get('type'),
        );

        $password = $post->remove('password');
        $avat_path = $post->remove('avat_path');

        $msg = 'Сохранено';
        $img_out = "";
        if (!empty($_FILES['upload']['name']) && isImg($_FILES['upload']['name'])) {
            $img = $this->cfg('AVATAR_PATH') . '/' . md5($_FILES['upload']['tmp_name']) . "." . file_ext($_FILES['upload']['name']);
            move_uploaded_file($_FILES['upload']['tmp_name'], ROOT . $img);
            $data['avat'] = $img;
            $img_out = scaleImg($img, 'w200');
        }
        if ($post->getInt('clear')) {
            $data['avat'] = '';
        }
        $err = array();
        $rs = DB::select("SELECT * FROM sc_users WHERE login='" . SQL::slashes($post->get('login')) . "' AND u_id<>$id");
        if ($rs->next()) {
            $err['login'] = 'Пользователь существует';
        }

        if (!$err) {
            if ($id === 0) {
                $data[] = "password=MD5('" . trim($password) . "')"; {
                    $id = DB::insert('sc_users', $data, 'u_id');
                }
            } else {
                if (trim($password)) {
                    $data[] = "password=MD5('" . trim($password) . "')";
                }
                DB::update('sc_users', $data, 'u_id=' . $id);
            }
            echo printJSONP(array('msg' => $msg, 'u_id' => $id, 'img' => $img_out));
            exit;
        } else {
            echo printJSONP(array('err' => $err));
            exit;
        }
    }

    function actPassword() {
        $this->explorer[] = array('name' => 'Сменить пароль');
        $this->setTitle('Сменить пароль');
        $this->display(array(), dirname(__FILE__) . '/password.tpl.php');
    }

    function actPasschange() {
        global $post;
        DB::update("sc_users", array('password' => md5($post->get('password'))), "u_id={$this->getUserId()}");
        echo printJSON(array('msg' => 'Пароль принят'));
        exit;
    }

    function actReg() {

        $rs = DB::select("SELECT * FROM sc_subscribe WHERE type LIKE '%reg%'");
        $data = array(
            'rs' => $rs->toArray(),
        );
        $this->display($data, dirname(__FILE__) . '/users_reg.tpl.php');
    }

    function actSendReg() {
        global $post;
        $mails = preg_split('/[,\s\n]/m', $post->get('mails'));
        foreach ($mails as $mail) {
            if (check_mail($mail = trim($mail))) {
                $snd = false;
                $rs = DB::select("SELECT * FROM sc_subscribe WHERE mail='{$mail}'");
                if ($rs->next()) {
                    if (($type = explode(' ', $rs->get('type'))) && !in_array('reg', $type)) {
                        DB::update('sc_subscribe', array('type' => $rs->get('type') . ' reg'), "mail='{$mail}'");
                        $snd = true;
                    }
                } else {
                    DB::insert('sc_subscribe', array('type' => 'reg', 'mail' => $mail));
                    $snd = true;
                }
                if ($snd) {

                    if (!$discount = (int) $this->cfg('SHOP_DISCOUNT_DEFAULT')) {
                        $discount = 5;
                    }
                    if (!$key = $this->cfg('KEY')) {
                        $key = '123';
                    }

                    $crc = md5("$mail$discount$key");
                    $href = "http://" . $_SERVER['HTTP_HOST'] . "/signup/?mail=$mail&discount=$discount&crc=$crc";
                    $d = array('mail' => $mail,
                        'href' => $href
                    );
                    $this->sendTemplateMail($mail, 'send_reg', $d);
                }
            }
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    function actSubscribe() {
        $page = new Page($this->getPages());
        parent::refresh();

        $cond = " WHERE 1=1 ";

        $query = "SELECT count(*) AS c FROM sc_subscribe " . $cond;
        $rs = DB::select($query);
        if ($rs->next()) {
            $page->all = $rs->getInt('c');
        }

        $order = 'ORDER BY ';
        $ord = $this->getURIVal('ord') != 'asc' ? 'asc' : 'desc';
        if (in_array($this->getURIVal('sort'), array('login', 'mail'))) {
            $order.=$this->getURIVal('sort') . ' ' . $ord;
        } else {
            $order.=" mail";
        }
        $data = array();

        $queryStr = "SELECT s.*,u.login FROM sc_subscribe s
		LEFT JOIN sc_users u ON s.mail=u.mail
		
		$cond $order LIMIT " . $page->getBegin() . "," . $page->per;

        $data['rs'] = DB::select($queryStr);
        $data['pg'] = $page;
        $this->setPageTitle('Подписка');
        $this->display($data, dirname(__FILE__) . '/subscribe.tpl.php');
    }

    function actAddSubscribe() {
        global $post;
        $mails = preg_split('/[,\s\n]/m', $post->get('mails'));

        if ($subscribe = $post->getArray('subscribe')) {
            foreach ($mails as $mail) {
                if (check_mail($mail = trim($mail))) {
                    $rs = DB::select("SELECT * FROM sc_subscribe WHERE mail='{$mail}'");
                    if ($rs->next()) {
                        $type = explode(' ', $rs->get('type'));
                        $type = array_unique(array_merge($type, $subscribe));
                        DB::update('sc_subscribe', array('type' => implode(' ', $type)), "mail='{$mail}'");
                    } else {
                        DB::insert('sc_subscribe', array('type' => implode(' ', $subscribe), 'mail' => $mail));
                    }
                }
            }
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    function actUnsubscribe() {
        DB::delete("sc_subscribe", "mail='" . SQL::slashes($_GET['mail']) . "'");
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function actMailDownload() {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=mails.csv');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $rs = DB::select("SELECT name,mail FROM sc_users WHERE type='user' ORDER BY name");
        while ($rs->next()) {
            echo implode(';', $rs->getRow()) . "\n";
        }
    }
    function actSubscribeMailDownload() {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=mails.csv');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $rs = DB::select("SELECT s.*,u.login,u.name FROM sc_subscribe s
		LEFT JOIN sc_users u ON s.mail=u.mail");
        while ($rs->next()) {
            echo implode(';', $rs->getRow()) . "\n";
        }
    }

}

?>