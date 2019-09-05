<?php

namespace Lihq1403\Helper\Tests;

use Lihq1403\Helper\Supports\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public function testListToTree()
    {
        $arr = [
            [
                'id'   => 1,
                'name' => '一',
                'pid'  => 0
            ],
            [
                'id'   => 2,
                'name' => '二',
                'pid'  => 1
            ],
            [
                'id'   => 3,
                'name' => '三',
                'pid'  => 2
            ],
            [
                'id'   => 4,
                'name' => '四',
                'pid'  => 0
            ],
        ];
        $arr_result = [
            [
                'id'    => 1,
                'name'  => '一',
                'pid'   => 0,
                'child' => [
                    [
                        'id'    => 2,
                        'name'  => '二',
                        'pid'   => 1,
                        'child' => [
                            [
                                'id'    => 3,
                                'name'  => '三',
                                'pid'   => 2,
                                'child' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id'    => 4,
                'name'  => '四',
                'pid'   => 0,
                'child' => [],
            ],
        ];
        $this->assertEquals($arr_result, Arr::listToTree($arr));
    }

    public function testTreeToList()
    {
        $arr = [
            [
                'id'    => 1,
                'name'  => '一',
                'pid'   => 0,
                'child' => [
                    [
                        'id'    => 2,
                        'name'  => '二',
                        'pid'   => 1,
                        'child' => [
                            [
                                'id'    => 3,
                                'name'  => '三',
                                'pid'   => 2,
                                'child' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id'    => 4,
                'name'  => '四',
                'pid'   => 0,
                'child' => [],
            ],
        ];

        $arr_result = [
            [
                'id'   => 1,
                'name' => '一',
                'pid'  => 0
            ],
            [
                'id'   => 2,
                'name' => '二',
                'pid'  => 1
            ],
            [
                'id'   => 3,
                'name' => '三',
                'pid'  => 2
            ],
            [
                'id'   => 4,
                'name' => '四',
                'pid'  => 0
            ],
        ];

        $this->assertEquals($arr_result, Arr::treeToList($arr));
    }

    public function testSortKey()
    {
        $arr = [
            ['id' => 1, 'sort' => 9, 'name' => '一'],
            ['id' => 2, 'sort' => 4, 'name' => '二'],
            ['id' => 3, 'sort' => 6, 'name' => '三'],
            ['id' => 4, 'sort' => 1, 'name' => '四'],
            ['id' => 5, 'sort' => 3, 'name' => '五'],
        ];

        $arr_result = [
            ['id' => 4, 'sort' => 1, 'name' => '四'],
            ['id' => 5, 'sort' => 3, 'name' => '五'],
            ['id' => 2, 'sort' => 4, 'name' => '二'],
            ['id' => 3, 'sort' => 6, 'name' => '三'],
            ['id' => 1, 'sort' => 9, 'name' => '一'],
        ];

        $this->assertEquals($arr_result, Arr::sortByKey($arr, 'sort', SORT_ASC));
    }

    public function testMapFunction()
    {
        $arr = [
            0, '', false, null, ' hhh', 'fff ', 'null'
        ];
        $arr_trim_result = [
            0, '', '', '', 'hhh', 'fff', 'null'
        ];
        $this->assertEquals($arr_trim_result, Arr::mapFunction($arr, 'trim'));

        $function = function ($var) {
            return 'test-' . $var;
        };

        $arr_function_result = [
            'test-0', 'test-', 'test-', 'test-', 'test- hhh', 'test-fff ', 'test-null'
        ];
        $this->assertEquals($arr_function_result, Arr::mapFunction($arr, $function));
    }

    public function testAccessible()
    {
        $arr1 = [1, 2, 3, 4];
        $arr2 = 1;
        $this->assertEquals(true, Arr::accessible($arr1));
        $this->assertEquals(false, Arr::accessible($arr2));
    }

    public function testExists()
    {
        $arr = [1, 2, 3, 4];

        $this->assertEquals(true, Arr::exists($arr, 3));
        $this->assertEquals(false, Arr::exists($arr, 4));
    }

    public function testGet()
    {
        $arr = [1, 2, 3, 4, 't' => [
            'est' => 'test'
        ]];
        $this->assertEquals(4, Arr::get($arr, 3));
        $this->assertEquals('default', Arr::get($arr, 4, 'default'));
        $this->assertEquals('test', Arr::get($arr, 't.est', 'default'));
    }

}