<?php

/**
 * https://developers.google.com/speed/pagespeed/insights/
 */
class ClosureCompile {

    static function compile($js_file_path, $enc = 'utf-8') {
        //return $js_file_path;
        $cache_path = 'cache/closure_compile';
        $cache_file = $cache_path . '/' . str_replace('/', '_', $js_file_path);
        $cache_file = preg_replace('/\.js$/', '.min.js', $cache_file);
        if (empty($_GET['compile_js'])) {
            //return $cache_file;
        }


        if (!file_exists($cache_path)) {
            mkdir($cache_path, 0777, true);
        }

        if (!file_exists($cache_file) || filemtime($js_file_path) > filemtime($cache_file)) {
            if ($data = self::getData(file_get_contents($js_file_path), $enc)) {
                $data = preg_replace('|/\*.*\*/|U', '', $data);
                file_put_contents($cache_file, $data);
            } else {
                return $js_file_path;
            }
        }
        return $cache_file;
    }

    static function getData($inp_js_str, $enc = 'utf-8') {
        if ($enc !== 'utf-8') {
            $inp_js_str = iconv($enc, 'utf-8', $inp_js_str);
        }
        $data = http_build_query(array(
            'js_code' => $inp_js_str,
            'compilation_level' => 'WHITESPACE_ONLY',
            'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
            //'compilation_level' => 'ADVANCED_OPTIMIZATIONS',
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

    static function isSpeedInsights() {
        return stripos(@$_SERVER['HTTP_USER_AGENT'], 'Speed Insights') !== false;
    }

    static function css2min($css = array(), $path = null) {
        $max_m_time = 0;
        $cache_file = '';
        foreach ($css as $style) {
            $max_m_time = max($max_m_time, filemtime($style));
            $name = preg_replace('|.*([^/]*)\.css$|Ui', '\1', $style);
            $cache_file .= $name;
            if ($path === null) {
                $path = dirname($style);
            }
        }
        $cache_file .= '.min.css';
        $cache_file = $path . '/' . $cache_file;
        if (!file_exists($cache_file) || $max_m_time > filemtime($cache_file)) {
            file_put_contents($cache_file, '');
            foreach ($css as $style) {
                file_put_contents($cache_file, self::css2minStr(file_get_contents($style)), FILE_APPEND);
            }
        }
        return $cache_file;
    }

    static function css2minStr($str) {
        $str = preg_replace('/[\n\r]/', '', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = preg_replace('/([:;\{\}])\s+/', '\1', $str);
        $str = preg_replace('|/\*.*\*/|U', '', $str);
        return $str;
    }

    static function html2minStr($str) {
        //$str = preg_replace('|//.*\n|U', '', $str);
        $str = preg_replace('/[\r]/', '', $str);
        //$str = preg_replace('/[\n\r]/', '', $str);
        $str = preg_replace('/[ \t]+/', ' ', $str);
        $str = preg_replace('/\n /', "\n", $str);
        $str = preg_replace('/\n+/', "\n", $str);
        $str = preg_replace('/<!--.*-->/U', '', $str);
        //$str= preg_replace('/([:;\{\}])\s+/', '\1', $str);
        return $str;
    }

    static function searchFile($dir, $name) {
        $d = opendir($dir);
        $dir_find = array();
        while ($f = readdir($d)) {
            if ($f != '.' && $f != '..' && is_dir($dir . '/' . $f)) {
                $dir_find = array_merge($dir_find, self::searchFile($dir . '/' . $f, $name));
            }
            if ($f == $name) {
                $dir_find[] = $dir;
            }
        }
        return $dir_find;
    }

    static function imgReplace($from, $to) {
        if (file_exists($from)) {
            $d = opendir($from);
            while ($f = readdir($d)) {
                if (isImg($f)) {
                    $dir_find = self::searchFile($to, $f);
                    if ($dir_find) {
                        foreach ($dir_find as $path) {
                            if (file_exists($from . '/' . $f)) {
                                if (md5_file($from . '/' . $f) !== md5_file($path . '/' . $f)) {
                                    rename($path . '/' . $f, $path . '/' . $f . '_' . time());
                                    rename($from . '/' . $f, $path . '/' . $f);
                                    echo $path . '/' . $f . "\n ";
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}
