<?

class Rumor {

    static private $instance;

    static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    function __construct() {
        require_once(ROOT . '/shared/php/rumor_2.0_build_02/rumor.php');
        prep_dict_MEM(ROOT . '/shared/php/rumor_2.0_build_02');
    }

    static function getBaseForm($word) {
        return self::getInstance()->_getBaseForm($word);
    }

    function _getBaseForm($word) {
        return get_base_form_MEM($word);
    }

    static function getAllForms($word) {
        return self::getInstance()->_getAllForms($word);
    }

    function _getAllForms($word) {
        return get_all_forms_MEM($word);
    }

}

?>