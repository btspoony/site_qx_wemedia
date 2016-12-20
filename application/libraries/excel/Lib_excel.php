<?php
require_once 'PHPExcel.php';
require_once 'PHPExcel/Reader/Excel2007.php';
require_once 'PHPExcel/Reader/Excel5.php';
require_once 'PHPExcel/IOFactory.php';

class Lib_excel {

    /**
     * 获取execl内容
     * @param type $filename
     * @return boolean
     */
    public function getExeclData($filename = null, $rowStart = 2) {
        if (!file_exists($filename)) {
            return false;
        }
        // $reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
        $PHPExcel = PHPExcel_IOFactory::load($filename); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数

        $ret_data = array();
        /** 循环读取每个单元格的数据 */
        for ($row = $rowStart; $row <= $highestRow; $row++) {//行数是以第1行开始
            $valData = array();
            for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
                $valData[] = $sheet->getCell($column . $row)->getValue();
            }
            $ret_data[] = $valData;
        }
        return $ret_data;
    }

    /**
     * 生成一个execl文件
     * @param type $fileName
     * @param type $headArr
     * @param type $data
     * @param type $type download 下载, || 生成一个文件
     * $fileName = "test_excel";
      $headArr = array("第一列","第二列","第三列");
      $data = array(array(1,2),array(1,3),array(5,7));
      writeExcel($fileName,$headArr,$data);
     */
    function writeExcel($data = array(), $title = array(), $filename = 'report', $type = null) {
        $objPHPExcel = new PHPExcel();
       
        //设置文档属性，设置中文会产生乱码，需要转换成utf-8格式！！
        // $objPHPExcel->getProperties()->setCreator("云舒")
        // ->setLastModifiedBy("云舒")
        // ->setTitle("产品URL导出")
        // ->setSubject("产品URL导出")
        // ->setDescription("产品URL导出")
        // ->setKeywords("产品URL导出");
        $objPHPExcel->setActiveSheetIndex(0);

        $cols = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //设置www.jb51.net标题
        for ($i = 0, $length = count($title); $i < $length; $i++) {
            //echo $cols{$i}.'1';
            $objPHPExcel->getActiveSheet()->setCellValue($cols{$i} . '1', $title[$i]);
        }
        //设置标题样式
        $titleCount = count($title);
        $r = $cols{0} . '1';
        $c = $cols{$titleCount} . '1';
        $objPHPExcel->getActiveSheet()->getStyle("$r:$c")->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0'
                        ),
                        'endcolor' => array(
                            'argb' => 'FFFFFFFF'
                        )
                    )
                )
        );

        $i = 0;
        foreach ($data as $d) { //这里用foreach,支持关联数组和数字索引数组
            $j = 0;
            foreach ($d as $v) {  //这里用foreach,支持关联数组和数字索引数组
                $objPHPExcel->getActiveSheet()->setCellValue($cols{$j} . ($i + 2), $v);
                $j++;
            }
            $i++;
        }
        $basename = basename($filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if ($type == 'download') {
//          生成2003excel格式的xls文件
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $basename);
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        } else {
            $path = dirname($filename);
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $objWriter->save($filename);
        }
    }

}
