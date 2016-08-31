<?

class CatalogConfig extends ModelConfig {

    function fields() {
        return array(
            'GOODS_FIELD' => array(
                'name' => 'Товар',
                'group' => array(
                    'GOODS_FIELD_EXT_ID' => array('name' => 'Внешний ID','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_PRODUCT' => array('name' => 'Артикул','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_MANUFACTURER_ID' => array('name' => 'Производитель','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_CATALOGS' => array('name' => 'Каталоги','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_WEIGHT_FLG' => array('name' => 'Весовой товар','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_AWARDS' => array('name' => 'Агентское вознаграждение','type'=>  self::TYPE_BOOL),
                    'GOODS_ACTION' => array('name' => 'Акции','type'=>  self::TYPE_BOOL),
                    'GOODS_COMMENT_ENABLED' => array('name' => 'Включить коментарии к товарам','type'=>  self::TYPE_BOOL),
                    'GOODS_PRICE_FORMAT' => array('name' => 'Формат цены','type'=>  self::TYPE_ENUM,'enum'=>array(1=>'123,00 123,21',2=>'123 123,21',)),
                )
            ),
//            'SHOP_R' => array(
//                'name' => 'Реквизиты',
//                'group' => array(
//                    'SHOP_R_FIRM_NAME' => array('name' =>'Фирма'),
//                    'SHOP_R_FIRM_INN' => array('name' => 'Инн'),
//                    'SHOP_R_FIRM_ACCOUNT' => array('name' => 'Р/с'),
//                    'SHOP_R_FIRM_BANK_BIK' => array('name' => 'Бик банка'),
//                    'SHOP_R_FIRM_BANK' => array('name' => 'Банк'),
//                    'SHOP_R_FIRM_CORR_ACCOUNT' => array('name' => 'Кор сч'),
//                ),
//            ),
//            'SHOP' => array(
//                'name' => 'Основные настройки',
//                'group' => array(
//                    'SHOP_SCALE_IMAGE' => array('name' => 'Увеличивать картинку при наведении'),
//                    'SHOP_CHECK_DELIVERY_TIME' => array('name' => 'Проверка времени доставки'),
//                    'SHOP_SHOW_GOODS' => array('name' => 'Показывать товары в корне каталога'),
//                    'SHOP_REFERAL_ORDER' => array('name' => 'Партнёрская программа'),
//                ),
//            ),
        );
    }

}
