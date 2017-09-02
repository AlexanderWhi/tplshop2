<?php

include_once("core/component/AdminListComponent.class.php");

class AdminPromote extends AdminListComponent {

    function actDefault() {
        $data = array(
            'rs' => LibPromote::getPromoteList(),
        );
        $this->display($data, dirname(__FILE__) . '/promote_list.tpl.php');
    }

    function actCreate() {
        global $post;
        LibPromote::generate($post->getint('n'), $post->getint('length'), $post->getfloat('discount'));
        echo printJSON(array("msg" => 'ok'));
        exit;
    }

    function actRemove() {
        global $get;
        LibPromote::remove($get->getint('id'));
        echo printJSON(array("msg" => 'ok'));
        exit;
    }

    function actRelate() {
        global $post;
        LibPromote::relate($post->getint('promote_id'), $post->getArray('item'));
        echo printJSON(array("msg" => 'ok'));
        exit;
    }

    function actImport() {
        global $get;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=Промо_' . $get->get('id') . '.csv');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $list= LibPromote::getCodeList($get->get('id'));
        echo 'code;used'."\n";
        foreach ($list as $row){
            echo "{$row['code']};{$row['used']}\n";
        }
        exit;
    }

}
