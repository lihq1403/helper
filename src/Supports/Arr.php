<?php

namespace Lihq1403\Helper\Supports;


use ArrayAccess;

class Arr
{
    /**
     * 列表数组转换为树结构
     * @param array $array
     * @param int $root
     * @param string $id
     * @param string $pid
     * @param string $child_name
     * @return array
     */
    public static function listToTree(array $array, $root = 0, $id = 'id', $pid = 'pid', $child_name = 'child')
    {
        $tree = [];
        foreach ($array as $k => $v) {
            if ($v[$pid] == $root) {
                $v[$child_name] = self::listToTree($array, $v[$id]);
                $tree[] = $v;
                unset($array[$k]);
            }
        }
        return $tree;
    }

    /**
     * 树结构转换为列表数组
     * @param array $tree
     * @param string $id
     * @param string $child
     * @return array
     */
    public static function treeToList(array $tree, $id = 'id', $child = 'child')
    {
        $array = [];
        foreach ($tree as $k => $val) {
            $array[] = $val;
            if (!empty($val[$child])) {
                $children = self::treeToList($val[$child], $val[$id]);
                if ($children) {
                    $array = array_merge($array, $children);
                }
            }
        }
        foreach ($array as &$item) {
            unset($item[$child]);
        }
        return $array;
    }

    /**
     * 二维数组按照某个字段排序
     * @param array $array
     * @param $field
     * @param int $arg
     * @return mixed
     */
    public static function sortByKey(array $array, $field, $arg = SORT_ASC)
    {
        if (!empty($array)) {
            foreach ($array as $v) {
                $sort[] = $v[$field];
            }
            array_multisort($sort, $arg, $array);
        }
        return $array;
    }

    /**
     * 数组递归格式化，默认两边去空
     * @param array $array
     * @param string $function
     * @return array
     */
    public static function mapFunction(array $array, $function = 'trim')
    {
        foreach ($array as &$item) {
            if (is_array($item)) {
                $item = self::mapFunction($item, $function);
            } else {
                if (is_array($function)) {
                    foreach ($function as $func) {
                        $item = $func($item);
                    }
                } else {
                    $item = $function($item);
                }
            }
        }
        return $array;
    }

    /**
     * 使用给定的回调对数组进行排序并保留原始键。
     * @param array    $array
     * @param callable $callback
     * @param int      $options
     * @param bool     $descending
     * @return array
     */
    public static function sortBy(array $array, callable $callback, $options = SORT_REGULAR, $descending = false): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        $descending ? arsort($results, $options)
            : asort($results, $options);

        foreach (array_keys($results) as $key) {
            $results[$key] = $array[$key];
        }

        return $results;
    }

    /**
     * Determine whether the given value is array accessible.
     * 检验是否是一个数组 或 具有像访问数组一样访问对象的能力
     * @param mixed $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     * 判断数组 键 是否存在
     * @param \ArrayAccess|array $array
     * @param string|int         $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string             $key
     * @param mixed              $default
     * @return mixed
     */
    public static function get($array, $key, $default = '')
    {
        if (!static::accessible($array)) {
            return value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}