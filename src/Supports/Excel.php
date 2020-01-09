<?php

namespace Lihq1403\Helper\Supports;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Excel
{
    /**
     * 通用导出
     * @param array $header_data
     * @param array $data
     * @param array $width
     * @param array $field
     * @param string $file_name
     * @param string $savePath
     * @return bool|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function generalExport(array $header_data, array $data, $width = [], $field = [], string $file_name = '', string $savePath = __DIR__)
    {
        // 已header为准
        $count = count($header_data);
        if ($count != count($width) || $count != count($field)) {
            return false;
        }
        // 获取表头列数组
        $header_arr = [];
        for ($i=1; $i<=$count;$i++) {
            array_push($header_arr, Coordinate::stringFromColumnIndex($i));
        }

        $list = [];

        // 需要加粗的单元格
        $bold = [];
        // 单元格宽度
        $setWidth = [];

        // 头部数据组装
        foreach ($header_arr as $k => $col) {
            $list[$col.'1'] = $header_data[$k] ?? '';
            array_push($bold, $col.'1');

            // 宽度处理
            if (!empty($width)) {
                $setWidth[$col] = $width[$k];
            }
        }

        // 内容数据组装
        $i = 2; // 从第几行开始,最好是字符串
        $j = 0; // 数据根据$field获取
        foreach ($data as $d) {
            foreach ($header_arr as $col) {
                $list[$col.$i] = $d[$field[$j]] ?? '';
                $j++;
            }
            $j = 0;
            $i++;
        }

        $options = [
            'savePath' => $savePath,
            'setBorder' => true,
            'bold' => $bold,
            'setWidth' => $setWidth
        ];

        return self::export($list, $file_name, $options);
    }

    /**
     * 导出excel
     * @param array $data  ['A1' => '名称', 'B1' => '序号']
     * @param string $file_name
     * @param array $options
     * $options 操作选项，例如：
     *                           bool   print       设置打印格式
     *                           string freezePane  锁定行数，例如表头为第一行，则锁定表头输入A2
     *                           array  setARGB     设置背景色，例如['A1', 'C1']
     *                           array  setWidth    设置宽度，例如['A' => 30, 'C' => 20]
     *                           bool   setBorder   设置单元格边框
     *                           array  mergeCells  设置合并单元格，例如['A1:J1' => 'A1:J1']
     *                           array  formula     设置公式，例如['F2' => '=IF(D2>0,E42/D2,0)']
     *                           array  format      设置格式，整列设置，例如['A' => 'General']
     *                           array  alignCenter 设置居中样式，例如['A1', 'A2']
     *                           array  bold        设置加粗样式，例如['A1', 'A2']
     *                           string savePath    保存路径，设置后则文件保存到服务器，不通过浏览器下载
     * @param int $active_sheet
     * @param string $writerType
     * @return bool|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function export(array $data, string $file_name = '', array $options = [], $active_sheet = 0, $writerType = 'Xlsx')
    {
        if (empty($data)) {
            return false;
        }
        set_time_limit(0);

        $objSpreadsheet = new Spreadsheet();

        // 设置默认文字上下左右居中
        $styleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];
        $objSpreadsheet->getDefaultStyle()->applyFromArray($styleArray);
        // 选择Excel Sheet
        $activeSheet = $objSpreadsheet->setActiveSheetIndex($active_sheet);

        // 打印设置
        if (isset($options['print']) && $options['print']) {
            // 设置打印为A4效果
            $activeSheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            // 设置打印时边距
            $pValue = 1 / 2.54;
            $activeSheet->getPageMargins()->setTop($pValue / 2);
            $activeSheet->getPageMargins()->setBottom($pValue * 2);
            $activeSheet->getPageMargins()->setLeft($pValue / 2);
            $activeSheet->getPageMargins()->setRight($pValue / 2);
        }

        // 行数据处理
        foreach ($data as $sKey => $sItem) {
            // 默认文本格式
            $pDataType = DataType::TYPE_STRING;

            // 设置单元格格式
            if (isset($options['format']) && !empty($options['format'])) {
                $colRow = Coordinate::coordinateFromString($sKey);

                /* 存在该列格式并且有特殊格式 */
                if (isset($options['format'][$colRow[0]]) && NumberFormat::FORMAT_GENERAL != $options['format'][$colRow[0]]) {

                    $activeSheet->getStyle($sKey)->getNumberFormat()->setFormatCode($options['format'][$colRow[0]]);

                    if (false !== strpos($options['format'][$colRow[0]], '0.00') &&
                        is_numeric(str_replace(['￥', ','], '', $sItem))) {
                        /* 数字格式转换为数字单元格 */
                        $pDataType = DataType::TYPE_NUMERIC;
                        $sItem     = str_replace(['￥', ','], '', $sItem);
                    }
                } elseif (is_int($sItem)) {
                    $pDataType = DataType::TYPE_NUMERIC;
                }
            }

            $activeSheet->setCellValueExplicit($sKey, $sItem, $pDataType);

            if (false !== strstr($sKey, ':')) {
                $options['mergeCells'][$sKey] = $sKey;
            }
        }
        unset($data);

        /* 设置锁定行 */
        if (isset($options['freezePane']) && !empty($options['freezePane'])) {
            $activeSheet->freezePane($options['freezePane']);
            unset($options['freezePane']);
        }

        /* 设置宽度 */
        if (isset($options['setWidth']) && !empty($options['setWidth'])) {
            foreach ($options['setWidth'] as $swKey => $swItem) {
                $activeSheet->getColumnDimension($swKey)->setWidth($swItem);
            }

            unset($options['setWidth']);
        }

        /* 设置背景色 */
        if (isset($options['setARGB']) && !empty($options['setARGB'])) {
            foreach ($options['setARGB'] as $sItem) {
                $activeSheet->getStyle($sItem)
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB(Color::COLOR_YELLOW);
            }

            unset($options['setARGB']);
        }

        /* 设置公式 */
        if (isset($options['formula']) && !empty($options['formula'])) {
            foreach ($options['formula'] as $fKey => $fItem) {
                $activeSheet->setCellValue($fKey, $fItem);
            }

            unset($options['formula']);
        }

        /* 合并行列处理 */
        if (isset($options['mergeCells']) && !empty($options['mergeCells'])) {
            $activeSheet->setMergeCells($options['mergeCells']);
            unset($options['mergeCells']);
        }

        /* 设置居中 */
        if (isset($options['alignCenter']) && !empty($options['alignCenter'])) {
            $styleArray = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ];

            foreach ($options['alignCenter'] as $acItem) {
                $activeSheet->getStyle($acItem)->applyFromArray($styleArray);
            }

            unset($options['alignCenter']);
        }

        /* 设置加粗 */
        if (isset($options['bold']) && !empty($options['bold'])) {
            foreach ($options['bold'] as $bItem) {
                $activeSheet->getStyle($bItem)->getFont()->setBold(true);
            }

            unset($options['bold']);
        }

        /* 设置单元格边框，整个表格设置即可，必须在数据填充后才可以获取到最大行列 */
        if (isset($options['setBorder']) && $options['setBorder']) {
            $border    = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN, // 设置border样式
                        'color'       => ['argb' => 'FF000000'], // 设置border颜色
                    ],
                ],
            ];
            $setBorder = 'A1:' . $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
            $activeSheet->getStyle($setBorder)->applyFromArray($border);
            unset($options['setBorder']);
        }

        $fileName = !empty($file_name) ? $file_name : (date('YmdHis') . '.'.strtolower($writerType));

        if (!isset($options['savePath'])) {
            /* 直接导出Excel，无需保存到本地，输出07Excel文件 */
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header(
                "Content-Disposition:attachment;filename=" . iconv(
                    "utf-8", "GB2312//TRANSLIT", $fileName
                )
            );
            header('Cache-Control: max-age=0');//禁止缓存
            //中文名兼容各种浏览器
            $ua = $_SERVER["HTTP_USER_AGENT"] ?? '';
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
            }
            $savePath = 'php://output';
        } else {
            $savePath = rtrim($options['savePath'], '/') . '/'.$fileName;
        }

        ob_clean();
        ob_start();
        $objWriter = IOFactory::createWriter($objSpreadsheet, $writerType);
        $objWriter->save($savePath);
        /* 释放内存 */
        $objSpreadsheet->disconnectWorksheets();
        unset($objSpreadsheet);
        ob_end_flush();

        return $savePath;
    }
}