<?

class GeoIp {

    const URL = "http://ipgeobase.ru:7020/geo";

    static function getCity($ip = null) {
        if($ip===null){
            if($c =  self::getClientCity()){
                return $c;
            }
        }
        $data = self::get($ip);
        $c=isset($data['city']) ? $data['city'] : '';
        Cookie::set('__CLIENT_CITY', $c);
        return $c;
    }
    static function getClientCity(){
        $city='';
        if($c=  Cookie::get('__CLIENT_CITY')){
            $city=$c;
        }
        return $city;
    }

    static function get($ip = '') {
        if (!$ip) {
            if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
                $ip = $_SERVER['HTTP_X_REAL_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        if (preg_match('/^(10|192)\./', $ip)) {//Забить на лок адреса
            return array();
        }
        if ($ip == '127.0.0.1') {
            $ip = '87.224.214.72';
        }
        $url = GeoIp::URL;
        $ch = curl_init();

//		if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
//			curl_setopt($ch, CURLOPT_PROXY, "10.61.38.132:8080");
//			curl_setopt($ch, CURLOPT_PROXYUSERPWD, "EKT\\leonov-aa:привет");
//		}

        curl_setopt($ch, CURLOPT_URL, $url . "?ip=$ip");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        $result = curl_exec($ch);

        curl_close($ch);

        $data = array();
        if (preg_match_all('|<([a-z]+)>(.+)</\1>|', $result, $res)) {
            foreach ($res[1] as $n => $v) {
                $data[$v] = $res[2][$n];
            }
        }
        return $data;
    }

    static function getYMap($address) {
        $address = urlencode($address);
        $url = "http://geocode-maps.yandex.ru/1.x/?geocode=$address&format=json";
        $ch = curl_init();

        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
//			curl_setopt($ch, CURLOPT_PROXY, "10.61.38.132:8080");
//			curl_setopt($ch, CURLOPT_PROXYUSERPWD, "EKT\\leonov-aa:ghbdtn");
        }
        curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_COOKIE, 'yandexuid=577515471327989215; fuid01=4f2781df037137fb.SG3i8CLM5fEPIgUymtklRKaqPZv-VnoaIcqjn3KMH_6luOK0j56tJm_6BYrHBRtMEB3nbcO0E-PDiejePO8NoNQsyAZdvtT_mu4hjT7yOq7-oahzn8qWSVBL1itn7Mva; yp=1336206623.ygu.1; yabs-frequency=/4/1G020EOG3L2kiWDG/6Pm3JfmEGzak0r2F3Zm00046tmKQHmvS0002/');
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $result = json_decode($result);

        $result = @$result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        if ($result) {
            $result = str_replace(' ', ',', $result);
        }
        $url = "http://static-maps.yandex.ru/1.x/?ll=$result&size=450,450&z=13&l=map&pt=$result,pm2rdm";
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return chunk_split(base64_encode($result));
    }

    static function getYMap2($address) {
        $address = urlencode($address);
        $url = "http://geocode-maps.yandex.ru/1.x/?geocode=$address&format=json";
        $ch = curl_init();

        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            curl_setopt($ch, CURLOPT_PROXY, "10.61.38.132:8080");
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "EKT\\leonov-aa:ghbdtn");
        }
        curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_COOKIE, 'yandexuid=577515471327989215; fuid01=4f2781df037137fb.SG3i8CLM5fEPIgUymtklRKaqPZv-VnoaIcqjn3KMH_6luOK0j56tJm_6BYrHBRtMEB3nbcO0E-PDiejePO8NoNQsyAZdvtT_mu4hjT7yOq7-oahzn8qWSVBL1itn7Mva; yp=1336206623.ygu.1; yabs-frequency=/4/1G020EOG3L2kiWDG/6Pm3JfmEGzak0r2F3Zm00046tmKQHmvS0002/');
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $result = json_decode($result);

        $result = @$result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        if ($result) {
            $result = str_replace(' ', ',', $result);
        }
        $url = "http://static-maps.yandex.ru/1.x/?ll=$result&size=351,341&z=13&l=map&pt=$result,pm2rdm";
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    static function getYMapPos($address) {
        $address = urlencode($address);
        $url = "http://geocode-maps.yandex.ru/1.x/?geocode=$address&format=json";
        $ch = curl_init();

        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            curl_setopt($ch, CURLOPT_PROXY, "10.61.38.132:8080");
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "EKT\\leonov-aa:ghbdtn");
        }
        curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_COOKIE, 'yandexuid=577515471327989215; fuid01=4f2781df037137fb.SG3i8CLM5fEPIgUymtklRKaqPZv-VnoaIcqjn3KMH_6luOK0j56tJm_6BYrHBRtMEB3nbcO0E-PDiejePO8NoNQsyAZdvtT_mu4hjT7yOq7-oahzn8qWSVBL1itn7Mva; yp=1336206623.ygu.1; yabs-frequency=/4/1G020EOG3L2kiWDG/6Pm3JfmEGzak0r2F3Zm00046tmKQHmvS0002/');
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $result = json_decode($result);

        $result = @$result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        if ($result) {
            $result = str_replace(' ', ',', $result);
        }

        curl_close($ch);
        return $result;
    }

}

?>