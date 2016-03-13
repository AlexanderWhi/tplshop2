<?

class ShopConfig extends ModelConfig {

    function fields() {
        return array(
            'YML' => array(
                'name' => 'YML',
                'group' => array(
                    'SHOP_YML_NAME' => array('name' => 'Название магазина YML'),
                    'SHOP_YML_COMPANY' => array('name' => 'Компания YML'),
                    'SHOP_YML_PICKUP' => array('name' => 'Самовывоз YML'),
                )
            ),
            'SHOP_R' => array(
                'name' => 'Реквизиты',
                'group' => array(
                    'SHOP_R_FIRM_NAME' => array('name' =>'Фирма'),
                    'SHOP_R_FIRM_INN' => array('name' => 'Инн'),
                    'SHOP_R_FIRM_ACCOUNT' => array('name' => 'Р/с'),
                    'SHOP_R_FIRM_BANK_BIK' => array('name' => 'Бик банка'),
                    'SHOP_R_FIRM_BANK' => array('name' => 'Банк'),
                    'SHOP_R_FIRM_CORR_ACCOUNT' => array('name' => 'Кор сч'),
                ),
            ),
            'SHOP' => array(
                'name' => 'Основные настройки',
                'group' => array(
                    'SHOP_SCALE_IMAGE' => array('name' => 'Увеличивать картинку при наведении','type'=>  self::TYPE_BOOL),
                    
                    'SHOP_SHOW_GOODS' => array('name' => 'Показывать товары в корне каталога','type'=>  self::TYPE_BOOL),
                    'SHOP_REFERAL_ORDER' => array('name' => 'Партнёрская программа','type'=>  self::TYPE_BOOL),
                    'SHOP_DELIVERY_ENABLED' => array('name' => 'Доставка','type'=>  self::TYPE_BOOL),
                    'SHOP_CHECK_DELIVERY_TIME' => array('name' => 'Проверка времени доставки','type'=>  self::TYPE_BOOL),
                    'SHOP_ORDER_COND' => array('name' => 'Условие доставки'),
                ),
            ),
        );
    }

}
