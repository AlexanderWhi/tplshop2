<?php

class ClosureCompile {

    static function compile($js_file_path) {
        //return $js_file_path;
        $cache_path = 'cache/closure_compile';
        $cache_file = $cache_path . '/' . str_replace('/', '_', $js_file_path);
        if (empty($_GET['compile_js'])) {
            //return $cache_file;
        }


        if (!file_exists($cache_path)) {
            mkdir($cache_path, 0777, true);
        }

        if (!file_exists($cache_file) || filemtime($js_file_path) > filemtime($cache_file)) {
            if ($data = self::getData(file_get_contents($js_file_path))) {
                file_put_contents($cache_file, $data);
            } else {
                return $js_file_path;
            }
        }
        return $cache_file;
    }

    static function getData($inp_js_str) {
        $data = http_build_query(array(
            'js_code' => iconv('cp1251','utf-8',$inp_js_str),
            'compilation_level' => 'WHITESPACE_ONLY',
            'output_format' => 'text',
            'output_info' => 'compiled_code',
                )
        );
        $ch = curl_init('http://closure-compiler.appspot.com/compile');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_error($ch);
        return $res;
    }
    
   static function isSpeedInsights(){
       return stripos(@$_SERVER['HTTP_USER_AGENT'], 'Speed Insights') !== false;
   }
}
