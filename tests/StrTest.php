<?php

namespace Lihq1403\Helper\Tests;

use Lihq1403\Helper\Supports\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    public function testUuid()
    {
        $this->assertStringStartsWith('prefix', Str::uuid('prefix'));
    }

    public function testLimitStr()
    {
        $str = '1234567890';

        $this->assertEquals($str, Str::limitStr($str, 10));

        $this->assertEquals('1234567...', Str::limitStr($str, 7));

        $this->assertEquals('1234567!!!', Str::limitStr($str, 7, '!!!'));
    }

    public function testRandom()
    {
        $this->assertRegExp('/^[a-z]{6}$/', Str::random(6, 'lower'));
        $this->assertRegExp('/^[A-Z]{6}$/', Str::random(6, 'upper'));
        $this->assertRegExp('/^[0-9]{6}$/', Str::random(6, 'number'));
        $this->assertRegExp('/^[\!\@\#\$\%\^\&\*\(\)\-\_ \[\]\{\}\<\>\~\`\+\=\,\.\;\:\/\?\|]{6}$/', Str::random(6, 'char'));
        $this->assertRegExp('/^[A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_ \[\]\{\}\<\>\~\`\+\=\,\.\;\:\/\?\|]{6}$/', Str::random(6));
    }

    public function testContains()
    {
        $this->assertEquals(false, Str::contains('abc789abc', '456'));
        $this->assertEquals(true, Str::contains('abc789abc', '789'));
    }

    public function testEndsWith()
    {
        $this->assertEquals(true, Str::endsWith('abc', 'bc'));
        $this->assertEquals(false, Str::endsWith('abc', 'ac'));
    }

    public function testStartsWith()
    {
        $this->assertEquals(true, Str::startsWith('abc', 'ab'));
        $this->assertEquals(false, Str::startsWith('abc', 'bc'));
    }

    public function testLower()
    {
        $this->assertEquals('abc', Str::lower('ABc'));
    }

    public function testUpper()
    {
        $this->assertEquals('ABC', Str::upper('aBc'));
    }

    public function testLength()
    {
        $this->assertEquals(8, Str::length('123456hh'));
        $this->assertEquals(8, Str::length('123456哈哈'));
    }

    public function testSubStr()
    {
        $this->assertEquals('abc', Str::substr('abc_efj', 0, 3));
        $this->assertEquals('嘿_哈', Str::substr('嘿嘿嘿_哈哈哈', 2, 3));
    }
}