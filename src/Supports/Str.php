<?php

namespace Lihq1403\Helper\Supports;

class Str
{
    /**
     * 对emoji表情转义
     * @param string $str
     * @return string
     */
    public static function emojiEncode(string $str)
    {
        $strEncode = '';

        $length = self::length($str);

        for ($i=0; $i < $length; $i++) {
            $_tmpStr = self::substr($str, $i,1);
            if(self::length($_tmpStr) >= 4){
                $strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
            }else{
                $strEncode .= $_tmpStr;
            }
        }

        return $strEncode;
    }

    /**
     * 对emoji表情转反义
     * @param string $str
     * @return string|string[]|null
     */
    public static function emojiDecode(string $str)
    {
        $strDecode = preg_replace_callback('|\[\[EMOJI:(.*?)\]\]|', function($matches){
            return rawurldecode($matches[1]);
        }, $str);

        return $strDecode;
    }


    /**
     * 生成uuid
     * @param string $prefix
     * @return string
     */
    public static function uuid(string $prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8) . '-';
        $uuid .= substr($chars,8,4) . '-';
        $uuid .= substr($chars,12,4) . '-';
        $uuid .= substr($chars,16,4) . '-';
        $uuid .= substr($chars,20,12);
        return $prefix . $uuid;
    }

    /**
     * 限制字符串长度，超过的部分用$replace替换
     * @param string $str
     * @param int $limit
     * @param string $replace
     * @return string
     */
    public static function limitStr(string $str, int $limit, $replace = '...')
    {
        if (self::length($str) <= $limit) {
            return $str;
        } else {
            return mb_substr($str, 0, $limit) . $replace;
        }
    }

    /**
     * 按模式生成随机数
     * @param int $length
     * @param string $type
     * @return string
     */
    public static function random(int $length = 6, $type = 'number|lower|upper|char')
    {
        $chars = [
            'number' => '0123456789', // 纯数字
            'lower' => 'abcdefghijklmnopqrstuvwxyz', // 纯小写字母
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', // 纯大写字母
            'char' => '!@#$%^&*()-_ []{}<>~`+=,.;:/?|' // 常用符号
        ];

        $type = explode('|', $type);

        $char_seeder = '';
        foreach ($type as $t) {
            $char_seeder .= $chars[$t] ?? '';
        }
        if (empty($char_seeder)) {
            $char_seeder = implode('', $chars);
        }

        $random_string = '';
        for ($i = 0; $i < $length; $i++) {
            $random_string .= $char_seeder[mt_rand(0, self::length($char_seeder) - 1)];

        }

        return $random_string;
    }

    /**
     * 检查字符串中是否包含某些字符串
     * @param string       $haystack
     * @param string|array $needles
     * @return bool
     */
    public static function contains(string $haystack, $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ('' != $needle && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串结尾
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function endsWith(string $haystack, $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === static::substr($haystack, -static::length($needle))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串开头
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function startsWith(string $haystack, $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ('' != $needle && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * 字符串转小写
     *
     * @param  string $value
     * @return string
     */
    public static function lower(string $value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 字符串转大写
     *
     * @param  string $value
     * @return string
     */
    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     *
     * @param  string $value
     * @return int
     */
    public static function length(string $value): int
    {
        return mb_strlen($value, 'UTF-8');
    }

    /**
     * 截取字符串
     *
     * @param  string   $string
     * @param  int      $start
     * @param  int|null $length
     * @return string
     */
    public static function substr(string $string, int $start, int $length = null): string
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }
}