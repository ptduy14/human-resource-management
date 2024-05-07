<?php 
	
	// PHPExcel
  	include('Classes/PHPExcel.php');
  	// connect database
  	require_once('../config.php');

  	// export file excel
  	$objExcel = new PHPExcel;
  	$objExcel->setActiveSheetIndex(0);
  	$sheet = $objExcel->getActiveSheet()->setTitle('Bảng lương');
  	// dinh dang file excel
  	// - dinh dang cho du kich thuoc noi dung
  	$sheet->getColumnDimension("A")->setAutoSize(true);
  	$sheet->getColumnDimension("B")->setAutoSize(true);
  	$sheet->getColumnDimension("C")->setAutoSize(true);
  	$sheet->getColumnDimension("D")->setAutoSize(true);
  	$sheet->getColumnDimension("E")->setAutoSize(true);
  	$sheet->getColumnDimension("F")->setAutoSize(true);
  	$sheet->getColumnDimension("G")->setAutoSize(true);
  	$sheet->getColumnDimension("H")->setAutoSize(true);
  	// chinh mau dong title
  	$sheet->getStyle('A1:H1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00ffff00');
  	// canh giua
  	$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	// dem so dong
  	$rowCount = 1;
  	// set cho dong dau tien (dong tieu de)
  	$sheet->setCellValue('A' . $rowCount, 'STT');
  	$sheet->setCellValue('B' . $rowCount, 'Mã lương');
  	$sheet->setCellValue('C' . $rowCount, 'Tên nhân viên');
  	$sheet->setCellValue('D' . $rowCount, 'Chức vụ');
  	$sheet->setCellValue('E' . $rowCount, 'Lương tháng');
  	$sheet->setCellValue('F' . $rowCount, 'Ngày công');
  	$sheet->setCellValue('G' . $rowCount, 'Thực lãnh');
  	$sheet->setCellValue('H' . $rowCount, 'Ngày chấm công');

  	// do du lieu tu db
  	$sql = "SELECT ma_luong, hinh_anh, nv.id as idNhanVien, ten_nv, ten_chuc_vu, luong_thang, ngay_cong, phu_cap, khoan_nop, tam_ung, thuc_lanh, ngay_cham FROM luong l, nhanvien nv, chuc_vu cv WHERE nv.id = l.nhanvien_id AND nv.chuc_vu_id = cv.id";
  	$result = mysqli_query($conn, $sql);
  	$stt = 0;
  	while ($row = mysqli_fetch_array($result)) 
  	{
  		// do du lieu tang len theo cac cot
  		$rowCount++;
  		$stt++;
  		// do het du lieu ra cac dong
  		$sheet->setCellValue('A' . $rowCount, $stt);
	  	$sheet->setCellValue('B' . $rowCount, $row['ma_luong']);
	  	$sheet->setCellValue('C' . $rowCount, $row['ten_nv']);
	  	$sheet->setCellValue('D' . $rowCount, $row['ten_chuc_vu']);
	  	$sheet->setCellValue('E' . $rowCount, number_format($row['luong_thang'])."vnđ");
	  	$sheet->setCellValue('F' . $rowCount, $row['ngay_cong']);
	  	$sheet->setCellValue('G' . $rowCount, number_format($row['thuc_lanh'])."vnđ");
	  	$sheet->setCellValue('H' . $rowCount, $row['ngay_cham']);
  	}

  	// tao border
  	$styleArray = array(
  		'borders' => array(
  			'allborders' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN
  			)
  		)
  	);
  	$sheet->getStyle('A1:' . 'H'.($rowCount))->applyFromArray($styleArray);

  	// tao tac xuat file
  	$objWriter = new PHPExcel_Writer_Excel2007($objExcel);
  	$filename = 'bang-luong.xlsx';
  	$objWriter->save($filename);

  	// cau hinh khi xuat file
  	header('Content-Disposition: attachment; filename="' .$filename. '"'); // tra ve file kieu attachment
  	header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
  	header('Content-Legth: ' . filesize($filename));
  	header('Content-Transfer-Encoding: binary');
  	header('Cache-Control: must-revalidate');
  	header('Pragma: no-cache');
  	readfile($filename);
  	return;

?>