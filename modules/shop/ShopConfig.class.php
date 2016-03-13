<?

class ShopConfig extends ModelConfig {

    function fields() {
        return array(
            'YML' => array(
                'name' => 'YML',
                'group' => array(
                    'SHOP_YML_NAME' => array('name' => '�������� �������� YML'),
                    'SHOP_YML_COMPANY' => array('name' => '�������� YML'),
                    'SHOP_YML_PICKUP' => array('name' => '��������� YML'),
                )
            ),
            'SHOP_R' => array(
                'name' => '���������',
                'group' => array(
                    'SHOP_R_FIRM_NAME' => array('name' =>'�����'),
                    'SHOP_R_FIRM_INN' => array('name' => '���'),
                    'SHOP_R_FIRM_ACCOUNT' => array('name' => '�/�'),
                    'SHOP_R_FIRM_BANK_BIK' => array('name' => '��� �����'),
                    'SHOP_R_FIRM_BANK' => array('name' => '����'),
                    'SHOP_R_FIRM_CORR_ACCOUNT' => array('name' => '��� ��'),
                ),
            ),
            'SHOP' => array(
                'name' => '�������� ���������',
                'group' => array(
                    'SHOP_SCALE_IMAGE' => array('name' => '����������� �������� ��� ���������','type'=>  self::TYPE_BOOL),
                    
                    'SHOP_SHOW_GOODS' => array('name' => '���������� ������ � ����� ��������','type'=>  self::TYPE_BOOL),
                    'SHOP_REFERAL_ORDER' => array('name' => '���������� ���������','type'=>  self::TYPE_BOOL),
                    'SHOP_DELIVERY_ENABLED' => array('name' => '��������','type'=>  self::TYPE_BOOL),
                    'SHOP_CHECK_DELIVERY_TIME' => array('name' => '�������� ������� ��������','type'=>  self::TYPE_BOOL),
                    'SHOP_ORDER_COND' => array('name' => '������� ��������'),
                ),
            ),
        );
    }

}
