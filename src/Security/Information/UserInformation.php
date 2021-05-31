<?php

namespace MElaraby\Emerald\Security\Information;

use function request;

class UserInformation
{
    /**
     * @return array
     */
    public static function getLoginInformation(): array
    {
        return [
            'browser' => static::getBrowser(),
            'device' => static::getDevice(),
            'OS' => static::getOS(),
            'ip_address' => static::getIP(),
            'location' => static::getUserAgent(),
        ];
    }

    /**
     * @return string
     */
    public static function getBrowser(): string
    {

        $userAgent = self::getUserAgent();

        $browser = "Unknown Browser";

        $browserArray = array(
            '/msie/i' => 'Internet Explorer',
            '/Trident/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/knoqueror/i' => 'Konqueror',
            '/ubrowser/i' => 'UC Browser',
            '/mobile/i' => 'Safari Browser',
        );

        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser = $value;
            }
        }

        return $browser;
    }

    /**
     * @return mixed
     */
    private static function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * @return string
     */
    public static function getDevice(): string
    {
        $tabletBrowser = $mobileBrowser = 0;

        $mobileAgents = array(
            'w3c', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac', 'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',

            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox', 'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',

            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-');

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tabletBrowser++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobileBrowser++;
        }

        if ((isset ($_SERVER['HTTP_ACCEPT']) && (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0))
            or
            ((isset($_SERVER['HTTP_X_WAP_PROFILE'])
                or
                isset($_SERVER['HTTP_PROFILE'])))) {
            $mobileBrowser++;
        }

        $mobileUA = strtolower(substr(self::getUserAgent(), 0, 4));

        if (in_array($mobileUA, $mobileAgents)) {
            $mobileBrowser++;
        }

        if (strpos(strtolower(self::getUserAgent()), 'opera mini') > 0) {
            $mobileBrowser++;

            //Check for tables on opera mini alternative headers
            $stock_ua = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] ?? ($_SERVER['HTTP_DEVICE_STOCK_UA'] ?? ''));

            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tabletBrowser++;
            }
        }

        if ($tabletBrowser > 0) {
            //do something for tablet devices

            return 'Tablet';
        } else if ($mobileBrowser > 0) {
            //do something for mobile devices

            return 'Mobile';
        }

        //do something for everything else
        return 'Computer';

    }

    /**
     * @return string
     */
    public static function getOS(): string
    {
        $userAgent = self::getUserAgent();
        $OSPlatform = "Unknown OS Platform";
        $OSArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        );

        foreach ($OSArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $OSPlatform = $value;
            }
        }

        return $OSPlatform;
    }

    /**
     * @return string
     */
    public static function getIP(): string
    {
        return request()->getClientIp();
//browser
//        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
//            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
//        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
//            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
//        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
//            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
//        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
//            $ipAddress = $_SERVER['HTTP_FORWARDED'];
//        } else if (isset($_SERVER['REMOTE_ADDR'])) {
//            $ipAddress = $_SERVER['REMOTE_ADDR'];
//        } else {
//            $ipAddress = 'UNKNOWN';
//        }
//
//        return $ipAddress;

    }
}
