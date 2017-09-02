<?php

class LibPromote {

    static function generate($n, $length, $discount) {
        $id = DB::insert('sc_shop_promote', array(
                    'create' => date('Y-m-d H:i:s'),
                    'discount' => $discount,
        ));
        for ($i = 0; $i < $n; $i++) {
            $code = rand(str_pad(1, $length, '0'), str_pad(9, $length, '9'));
            $rs = DB::select("SELECT id FROM sc_shop_promote_code WHERE code='$code'");
            if (!$rs->next()) {
                $d = array(
                    'promote_id' => $id,
                    'code' => $code,
                );
                DB::insert('sc_shop_promote_code', $d);
            }
        }
    }

    static function relate($id, $items) {
        foreach ($items as $i) {
            $rs = DB::delete('sc_shop_promote_rel', "promote_id=$id AND item_id=$i");
            DB::insert('sc_shop_promote_rel', array(
                'promote_id' => $id,
                'item_id' => $i,
            ));
        }
    }

    static function getPromoteList() {
        $rs = DB::select("SELECT p.*,
            (SELECT COUNT(*) FROM sc_shop_promote_rel WHERE promote_id=p.id) AS rel,
            (SELECT COUNT(*) FROM sc_shop_promote_code WHERE promote_id=p.id) AS code,
            (SELECT COUNT(*) FROM sc_shop_promote_code WHERE promote_id=p.id AND used IS NOT NULL) AS used
            FROM sc_shop_promote p");
        return $rs->toArray();
    }

    static function remove($id) {
        DB::delete('sc_shop_promote', "id=$id");
        DB::delete('sc_shop_promote_rel', "promote_id=$id");
        DB::delete('sc_shop_promote_code', "promote_id=$id");
    }

    static function getCodeList($promote_id) {
        $rs = DB::select("SELECT *
            FROM sc_shop_promote_code p WHERE promote_id=$promote_id");
        return $rs->toArray();
    }
    
    static function getPromote($code,$item_id){
        $rs=DB::select("SELECT * FROM sc_shop_promote_code pc, sc_shop_promote_rel pl,sc_shop_promote p 
            WHERE code='$code' AND p.id=pc.promote_id AND pl.item_id=$item_id AND pl.promote_id=p.id");
        if($rs->next()){
            return $rs->get('discount');
        }
        return 0;
    }

}
