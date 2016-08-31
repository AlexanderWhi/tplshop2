<?php

class Img {

    static $stamp = false;

    const ST = 'st';

    static function scaleImg($img, $params) {
        if (self::$stamp) {
            $params.=self::ST;
        }
        return scaleImg($img, $params);
    }

    static function scale($img, $params) {
        return scaleImg($img, $params);
    }

    static function scaleByFormat($img, $format = 0, $formatParams = array()) {
        if (!empty($formatParams[$format])) {
            return self::scaleImg($img, $formatParams[$format]);
        } elseif (!empty($formatParams[0])) {
            return self::scaleImg($img, $formatParams[0]);
        }
        return $img;
    }

    static function scaleBySize($img, $format = 0, $size = array(100, 100)) {
        $min = min($size[0], $size[1]);
        $max = max($size[0], $size[1]);
        $formatParams = array(
            'w' . $size[0],
            'h' . $size[1],
            'w' . $size[0] . 'h' . $size[1],
            'sq' . $max,
        );
        return self::scaleByFormat($img, $format, $formatParams);
    }

    static function getFormatList() {
        return array(
            0 => 'Выровнять по ширине',
            1 => 'Выровнять по высоте',
            2 => 'Вписать',
            3 => 'Описать',
        );
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

