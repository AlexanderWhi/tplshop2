<?

class CatalogConfig extends ModelConfig {

    function fields() {
        return array(
            'GOODS_FIELD' => array(
                'name' => '�����',
                'group' => array(
                    'GOODS_FIELD_EXT_ID' => array('name' => '������� ID','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_PRODUCT' => array('name' => '�������','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_MANUFACTURER_ID' => array('name' => '�������������','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_CATALOGS' => array('name' => '��������','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_WEIGHT_FLG' => array('name' => '������� �����','type'=>  self::TYPE_BOOL),
                    'GOODS_FIELD_AWARDS' => array('name' => '��������� ��������������','type'=>  self::TYPE_BOOL),
                )
            ),
//            'SHOP_R' => array(
//                'name' => '���������',
//                'group' => array(
//                    'SHOP_R_FIRM_NAME' => array('name' =>'�����'),
//                    'SHOP_R_FIRM_INN' => array('name' => '���'),
//                    'SHOP_R_FIRM_ACCOUNT' => array('name' => '�/�'),
//                    'SHOP_R_FIRM_BANK_BIK' => array('name' => '��� �����'),
//                    'SHOP_R_FIRM_BANK' => array('name' => '����'),
//                    'SHOP_R_FIRM_CORR_ACCOUNT' => array('name' => '��� ��'),
//                ),
//            ),
//            'SHOP' => array(
//                'name' => '�������� ���������',
//                'group' => array(
//                    'SHOP_SCALE_IMAGE' => array('name' => '����������� �������� ��� ���������'),
//                    'SHOP_CHECK_DELIVERY_TIME' => array('name' => '�������� ������� ��������'),
//                    'SHOP_SHOW_GOODS' => array('name' => '���������� ������ � ����� ��������'),
//                    'SHOP_REFERAL_ORDER' => array('name' => '���������� ���������'),
//                ),
//            ),
        );
    }

}
