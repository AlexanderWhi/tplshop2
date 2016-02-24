<?

class LibShop {

    private static $instance;

    /**
     * @return LibShop
     */
    static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return LibShop
     */
    static function getPrototype() {
        return new self;
    }

    /**
     * Списать товары с остатков
     * @param int $order_id
     */
    static function debitOrderItems($order_id) {
        $rs = DB::select("SELECT * FROM sc_shop_order_item WHERE orderid=$order_id");
        while ($rs->next()) {
            $rs2 = DB::select("SELECT * FROM sc_shop_item WHERE id={$rs->getInt('itemid')}");
            if ($rs2->next()) {
                $in_stock = $rs2->get('in_stock') - $rs->get('count');
                if ($in_stock >= 0) {
                    DB::update('sc_shop_item', array('in_stock' => $in_stock), "id={$rs->getInt('itemid')}");
                }
            }
        }
    }

    static function refundOrderItems($order_id) {
        $rs = DB::select("SELECT * FROM sc_shop_order_item WHERE order_id=$order_id");
        while ($rs->next()) {
            $rs2 = DB::select("SELECT * FROM sc_shop_item WHERE id={$rs->getInt('item_id')}");
            if ($rs2->next()) {
                $in_stock = $rs2->get('in_stock') + $rs->get('count');
                if ($in_stock >= 0) {
                    DB::update('sc_shop_item', array('in_stock' => $in_stock), "id={$rs->getInt('item_id')}");
                }
            }
        }
    }

    static function changeOrderStatus($order_id, $order_status = null, $pay_status = null) {
        $rs = DB::select("SELECT * FROM sc_shop_order WHERE id=$order_id");
        if ($rs->next()) {
            if ($order_status != $rs->get('order_status')) {
                if ($pay_status == 1) {//Оплачен
                    self::debitOrderItems($order_id);
                }
                if ($order_status == 4) {
                    self::refundOrderItems($order_id);
                }
            }
            DB::update('sc_shop_order', array('order_status' => $order_status, 'pay_status' => $pay_status), "id=$order_id");
        }
    }

    static function getOrderStatusList($order_status = null) {
        $order_status_list = array(
            0 => 'Новый заказ',
            1 => 'Оформленный заказ',
            2 => 'Доставлен',
            3 => 'НЕ доставлен',
            4 => 'Возврат',
        );
        if ($order_status !== null) {
            return $order_status_list[$order_status];
        }
        return $order_status_list;
    }

    static function getPayStatusList($pay_status = null) {
        $pay_status_list = array(
            0 => 'НЕ оплачен',
            1 => 'Оплачен',
        );
        if ($pay_status !== null) {
            return $pay_status_list[$pay_status];
        }
        return $pay_status_list;
    }

    static function addOrder($data,$basket) {
        $id = DB::insert('sc_shop_order', $data);
        foreach ($basket as $item) {
            $d = array(
                'orderid' => $id,
                'itemid' => $item['id'],
                'count' => $item['count'],
                'price' => $item['price'],
//						'unit_sale'=>$item['unit_sale'],
            );
            if (!empty($item['nmnid'])) {
                $d['nmn'] = $item['nmnid'];
            }
            if (!empty($item['item_comment'])) {
                $d['comment'] = $item['item_comment'];
            }
            DB::insert('sc_shop_order_item', $d);
            
        }
        //Спишем товары со склада
        self::debitOrderItems($id);
        return $id;
    }

}
