<?php

namespace Lihq1403\Helper\Supports;

use DateTime;
use Lihq1403\Helper\Interfaces\DateGlobal;

class Time
{
    /**
     * 返回今日开始和结束的时间戳
     *
     * @return array
     */
    public static function today()
    {
        list($y, $m, $d) = explode('-', date('Y-m-d'));
        return [
            mktime(0, 0, 0, $m, $d, $y),
            mktime(23, 59, 59, $m, $d, $y)
        ];
    }

    /**
     * 返回昨日开始和结束的时间戳
     *
     * @return array
     */
    public static function yesterday()
    {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, date('m'), $yesterday, date('Y')),
            mktime(23, 59, 59, date('m'), $yesterday, date('Y'))
        ];
    }

    /**
     * 返回本周开始和结束的时间戳
     *
     * @return array
     */
    public static function week()
    {
        list($y, $m, $d, $w) = explode('-', date('Y-m-d-w'));
        if($w == 0) $w = 7; //修正周日的问题
        return [
            mktime(0, 0, 0, $m, $d - $w + 1, $y), mktime(23, 59, 59, $m, $d - $w + 7, $y)
        ];
    }

    /**
     * 返回上周开始和结束的时间戳
     *
     * @return array
     */
    public static function lastWeek()
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }

    /**
     * 返回本月开始和结束的时间戳
     *
     * @return array
     */
    public static function month()
    {
        list($y, $m, $t) = explode('-', date('Y-m-t'));
        return [
            mktime(0, 0, 0, $m, 1, $y),
            mktime(23, 59, 59, $m, $t, $y)
        ];
    }

    /**
     * 返回上个月开始和结束的时间戳
     *
     * @return array
     */
    public static function lastMonth()
    {
        $y = date('Y');
        $m = date('m');
        $begin = mktime(0, 0, 0, $m - 1, 1, $y);
        $end = mktime(23, 59, 59, $m - 1, date('t', $begin), $y);
        return [$begin, $end];
    }

    /**
     * 返回今年开始和结束的时间戳
     *
     * @return array
     */
    public static function year()
    {
        $y = date('Y');
        return [
            mktime(0, 0, 0, 1, 1, $y),
            mktime(23, 59, 59, 12, 31, $y)
        ];
    }

    /**
     * 返回去年开始和结束的时间戳
     *
     * @return array
     */
    public static function lastYear()
    {
        $year = date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year)
        ];
    }

    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow($day = 1, $now = true)
    {
        $end = time();
        if (!$now) {
            list($foo, $end) = self::yesterday();
        }
        return [
            mktime(0, 0, 0, date('m'), date('d') - $day, date('Y')),
            $end
        ];
    }

    /**
     * 返回几天前的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAgo($day = 1)
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }

    /**
     * 返回几天后的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAfter($day = 1)
    {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }

    /**
     * 天数转换成秒数
     *
     * @param int $day
     * @return int
     */
    public static function daysToSecond($day = 1)
    {
        return $day * DateGlobal::SECONDS_IN_A_DAY;
    }

    /**
     * 周数转换成秒数
     *
     * @param int $week
     * @return int
     */
    public static function weeksToSecond($week = 1)
    {
        return DateGlobal::SECONDS_IN_A_WEEK * $week;
    }

    /**
     * 人性化显示两个时间的差
     * @param DateTime $leftTime
     * @param DateTime $rightTime
     * @param bool     $absolute
     * @return string
     */
    public static function timeDiffFormat(DateTime $leftTime, DateTime $rightTime, $absolute = false): string
    {
        $diff = $leftTime->diff($rightTime, $absolute);
        return ($absolute && !$diff->invert ? '-' : '')
            . ($diff->y ? $diff->y . '年' : '')
            . ($diff->m ? $diff->m . '月' : '')
            . ($diff->d ? $diff->d . '日' : '')
            . ($diff->h ? $diff->h . '小时' : '')
            . ($diff->i ? $diff->i . '分钟' : '')
            . ($diff->s ? $diff->s . '秒' : '');
    }

    /**
     * 根据生日计算年龄
     * @param $timestamp | 时间戳
     * @param int $type | 1的时候是虚岁,2的时候是周岁
     * @return false|int|string
     */
    public static function ageByBirthday($timestamp, $type = 2)
    {
        $nowYear = date("Y", time());
        $nowMonth = date("m", time());
        $nowDay = date("d", time());
        $birthYear = date("Y", $timestamp);
        $birthMonth = date("m", $timestamp);
        $birthDay = date("d", $timestamp);
        if($type == 1){
            $age = $nowYear - ($birthYear - 1);
        }else{
            if($nowMonth < $birthMonth){
                $age = $nowYear - $birthYear - 1;
            }elseif($nowMonth == $birthMonth){
                if($nowDay < $birthDay){
                    $age = $nowYear - $birthYear - 1;
                }else{
                    $age = $nowYear - $birthYear;
                }
            }else{
                $age = $nowYear - $birthYear;
            }
        }
        return $age;
    }

    /**
     * 返回一个时间格式
     * @param $timestamp
     * @param string $format
     * @return false|string
     */
    public static function defaultFormat($timestamp, $format = 'Y-m-d H:i:s')
    {
        if (empty($timestamp)) {
            return '';
        }
        return date($format, $timestamp);
    }
}