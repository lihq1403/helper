<?php

namespace Lihq1403\Helper\Supports;

class File
{
    /**
     * 检查文件是否存在
     * @param string $file
     * @return bool
     */
    public static function exist($file = '')
    {
        if (!$file || !file_exists($file)) {
            return false;
        }
        return true;
    }

    /**
     * 检查文件是否可读
     * @param string $file
     * @return bool
     */
    public static function readable($file = '')
    {
        if (!self::exist($file) || !is_readable($file)) {
            return false;
        }
        return true;
    }
}