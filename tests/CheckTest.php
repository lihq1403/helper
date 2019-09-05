<?php

namespace Lihq1403\Helper\Tests;

use Lihq1403\Helper\Supports\Check;
use PHPUnit\Framework\TestCase;

class CheckTest extends TestCase
{
    public function testIsMobile()
    {
        $this->assertEquals(true, Check::isMobile('13800138000'));
        $this->assertEquals(false, Check::isMobile('10086'));
    }

    public function testIsEmail()
    {
        $this->assertEquals(true, Check::isEmail('13800138000@qq.com'));
        $this->assertEquals(false, Check::isEmail('10086'));
    }

    public function testIsIp()
    {
        $this->assertEquals(true, Check::isIp('127.0.0.1'));
        $this->assertEquals(false, Check::isIp('114.114.114.256'));
        $this->assertEquals(true, Check::isIp('2001:0db8:85a3:08d3:1319:8a2e:0370:7344', 'ipv6'));
    }

    public function testIsUrl()
    {
        $this->assertEquals(false, Check::isUrl('127.0.0.1'));
        $this->assertEquals(true, Check::isUrl('http://www.baidu.com'));
        $this->assertEquals(true, Check::isUrl('ftp://www.baidu.com'));
    }

    public function testIsMacAddr()
    {
        $this->assertEquals(true, Check::isMacAddr('07-16-76-00-02-86'));
        $this->assertEquals(false, Check::isMacAddr('07-16-76-00-02-8G'));
    }

    public function testIsData()
    {
        $this->assertEquals(true, Check::isDate('2019-08-09'));
        $this->assertEquals(false, Check::isDate('2019-02-31'));
    }

    public function testISJson()
    {
        $this->assertEquals(false, Check::isJson('{"id"=>1}'));
        $this->assertEquals(true, Check::isJson('{ "firstName":"John" , "lastName":"Doe" }'));
    }

    public function testIsToday()
    {
        $this->assertEquals(false, Check::isToday(strtotime('2010-08-09')));
        $this->assertEquals(true, Check::isToday(time()));
    }
}