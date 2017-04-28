<?php
/**
 * Created by PhpStorm.
 * User: a8042
 * Date: 2017/4/27
 * Time: 14:12
 */

namespace App\Acme;
class GPS
{
 private $PI = 3.14159265358979324;
 private $x_pi = 0;

    public function __construct()
    {
        $this->x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    }

    //wgs84  to gcj02
    public function gcj_encrypt($wgsLat, $wgsLon)
    {
        $d = $this->delta($wgsLat, $wgsLon);
        return array('lat' => $wgsLat + $d['lat'], 'lon' => $wgsLon + $d['lon']);
    }


//    // two point's distance
//    public function distance($latA, $lonA, $latB, $lonB)
//    {
//         $earthR = 6371000.;
//        $x = cos($latA * $this->PI / 180.) * cos($latB * $this->PI / 180.) * cos(($lonA - $lonB) * $this->PI / 180);
//        $y = sin($latA * $this->PI / 180.) * sin($latB * $this->PI / 180.);
//        $s = $x + $y;
//        if($s > 1) $s = 1;
//        if($s < -1) $s = -1;
//        $alpha = acos($s);
//        $distance = $alpha * $earthR;
//        return $distance;
//    }

    private function delta($lat, $lon)
    {
// Krasovsky 1940
//
// a = 6378245.0, 1/f = 298.3
// b = a * (1 - f)
// ee = (a^2 - b^2) / a^2;
        $a = 6378245.0;//  a: 卫星椭球坐标投影到平面地图坐标系的投影因子。
        $ee = 0.00669342162296594323;//  ee: 椭球的偏心率。
        $dLat = $this->transformLat($lon - 105.0, $lat - 35.0);
        $dLon = $this->transformLon($lon - 105.0, $lat - 35.0);
        $radLat = $lat / 180.0 * $this->PI;
        $magic = sin($radLat);
        $magic = 1 - $ee * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $dLat = ($dLat * 180.0) / (($a * (1 - $ee)) / ($magic * $sqrtMagic) * $this->PI);
        $dLon = ($dLon * 180.0) / ($a / $sqrtMagic * cos($radLat) * $this->PI);
        return array('lat' => $dLat, 'lon' => $dLon);
    }

    private function transformLat($x, $y)
    {
        $ret = -100.0 + 2.0 * $x + 3.0 * $y + 0.2 * $y * $y + 0.1 * $x * $y + 0.2 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * $this->PI) + 20.0 * sin(2.0 * $x * $this->PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($y * $this->PI) + 40.0 * sin($y / 3.0 * $this->PI)) * 2.0 / 3.0;
        $ret += (160.0 * sin($y / 12.0 * $this->PI) + 320 * sin($y * $this->PI / 30.0)) * 2.0 / 3.0;
        return $ret;
    }

    private function transformLon($x, $y)
    {
        $ret = 300.0 + $x + 2.0 * $y + 0.1 * $x * $x + 0.1 * $x * $y + 0.1 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * $this->PI) + 20.0 * sin(2.0 * $x * $this->PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($x * $this->PI) + 40.0 * sin($x / 3.0 * $this->PI)) * 2.0 / 3.0;
        $ret += (150.0 * sin($x / 12.0 * $this->PI) + 300.0 * sin($x / 30.0 * $this->PI)) * 2.0 / 3.0;
        return $ret;
    }
}