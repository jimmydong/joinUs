<?php
/**
 * 谷歌地图应用
 */
class GoogleMap{
	// Key
	public static $key = 'AIzaSyB3RdeQiBbKi7XTgvH4keXEBh975YurTtA';
	// 默认的直线距离放大倍数
	public static $distance_modify_val = 1.2;
	// 缓冲
	private static $buffer = array();
	// 语言
	public static $language = 'zh-CN'; //en
	
	public static function demo() {
		//地址=>经纬度
		//var_dump2(self::getLocation(2, '中国北京市北京海淀区北太平庄黄寺大街西口'));
		
		//经纬度=>地址
		//var_dump2(self::toLocation(116.366975000000, 39.852133000000));
		
		//计算距离
		$from = array('lng'=>116.366975000000, 'lat'=>39.852133000000);
		$to = array('lng'=>116.432594299316, 'lat'=>39.873054504395);
		//$from = array( 'lat' => 45.8206168 , 'lng' => -119.7701635);
		//$to = array('lat' => 45.8323334, 'lng' => -119.7026555);
		//var_dump2(self::route($from,$to));
		
		//地址补全
		//var_dump2(self::searchLocation('北京', '黄寺大'));
	}
	/**
	 * 根据地点名称查询经纬度
	 * @param mixed $city   - id 或 name 都可以
	 * @param string $location
	 * @return array['lat','lng'] | false
	 */
	public static function getLocation($city, $location){
		if(is_int($city)){
			$t = \model\City::getOne($city);
			$city = $t['name'];
		}
		$url = "https://maps.googleapis.com/maps/api/geocode/json?key=".self::$key."&language=".self::$language;
		$url = $url . "&address=" . urlencode(trim($location .' '. $city));
		$info = self::_getUrl($url);
		$info = json_decode($info, true);
		if ($info['status'] == 'OK') {
			return $info['results'][0]['geometry']['location'];
		} else {
			return false;
		}
	}

	/**
	 * 计算路径时间 【注意：如果超时，则按照直线距离按照默认速度计算】
	 * mode: driving , walking, bicycling, transit
	 * transit_mode: bus, subway, train, rail  可以用 | 连接多种方式
	 * @param string $from  array('lng'=$lng, 'lat'=>lat)
	 * @param string $to
	 * @param string $mode  driving,walking,transit
	 */
	public static function route($from, $to, $mode='transit'){
		$default_speed = 5;	//如果无法获取，则按照步行 每小时 5 公里

		$city = urlencode($city);
		if(is_array($from))$origin = urlencode($from['lat'] . ',' . $from['lng']);
		else $origin = urlencode($from);
		if(is_array($to))$destination = urlencode($to['lat'] . ',' . $to['lng']);
		else $destination = urlencode($to);
		$url = "https://maps.googleapis.com/maps/api/directions/json?key=".self::$key."&language=".self::$language;
		$url .= "&origin=".$origin."&destination=".$destination;
		$info = file_get_contents($url);
		if($info){
			$info = json_decode($info, true);
			if ($info['status'] == 'OK'){
				if($t = $info['routes'][0]['legs'][0]){
					$re['time'] = round($t['duration']['value'] / 60);
					$re['distance'] = $t['distance']['value'];
					$re['fromGoogle'] = true;
					return $re;
				}
			}
		}

		$info['distance'] = self::getDistance($from['lng'], $from['lat'], $to['lng'], $to['lat']);
		$info['time'] = round($info['distance'] /1000 / $default_speed * 60);
		$info['fromGoogle'] = false;
		return $info;
	}

	/**
	 * 计算两点之间的距离
	 * @param float $lng1 维度1
	 * @param float $lat1 经度1
	 * @param float $lng2 维度2
	 * @param float $lat2 经度2
	 * @return number 米
	 */
	public static function getDistance($lng1, $lat1, $lng2, $lat2)
	{
		$key = "GoogleMap_Distance_{$lng1}_{$lat1}_{$lng2}_{$lat2}";
		if(!$refresh){
			$re = self::$buffer[$key];
			if($re){
				return $re;
			}
		}

		//将角度转为狐度
		$radLat1=deg2rad($lat1);
		$radLat2=deg2rad($lat2);
		$radLng1=deg2rad($lng1);
		$radLng2=deg2rad($lng2);
		$a=$radLat1-$radLat2;//两纬度之差,纬度<90
		$b=$radLng1-$radLng2;//两经度之差纬度<180
		$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378137;
		$re = round($s,2);
		// 直线距离放大一些
		$re *= self::$distance_modify_val;

		self::$buffer[$key] = $re;
		return $re;
	}


    /**
     * 根据经纬度获取附近地址
     * Array
     (
     [addr] => 华腾园6号楼101室
     [cp] => NavInfo
     [direction] => 东南
     [distance] => 102
     [name] => 华海经纪
     [poiType] => 教育培训
     [point] => Array
     (
     [x] => 116.47149867379
     [y] => 39.892467337
     )

     [tel] =>
     [uid] => bcd104904a45c784704b8732
     [zip] =>
     )
     * @param float $lng
     * @param float $lat
     */
    public static function getAroundLocation($lng, $lat) {
    	$ak = self::$aklist[rand(0, count(self::$aklist)-1)];
    	$url = "http://api.map.baidu.com/geocoder/v2/?ak=".$ak."&location={$lat},{$lng}&output=json&pois=1";
    	$info = file_get_contents($url);
    	$info = json_decode($info, true);
    	if ($info['status'] == 0) {
    		return $info['result']['pois'];
    	} else {
    		return false;
    	}
    }
    
}