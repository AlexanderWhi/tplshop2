<?php

include_once 'core/component/AdminComponent.class.php';

class Config extends AdminComponent {

    protected $mod_name = 'Конфигурация';
    protected $mod_title = 'Конфигурация';

    function getListData() {
        global $ST, $CONFIG;
        $data = array(
            'rs' => $ST->select("SELECT * FROM sc_config ORDER BY name")->toArray(),
        );
        $rs = $ST->select("SELECT * FROM sc_enum WHERE LOWER(field_name) LIKE 'cfg_%'");
        while ($rs->next()) {
            $field_name = preg_replace('/^cfg_/i', '', $rs->get('field_name'));
            $data['enum'][$field_name][$rs->get('field_value')] = $rs->get('value_desc');
        }
        return $data;
    }

    function actDefault() {
        global $ST, $CONFIG, $post;

        $data = $this->getListData();

        if ($this->isSu()) {
            $this->display($data, dirname(__FILE__) . '/config_su.tpl.php');
        } else {
            $this->display($data, dirname(__FILE__) . '/config.tpl.php');
        }
    }

    function actSave() {
        global $ST, $CONFIG, $post;
        foreach ($CONFIG as $k => $v) {
            if ($post->exists(strtolower($k))) {
                $ST->update('sc_config', array('value' => $post->get(strtolower($k))), "LOWER(name)=LOWER('" . $k . "')");
            }
        }
        echo printJSON(array('msg' => 'Данные обновлены'));
        exit;
    }

    function actAdd() {
        global $ST, $post;

        $d = array(
            'name' => $post->get('name'),
            'value' => $post->get('value'),
            'description' => $post->get('description'),
        );
        $rs = $ST->select("SELECT * FROM sc_config WHERE name='" . SQL::slashes($d['name']) . "'");
        if (!$rs->next()) {
            $ST->insert('sc_config', $d);
            $out['msg'] = 'добавлено';
            $out['html'] = $this->rndList();
        } else {
            $out['err'] = $d['name'] . ' уже существует';
        }

        exit(printJSON($out));
    }

    function rndList() {
        $d = $this->getListData();
        return $this->render($d, dirname(__FILE__) . '/config_list.tpl.php');
    }

    function actRemove() {
        global $ST, $get, $post;
        if ($get->get('name')) {
            $ST->delete('sc_config', "name='" . SQL::slashes($get->get('name')) . "'");
        }
        if ($item = $post->getArray('item')) {
            foreach ($item as $n) {
                $ST->delete('sc_config', "name='" . SQL::slashes($n) . "'");
            }
        }
        echo $this->rndList();
    }

    function actFront(){
        $data=array(
            'logo'=>$this->cfg('LOGO_PATH'),
            'favicon'=>$this->cfg('FAVICON_PATH'),
            'no_img'=>$this->cfg('NO_IMG'),
        );
        
        $this->display($data, dirname(__FILE__) . '/config_front.tpl.php');
    }
    function actFrontSave(){
        global $post;
        
        $path=dirname($this->cfg('NO_IMG'));
        if($_FILES['favicon']['tmp_name']){
            $fname=$path.'/favicon_'.time()."_".file_ext($_FILES['favicon']['name']);
            if(move_uploaded_file($_FILES['favicon']['tmp_name'], ROOT.$fname)){
                $this->saveCfg('FAVICON_PATH', $fname,'Фавикон');
            }
        }
        if($post->get('clear_favicon')){
            $this->saveCfg('FAVICON_PATH', '');
        }
        if($_FILES['logo']['tmp_name']){
            $fname=$path.'/logo_'.time()."_".file_ext($_FILES['logo']['name']);
            if(move_uploaded_file($_FILES['logo']['tmp_name'], ROOT.$fname)){
                $this->saveCfg('LOGO_PATH', $fname,'Лого');
            }
        }
        if($post->get('clear_logo')){
            $this->saveCfg('LOGO_PATH', '');
        }
        if($_FILES['no_img']['tmp_name']){
            $fname=$path.'/no_img_'.time()."_".file_ext($_FILES['no_img']['name']);
            if(move_uploaded_file($_FILES['no_img']['tmp_name'], ROOT.$fname)){
                $this->saveCfg('NO_IMG', $fname,'Лого');
            }
        }
        if($post->get('clear_logo')){
            $this->saveCfg('NO_IMG', null);
        }
        
        echo printJSON(array('msg' => 'Данные обновлены'));
        exit;
    }
    function saveCfg($name,$val,$desc=null){
        
        if($val===null){
            DB::delete('sc_config',"name='$name'");
            return;
        }
        
        $rs=DB::select("SELECT * FROM sc_config WHERE name='$name'");
        $d=array('value'=>$val);
        if($desc!==null){
            $d['description']=$desc;
        }
        if($rs->next()){
            DB::update('sc_config', $d,"name='$name'");
        }else{
            $d['name']=$name;
            DB::insert('sc_config', $d);
        }
    }
}