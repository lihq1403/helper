<?php

namespace Lihq1403\Helper\Supports;

/**
 * Csv文件操作
 * Class Csv
 * @package Lihq1403\Helper\Supports
 */
class Csv
{
    /**
     * 读取csv文件数据
     * @param string $csv_file
     * @param int $lines 需要读取的行数 0代表所有
     * @param int $start 开始行数
     * @return array
     */
    public static function read($csv_file = '', $lines = 0, $start = 0)
    {
        if (!File::readable($csv_file)) {
            return [];
        }
        $spl_object = new \SplFileObject($csv_file, 'rb');

        // 0代表全部，即最大行
        if (empty($lines)) {
            $spl_object->seek(filesize($csv_file));
            $lines = $spl_object->key();
        }

        $data = [];
        $spl_object->seek($start);
        while ($lines-- && !$spl_object->eof()) {
            $data[] = $spl_object->fgetcsv();
            $spl_object->next();
        }
        return $data;
    }
}