<?php 

// connect database
require_once('../config.php');

  	if(isset($_POST["idNhanVien"]) && isset($_POST["soNgayCong"]))
  	{
  		$idNhanVien = $_POST['idNhanVien'];
  		$soNgayCong = $_POST['soNgayCong'];

  		// lay chuc vu de kiem tra phu cap
  		$phuCap = "SELECT ma_chuc_vu, ten_chuc_vu FROM nhanvien nv, chuc_vu cv WHERE nv.chuc_vu_id = cv.id AND nv.id = $idNhanVien";
  		$resultPhuCap = mysqli_query($conn, $phuCap);
  		$rowPhuCap = mysqli_fetch_array($resultPhuCap);
  		$maChucVu = $rowPhuCap['ma_chuc_vu'];

  		if($maChucVu == 'MCV1569203773') // giam doc
  			$tongPhuCap = 1000000 + ($soNgayCong * 45000);
  		else if($maChucVu == 'MCV1569203762') // pho giam doc
  			$tongPhuCap = 800000 + ($soNgayCong * 45000);
  		else if($maChucVu == 'MCV1569985216' || $maChucVu == 'MCV1569985261') // TP, PP
  			$tongPhuCap = 500000 + ($soNgayCong * 45000);
  		else if($maChucVu == 'MCV1569204007') // nhan vien
  			// neu ngay cong lon hon 25 ngay 
  			if($soNgayCong > 25)
  				$tongPhuCap = 300000 + ($soNgayCong * 45000);
  			else
  				$tongPhuCap = 0;
  		else
  			$tongPhuCap = 0;

  		echo $tongPhuCap;
  	}
?>