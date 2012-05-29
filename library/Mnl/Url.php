<?php
namespace Mnl;

class Url
{
    public static function isRelative($url)
    {
        if (substr($url, 0, 1) == '/') {
            return false;
        }
        if (preg_match('/^http(s)?:\/\//', $url)) {
            return false;
        }
        return true;
    }
}
