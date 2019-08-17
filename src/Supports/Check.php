<?php

namespace Lihq1403\Helper\Supports;

class Check
{
    /**
     * 检查是否是手机号码
     * @param string $mobile
     * @return bool
     */
    public static function isMobile(string $mobile): bool
    {
        return regex_check($mobile, '/^1[3-9]\\d{9}$/');
    }

    /**
     * 是否是邮箱地址
     * @param string $email
     * @return bool
     */
    public static function isEmail(string $email)
    {
        return filter_check($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * 是否是ip地址
     * @param string $ip
     * @param string $rule
     * @return bool
     */
    public static function isIp(string $ip, $rule = 'ipv4')
    {
        if (!in_array($rule, ['ipv4', 'ipv6'])) {
            $rule = 'ipv4';
        }
        return filter_check($ip, [FILTER_VALIDATE_IP, 'ipv6' == $rule ? FILTER_FLAG_IPV6 : FILTER_FLAG_IPV4]);
    }

    /**
     * 是否是url网址
     * @param string $url
     * @return bool
     */
    public static function isUrl(string $url)
    {
        return filter_check($url, FILTER_VALIDATE_URL);
    }

    /**
     * 是否是mac物理地址
     * @param string $mac_addr
     * @return bool
     */
    public static function isMacAddr(string $mac_addr)
    {
        return filter_check($mac_addr, FILTER_VALIDATE_MAC);
    }

    /**
     * 验证是否是个日期
     * @param string $value
     * @return bool
     */
    public static function isDate(string $value)
    {
        if (is_numeric($value)) {
            return false;
        } else {
            if (strtotime($value)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 验证是否是个json字符串
     * @param string $str
     * @return bool
     */
    public static function isJson(string $str)
    {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}