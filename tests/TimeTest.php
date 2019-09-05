<?php

namespace Lihq1403\Helper\Tests;

use Lihq1403\Helper\Interfaces\DateGlobal;
use Lihq1403\Helper\Supports\Time;
use PHPUnit\Framework\TestCase;

/**
 * 编写日期 2019-09-05，所以使用准备好的时间戳
 * Class TimeTest
 * @package Lihq1403\Helper\Tests
 */
class TimeTest extends TestCase
{
    public function testToday()
    {
        $today = [
            strtotime('2019-09-05'),
            strtotime('2019-09-06') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($today, Time::today());
    }

    public function testYesterday()
    {
        $yesterday = [
            strtotime('2019-09-04'),
            strtotime('2019-09-05') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($yesterday, Time::yesterday());
    }

    public function testWeek()
    {
        $time = [
            strtotime('2019-09-02'),
            strtotime('2019-09-09') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($time, Time::week());
    }

    public function testLastWeek()
    {
        $time = [
            strtotime('2019-08-26'),
            strtotime('2019-09-02') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($time, Time::lastWeek());
    }

    public function testMonth()
    {
        $time = [
            strtotime('2019-09-01'),
            strtotime('2019-10-01') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($time, Time::month());
    }

    public function testLastMonth()
    {
        $time = [
            strtotime('2019-08-01'),
            strtotime('2019-09-01') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($time, Time::lastMonth());
    }

    public function testYear()
    {
        $time = [
            strtotime('2019-01-01'),
            strtotime('2020-01-01') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($time, Time::year());
    }

    public function testLastYear()
    {
        $time = [
            strtotime('2018-01-01'),
            strtotime('2019-01-01') - DateGlobal::SECONDS_IN_A_SECONDS,
        ];
        $this->assertEquals($time, Time::lastYear());
    }

    public function testDayToNow()
    {
        $this->assertEquals([
            strtotime('2019-09-04'),
            \time()
        ], Time::dayToNow(1, true));
        $this->assertEquals([
            strtotime('2019-09-04'),
            strtotime('2019-09-05') - DateGlobal::SECONDS_IN_A_SECONDS
        ], Time::dayToNow(1, false));
    }

    public function testDaysAgo()
    {
        $this->assertEquals(strtotime('2019-09-03 '.date('H:i:s')), Time::daysAgo(2));
    }

    public function testDaysAfter()
    {
        $this->assertEquals(strtotime('2019-09-07 '.date('H:i:s')), Time::daysAfter(2));
    }

    public function testDaysToSecond()
    {
        $this->assertEquals(60 * 60 * 24 * 2, Time::daysToSecond(2));
    }

    public function testWeeksToSecond()
    {
        $this->assertEquals(60 * 60 * 24 * 7 * 2, Time::weeksToSecond(2));
    }

    public function testAgeByBirthday()
    {
        $this->assertEquals(25, Time::ageByBirthday(strtotime('1994-08-08')));
        $this->assertEquals(26, Time::ageByBirthday(strtotime('1994-08-08'), 1));
    }

    public function testDefaultFormat()
    {
        $this->assertEquals('2018-08-08 00:00:00', Time::defaultFormat(strtotime('2018-08-08')));
    }

}
