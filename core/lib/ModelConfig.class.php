<?php

function __fill_config(&$item, $key, $data) {
    if (isset($data[$key])) {
        $item['value'] = $data[$key];
    }
}

class ModelConfig {

    const TYPE_BOOL=2;
    const TYPE_STR=1;
    const TYPE_NUM=3;
    const TYPE_ENUM=4;
    function fields() {
        return array();
    }

    function getName() {
        return 'default';
    }

    private static function _getConfig(&$grp, $data) {
        foreach ($grp as $k=>&$node) {
            if (isset($data[$k])) {
                $node['value'] = $data[$k];
            }
            if(isset($node['group'])){
                self::_getConfig($node['group'],$data);
            }
        }
    }

    function getConfig() {
        $data = $this->fields();
        self::_getConfig($data, $this->data);
        return $data;
    }

    function load($data = null) {
        if ($data === null) {
            $rs = DB::select("SELECT * FROM sc_config");
            while ($rs->next()) {
                $this->data[$rs->get('name')] = $rs->get('value');
            }
        } else {
            $this->data = $data;
        }
    }

    public $data = array();

    function save($data) {
        foreach ($data as $k => $v) {
            $rs = DB::select("SELECT * FROM sc_config WHERE name='{$k}'");
            if ($rs->next()) {
                DB::update('sc_config', array('value' => $v), "name='{$k}'");
            } else {
                DB::insert('sc_config', array('value' => $v, 'name' => $k));
            }
        }
    }

}
