<?php 

// create session
session_start();

if(isset($_SESSION['username']) && isset($_SESSION['level']))
{
  // include file
  include('../layouts/header.php');
  include('../layouts/topbar.php');
  include('../layouts/sidebar.php');

  if(isset($_GET['id']))
  {
    // get id
    $id = $_GET['id'];

    // hien thi record
    $kt = "SELECT * FROM khen_thuong_ky_luat WHERE ma_kt = '$id'";
    $resultKT = mysqli_query($conn, $kt); 
    $rowKT = mysqli_fetch_array($resultKT);

    // set value active
    $nvAC = "SELECT nv.id as idAC, ma_nv, ten_nv, lktkl.id as loai_id, ten_loai FROM khen_thuong_ky_luat ktkl, nhanvien nv, loai_khen_thuong_ky_luat lktkl WHERE nv.id = ktkl.nhanvien_id AND lktkl.id = ktkl.loai_kt_id AND  ma_kt = '$id'";
    $resultNVAC = mysqli_query($conn, $nvAC);
    $rowNVAC = mysqli_fetch_array($resultNVAC);
    $idNVAC = $rowNVAC['idAC'];
    $loaiAC = $rowNVAC['loai_id'];

    // hien thi loai KỶ LUẬT
    $showData = "SELECT id, ma_loai, ten_loai FROM loai_khen_thuong_ky_luat WHERE id <> $loaiAC AND flag = 0 ORDER BY ngay_tao DESC";
    $result = mysqli_query($conn, $showData);
    $arrShow = array();
    while ($row = mysqli_fetch_array($result)) {
      $arrShow[] = $row;
    }

    // hien thi nhan vien
    $nv = "SELECT id, ma_nv, ten_nv FROM nhanvien WHERE id <> $idNVAC";
    $resultNV = mysqli_query($conn, $nv);
    $arrNV = array();
    while ($rowNV = mysqli_fetch_array($resultNV)) {
      $arrNV[] = $rowNV;
    }

    // them khen thuong
    if(isset($_POST['save']))
    {
      // create array error
      $error = array();
      $success = array();
      $showMess = false;

      // get id in form
      $soQuyetDinh = $_POST['soQuyetDinh'];
      $ngayQuyetDinh = $_POST['ngayQuyetDinh'];
      $tenKyLuat = $_POST['tenKyLuat'];
      $nhanVien = $_POST['nhanVien'];
      $loaiKyLuat = $_POST['loaiKyLuat'];
      $hinhThuc = $_POST['hinhThuc'];
      $soTienPhat = $_POST['soTienPhat'];
      $moTa = $_POST['moTa'];
      $nguoiTao = $_POST['nguoiTao'];
      $ngayTao = date("Y-m-d H:i:s");
      $flag = 1;


      // validate
      if(empty($soQuyetDinh))
        $error['soQuyetDinh'] = 'Vui lòng nhập <b> số quyết định </b>';
      if($nhanVien == 'chon')
        $error['nhanVien'] = 'Vui lòng chọn <b> nhân viên </b>';
      if($loaiKyLuat== 'chon')
        $error['loaiKyLuat'] = 'Vui lòng chọn <b> loại kỷ luật </b>';
      if($hinhThuc == 'chon')
        $error['hinhThuc'] = 'Vui lòng chọn <b> hình thức </b>';
      if(empty($soTienPhat))
        $error['soTienPhat'] = 'Vui lòng nhập <b> số tiền phạt </b>';

      if(!$error)
      {
        $showMess = true;
        $update = " UPDATE khen_thuong_ky_luat SET
                    so_qd = '$soQuyetDinh',
                    ngay_qd = '$ngayQuyetDinh',
                    nhanvien_id = '$nhanVien',
                    ten_khen_thuong = '$tenKyLuat',
                    loai_kt_id = '$loaiKyLuat',
                    hinh_thuc = '$hinhThuc',
                    so_tien = '$soTienPhat',
                    ghi_chu = '$moTa',
                    nguoi_sua = '$nguoiTao',
                    ngay_sua = '$ngayTao'
                    WHERE ma_kt = '$id'";
        $result = mysqli_query($conn, $update);
        if($result)
        {
          $success['success'] = 'Lưu lại thành công';
          echo '<script>setTimeout("window.location=\'sua-ky-luat.php?p=bonus-discipline&a=discipline&id='.$id.'\'",1000);</script>';
        }
      }
    }
  }

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Kỷ luật
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="ky-luat.php?p=bonus-discipline&a=discipline">Kỷ luật</a></li>
        <li class="active">Chỉnh sửa kỷ luật</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Chỉnh sửa kỷ luật</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php 
                // show error
                if($row_acc['quyen'] != 1) 
                {
                  echo "<div class='alert alert-warning alert-dismissible'>";
                  echo "<h4><i class='icon fa fa-ban'></i> Thông báo!</h4>";
                  echo "Bạn <b> không có quyền </b> thực hiện chức năng này.";
                  echo "</div>";
                }
              ?>

              <?php 
                // show error
                if(isset($error)) 
                {
                  if($showMess == false)
                  {
                    echo "<div class='alert alert-danger alert-dismissible'>";
                    echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    echo "<h4><i class='icon fa fa-ban'></i> Lỗi!</h4>";
                    foreach ($error as $err) 
                    {
                      echo $err . "<br/>";
                    }
                    echo "</div>";
                  }
                }
              ?>
              <?php 
                // show success
                if(isset($success)) 
                {
                  if($showMess == true)
                  {
                    echo "<div class='alert alert-success alert-dismissible'>";
                    echo "<h4><i class='icon fa fa-check'></i> Thành công!</h4>";
                    foreach ($success as $suc) 
                    {
                      echo $suc . "<br/>";
                    }
                    echo "</div>";
                  }
                }
              ?>
              <form action="" method="POST">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mã  kỷ luật: </label>
                      <input type="text" class="form-control" name="maKyLuat" value="<?php echo $id; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Số quyết định: <span style="color: red;">*</span>: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập số quyết định" name="soQuyetDinh" value="<?php echo $rowKT['so_qd']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Ngày quyết định: </label>
                      <input type="date" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên loại" value="<?php echo date_format(date_create($rowKT['ngay_qd']), 'Y-m-d'); ?>" name="ngayQuyetDinh">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tên kỷ luật: <span style="color: red;">*</span>: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên kỷ luật" name="tenKyLuat" value="<?php echo $rowKT['ten_khen_thuong']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Chọn nhân viên: </label>
                      <select class="form-control" name="nhanVien">
                      <option value="<?php echo $rowNVAC['idAC']; ?>"><?php echo $rowNVAC['ma_nv']; ?> - <?php echo $rowNVAC['ten_nv']; ?></option>
                      <?php 
                        foreach($arrNV as $nv)
                        {
                          echo "<option value='".$nv['id']."'>".$nv['ma_nv']." - ".$nv['ten_nv']."</option>";
                        }
                      ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Loại kỷ luật: </label>
                      <select class="form-control" name="loaiKyLuat">
                      <option value="<?php echo $rowNVAC['loai_id']; ?>"><?php echo $rowNVAC['ten_loai']; ?></option>
                      <?php 
                        foreach($arrShow as $arrS)
                        {
                          echo "<option value='".$arrS['id']."'>".$arrS['ten_loai']."</option>";
                        }
                      ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Hình thức: </label>
                      <select class="form-control" name="hinhThuc">
                      <?php 
                        if($rowKT['hinh_thuc'] == 1)
                        {
                          echo "<option value='1' selected>Trừ tiền qua thẻ</option>";
                          echo "<option value='0'>Trừ tiền mặt</option>";
                        }
                        else
                        {
                          echo "<option value='1'>Trừ tiền qua thẻ</option>";
                          echo "<option value='0' selected>Trừ tiền mặt</option>";
                        }
                      ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Số tiền phạt <span style="color: red;">*</span>: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập số tiền thưởng" name="soTienPhat" value="<?php echo $rowKT['so_tien']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mô tả: </label>
                      <textarea class="form-control" id="editor1" name="moTa"><?php echo $rowKT['ghi_chu']; ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Người tạo: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row_acc['ho']; ?> <?php echo $row_acc['ten']; ?>" name="nguoiTao" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Ngày tạo: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo date('d-m-Y H:i:s'); ?>" name="ngayTao" readonly>
                    </div>
                    <!-- /.form-group -->
                    <?php 
                      if($_SESSION['level'] == 1)
                        echo "<button type='submit' class='btn btn-warning' name='save'><i class='fa fa-save'></i> Lưu lại</button>";
                    ?>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

<?php
  // include
  include('../layouts/footer.php');
}
else
{
  // go to pages login
  header('Location: dang-nhap.php');
}

?>