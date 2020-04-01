<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProtectBlock extends Model
{
    public static function getUserAgent(){
        $Agents[]='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36';
        $Agents[]='Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0';
        $Agents[]='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393';
        $Agents[]='Mozilla/5.0 (iPad; CPU OS 8_4_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12H321 Safari/600.1.4';
        $Agents[]='Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1';
        $Agents[]='Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
        $Agents[]='Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)';
        $Agents[]='Mozilla/5.0 (Linux; Android 6.0.1; SAMSUNG SM-G570Y Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/4.0 Chrome/44.0.2403.133 Mobile Safari/537.36';
        $Agents[]='Mozilla/5.0 (Linux; Android 5.0; SAMSUNG SM-N900 Build/LRX21V) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/34.0.1847.76 Mobile Safari/537.36';
        $Agents[]='Mozilla/5.0 (Linux; Android 6.0.1; SAMSUNG SM-N910F Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/4.0 Chrome/44.0.2403.133 Mobile Safari/537.36';
        $Agents[]='Mozilla/5.0 (Linux; U; Android-4.0.3; en-us; Galaxy Nexus Build/IML74K) AppleWebKit/535.7 (KHTML, like Gecko) CrMo/16.0.912.75 Mobile Safari/535.7';
        $Agents[]='Mozilla/5.0 (Linux; Android 7.0; HTC 10 Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36';
        $Agents[]='Wget/1.15 (linux-gnu)';
        $Agents[]='Lynx/2.8.8pre.4 libwww-FM/2.14 SSL-MM/1.4.1 GNUTLS/2.12.23';
        $index= array_rand($Agents);
        return $Agents[$index];
    }
    
    public static function getProxy(){
        $proxy[]='https://45.77.202.16:8118';
        $proxy[]='https://38.91.100.171:3128';
        $proxy[]='https://64.235.204.107:8080';
        $proxy[]='https://138.197.32.120:3128';
        $proxy[]='https://45.77.202.16:8118';
        $proxy[]='https://45.32.195.135:3128';
        $proxy[]='https://174.138.74.211:8080';
        $index= array_rand($proxy);
        return $proxy[$index];
    }
}
