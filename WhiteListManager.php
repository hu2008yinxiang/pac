<?php

class WhiteListManager
{

    const SOURCE_URL = 'https://raw.githubusercontent.com/n0wa11/gfw_whitelist/master/whitelist.pac';

    /**
     *
     * @return boolean
     */
    public static function doUpdate()
    {
        $pac_content = file_get_contents(static::SOURCE_URL);
        if (! stripos($pac_content, 'return "DIRECT"')) {
            return false;
        }
        return file_put_contents(__DIR__ . '/whitelist.pac', $pac_content) !== false;
    }

    public static function doMerge($server, array $rules, $type = 'PROXY')
    {
        $keyword = '//custom-list';
        $proxy = "var IP_ADDRESS = 'www.abc.com:443'";
        $proxy_type = "var PROXY_TYPE = 'HTTPS'";
        $pac_content = file_get_contents(__DIR__ . '/whitelist.pac');
        $rule_content = json_encode($rules);
        return str_ireplace(array(
            $keyword,
            $proxy,
            $proxy_type
        ), array(
            $rule_content . ',' . PHP_EOL . $keyword,
            "var IP_ADDRESS = '$server'",
            "var PROXY_TYPE = '$type'"
        ), $pac_content);
    }
}