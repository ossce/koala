<?php
/**
 * PHPExcel方法
 * ============================================================================
 * 版权所有 2015-2020 风影科技，并保留所有权利。
 * 网站地址: https://www.fylesson.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件，未购买授权用户无论是否用于商业行为都是侵权行为！
 * 允许已购买用户对程序代码进行修改并在授权域名下使用，但是不允许对程序代码以
 * 任何形式任何目的进行二次发售，作者将依法保留追究法律责任的权力和最终解释权。
 * ============================================================================
 */

require_once dirname(__FILE__).'/../../library/phpexcel/PHPExcel.php';

class FyLessonv2PHPExcel{
	
	/**
	 * $title	 标题
	 * $arr	     数据
	 * $filename 文件名
	 * $savetype 文件处理方法 0.直接下载 1.保存文件
	 */
	public function exportTable($title, $arr, $filename, $savetype='0'){
		$objPHPExcel = new PHPExcel();

		//设置当前的表格 
		$objPHPExcel->setActiveSheetIndex(0);
		
		// 设置表格第一行显示内容
		$title_arr = $this->setTableTitle($title);
		foreach($title_arr as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($value['letter'], $value['name']);
		}

		$letter_arr = $this->getLetterArr();
		$objPHPExcel->getActiveSheet()->getStyle($letter_arr[0].'1:'.$letter_arr[count($title)-1].'1')->applyFromArray(
            array(
                'font' => array (
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                )
            )
        );

		/*以下就是对处理Excel里的数据，横着取数据*/
		$key = 1;
		foreach($arr as $key=>$val){
			$key++;
			$i = 0;
			foreach($val as $cv){
				$objPHPExcel->getActiveSheet()->setCellValue($letter_arr[$i].($key+1), $cv);
				$i++;
			}
		}

		//设置当前的表格 
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		//设置excel文件临时存储目录
		$dirpath = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/data/excel/';
		if (!file_exists($dirpath)) {
			mkdir($dirpath, 0777);
		}

		if($savetype){
			$dirname = $dirpath.$filename.'-'.date('Ymd').'.xls';
			$objWriter->save($dirname);
		}else{
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'-'.date('Ymd').'.xls"');
			header('Cache-Control: max-age=0');
			header('Content-Type: text/html; charset=utf-8');
			$objWriter->save('php://output');
		}
	}

	/**
	 * 上传excel文件
	 * $newfile  文件完整路径名称
	 */
	 public function inputExcel($newfile) {
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load($newfile, $encode='utf-8');
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		$data=[];
		$arr = $this->getLetterArr();
		for ($row = 2; $row <= $highestRow; $row++) {
			for ($column = 0; $arr[$column] != $highestColumn; $column++) {
				$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
				if($val instanceof PHPExcel_RichText) {
					$val = $val->__toString();
				}
				$data[$row][] = $val;
			}
		}

		return $data;
    }


	/**
	 * 设置表格的第一行标题
	 */
	 private function setTableTitle($title){
		 $letter_arr = $this->getLetterArr();
		 $count = count($title);

		 $title_arr = array();
		 foreach($letter_arr as $key=>$value){
			$title_arr[$key]['letter'] = $letter_arr[$key].'1';
			$title_arr[$key]['name']   = $title[$key];

			if($key < $count-1){
				continue;
			}else{
				break;
			}
		 }

		 return $title_arr;
	 }

	 /**
	  * 获取A~Z数组
 	  */
	 private function getLetterArr(){
		 $letter_arr = array('A','B','C','D','E','F','G','H','I','J','K',
							 'L','M','N','O','P','Q','R','S','T','U','V',
							 'W','X','Y','Z','AA','AB','AC','AD','AE','AF',
							 'AG','AH','AI','AJ','AK','AL','AM','AN');
		 return $letter_arr;
	 }
}


