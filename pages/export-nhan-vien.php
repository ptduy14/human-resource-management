<?php 
	
	// PHPExcel
  	include('Classes/PHPExcel.php');
  	// connect database
  	require_once('../config.php');

  	// export file excel
  	$objExcel = new PHPExcel;
  	$objExcel->setActiveSheetIndex(0);
  	$sheet = $objExcel->getActiveSheet()->setTitle('Bảng nhân viên');
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
    $sheet->getColumnDimension("I")->setAutoSize(true);
    $sheet->getColumnDimension("J")->setAutoSize(true);
    $sheet->getColumnDimension("K")->setAutoSize(true);
    $sheet->getColumnDimension("L")->setAutoSize(true);
    $sheet->getColumnDimension("M")->setAutoSize(true);
    $sheet->getColumnDimension("N")->setAutoSize(true);
    $sheet->getColumnDimension("O")->setAutoSize(true);
    $sheet->getColumnDimension("P")->setAutoSize(true);
    $sheet->getColumnDimension("Q")->setAutoSize(true);
    $sheet->getColumnDimension("R")->setAutoSize(true);
    $sheet->getColumnDimension("S")->setAutoSize(true);
    $sheet->getColumnDimension("T")->setAutoSize(true);
    $sheet->getColumnDimension("U")->setAutoSize(true);
    $sheet->getColumnDimension("V")->setAutoSize(true);
    $sheet->getColumnDimension("W")->setAutoSize(true);
  	// chinh mau dong title
  	$sheet->getStyle('A1:W1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00ffff00');
  	// canh giua
  	$sheet->getStyle('A1:W1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	// dem so dong
  	$rowCount = 1;
  	// set cho dong dau tien (dong tieu de)
  	$sheet->setCellValue('A' . $rowCount, 'STT');
  	$sheet->setCellValue('B' . $rowCount, 'Mã nhân viên');
  	$sheet->setCellValue('C' . $rowCount, 'Tên nhân viên');
  	$sheet->setCellValue('D' . $rowCount, 'Giới tính');
  	$sheet->setCellValue('E' . $rowCount, 'Ngày sinh');
  	$sheet->setCellValue('F' . $rowCount, 'Nơi sinh');
  	$sheet->setCellValue('G' . $rowCount, 'Tình trạng hôn nhân');
  	$sheet->setCellValue('H' . $rowCount, 'Số CMND');
    $sheet->setCellValue('I' . $rowCount, 'Ngày cấp');
    $sheet->setCellValue('J' . $rowCount, 'Nơi cấp');
    $sheet->setCellValue('K' . $rowCount, 'Nguyên quán');
    $sheet->setCellValue('L' . $rowCount, 'Quốc tịch');
    $sheet->setCellValue('M' . $rowCount, 'Dân tộc');
    $sheet->setCellValue('N' . $rowCount, 'Tôn giáo');
    $sheet->setCellValue('O' . $rowCount, 'Hộ khẩu');
    $sheet->setCellValue('P' . $rowCount, 'Tạm trú');
    $sheet->setCellValue('Q' . $rowCount, 'Loại nhân viên');
    $sheet->setCellValue('R' . $rowCount, 'Trình độ');
    $sheet->setCellValue('S' . $rowCount, 'Chuyên môn');
    $sheet->setCellValue('T' . $rowCount, 'Bằng cấp');
    $sheet->setCellValue('U' . $rowCount, 'Phòng ban');
    $sheet->setCellValue('V' . $rowCount, 'Chức vụ');
    $sheet->setCellValue('W' . $rowCount, 'Trạng thái');

  	// do du lieu tu db
  	$sql = "SELECT nv.id as id, ma_nv, hinh_anh, ten_nv, biet_danh, gioi_tinh, nv.ngay_tao as ngay_tao, ngay_sinh, noi_sinh, so_cmnd, ten_tinh_trang, ngay_cap_cmnd, noi_cap_cmnd, nguyen_quan, ten_quoc_tich, ten_dan_toc, ten_ton_giao, ho_khau, tam_tru, ten_loai_nv, ten_trinh_do, ten_chuyen_mon, ten_bang_cap, ten_phong_ban, ten_chuc_vu, trang_thai FROM nhanvien nv, quoc_tich qt, dan_toc dt, ton_giao tg, loai_nv lnv, trinh_do td, chuyen_mon cm, bang_cap bc, phong_ban pb, chuc_vu cv, tinh_trang_hon_nhan hn WHERE nv.quoc_tich_id = qt.id AND nv.dan_toc_id = dt.id AND nv.ton_giao_id = tg.id AND nv.loai_nv_id = lnv.id AND nv.trinh_do_id = td.id AND nv.chuyen_mon_id = cm.id AND nv.bang_cap_id = bc.id AND nv.phong_ban_id = pb.id AND nv.chuc_vu_id = cv.id AND nv.hon_nhan_id = hn.id ORDER BY nv.id DESC";
  	$result = mysqli_query($conn, $sql);
  	$stt = 0;
  	while ($row = mysqli_fetch_array($result)) 
  	{
  		// do du lieu tang len theo cac cot
  		$rowCount++;
  		$stt++;

      // cau hinh lai cac truong
      if($row['gioi_tinh'] == 1)
      {
        $gioiTinh = 'Nam';
      }
      else
      {
        $gioiTinh = 'Nữ';
      }

      if($row['trang_thai'] == 1)
      {
        $trangThai = 'Đang làm việc';
      }
      else
      {
        $trangThai = 'Đã nghỉ việc';
      }

  		// do het du lieu ra cac dong
  		$sheet->setCellValue('A' . $rowCount, $stt);
	  	$sheet->setCellValue('B' . $rowCount, $row['ma_nv']);
	  	$sheet->setCellValue('C' . $rowCount, $row['ten_nv']);
	  	$sheet->setCellValue('D' . $rowCount, $gioiTinh);
	  	$sheet->setCellValue('E' . $rowCount, date_format(date_create($row['ngay_sinh']), 'd/m/Y'));
	  	$sheet->setCellValue('F' . $rowCount, $row['noi_sinh']);
	  	$sheet->setCellValue('G' . $rowCount, $row['ten_tinh_trang']);
	  	$sheet->setCellValue('H' . $rowCount, $row['so_cmnd']);
      $sheet->setCellValue('I' . $rowCount, date_format(date_create($row['ngay_cap_cmnd']), 'd/m/Y'));
      $sheet->setCellValue('J' . $rowCount, $row['noi_cap_cmnd']);
      $sheet->setCellValue('K' . $rowCount, $row['nguyen_quan']);
      $sheet->setCellValue('L' . $rowCount, $row['ten_quoc_tich']);
      $sheet->setCellValue('M' . $rowCount, $row['ten_dan_toc']);
      $sheet->setCellValue('N' . $rowCount, $row['ten_ton_giao']);
      $sheet->setCellValue('O' . $rowCount, $row['ho_khau']);
      $sheet->setCellValue('P' . $rowCount, $row['tam_tru']);
      $sheet->setCellValue('Q' . $rowCount, $row['ten_loai_nv']);
      $sheet->setCellValue('R' . $rowCount, $row['ten_trinh_do']);
      $sheet->setCellValue('S' . $rowCount, $row['ten_chuyen_mon']);
      $sheet->setCellValue('T' . $rowCount, $row['ten_bang_cap']);
      $sheet->setCellValue('U' . $rowCount, $row['ten_phong_ban']);
      $sheet->setCellValue('V' . $rowCount, $row['ten_chuc_vu']);
      $sheet->setCellValue('W' . $rowCount, $trangThai);
  	}

  	// tao border
  	$styleArray = array(
  		'borders' => array(
  			'allborders' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN
  			)
  		)
  	);
  	$sheet->getStyle('A1:' . 'W'.($rowCount))->applyFromArray($styleArray);

  	// tao tac xuat file
  	$objWriter = new PHPExcel_Writer_Excel2007($objExcel);
  	$filename = 'nhan-vien.xlsx';
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