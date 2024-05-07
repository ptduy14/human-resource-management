<?php 

// create session
session_start();

if(isset($_SESSION['username']) && isset($_SESSION['level']))
{
  // include file
  include('../layouts/header.php');
  include('../layouts/topbar.php');
  include('../layouts/sidebar.php');

  if(isset($_POST['suaLoai']))
  {
    $id = $_POST['idLoai'];
    echo "<script>location.href='sua-loai-ky-luat.php?p=bonus-discipline&a=discipline&id=".$id."'</script>";
  }

  if(isset($_POST['suaKyLuat']))
  {
    $id = $_POST['idKyLuat'];
    echo "<script>location.href='sua-ky-luat.php?p=bonus-discipline&a=discipline&id=".$id."'</script>";
  }

  // hien thi loai kỷ luật
  $showData = "SELECT id, ma_loai, ten_loai, ghi_chu, nguoi_tao, ngay_tao, nguoi_sua, ngay_sua FROM loai_khen_thuong_ky_luat WHERE flag = 0 ORDER BY ngay_tao DESC";
  $result = mysqli_query($conn, $showData);
  $arrShow = array();
  while ($row = mysqli_fetch_array($result)) {
    $arrShow[] = $row;
  }

  // hien thi ky luat
  $kt = "SELECT ktkl.id as id, ma_kt, ten_khen_thuong, ten_nv, so_qd, ngay_qd, ten_loai, hinh_thuc, so_tien, ktkl.ngay_tao as ngay_tao FROM khen_thuong_ky_luat ktkl, nhanvien nv, loai_khen_thuong_ky_luat lktkl WHERE ktkl.nhanvien_id = nv.id AND ktkl.loai_kt_id = lktkl.id AND ktkl.flag = 0 ORDER BY ktkl.ngay_tao DESC";
  $resultKT = mysqli_query($conn, $kt);
  $arrKT = array();
  while ($rowKT = mysqli_fetch_array($resultKT)) {
    $arrKT[] = $rowKT;
  }

  // hien thi nhan vien
  $nv = "SELECT id, ma_nv, ten_nv FROM nhanvien ORDER BY id DESC";
  $resultNV = mysqli_query($conn, $nv);
  $arrNV = array();
  while ($rowNV = mysqli_fetch_array($resultNV)) {
    $arrNV[] = $rowNV;
  }

  // create code 
  $maLoai = "LKL" . time();
  $maKyLuat = "MKL" . time();

  // them loai khen thuong
  if(isset($_POST['taoLoai']))
  {
    // create array error
    $error = array();
    $success = array();
    $showMess = false;

    // get id in form
    $tenLoai = $_POST['tenLoai'];
    $moTa = $_POST['moTa'];
    $flag = 0;
    $nguoiTao = $_POST['nguoiTao'];
    $ngayTao = date("Y-m-d H:i:s");

    // validate
    if(empty($tenLoai))
      $error['tenLoai'] = 'Vui lòng nhập <b> tên loại </b>';

    if(!$error)
    {
      $showMess = true;
      $insert = "INSERT INTO loai_khen_thuong_ky_luat(ma_loai, ten_loai, ghi_chu, flag, nguoi_tao, ngay_tao, nguoi_sua, ngay_sua) VALUES('$maLoai','$tenLoai','$moTa', '$flag', '$nguoiTao', '$ngayTao', '$nguoiTao', '$ngayTao')";
      $result = mysqli_query($conn, $insert);
      if($result)
      {
        $success['success'] = 'Tạo loại kỷ luật thành công';
        echo '<script>setTimeout("window.location=\'ky-luat.php?p=bonus-discipline&a=discipline&tao-loai\'",1000);</script>';
      }
    }
  }

  // them khen thuong
  if(isset($_POST['taoKyLuat']))
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
    $loaiKyLuat= $_POST['loaiKyLuat'];
    $hinhThuc = $_POST['hinhThuc'];
    $soTienPhat = $_POST['soTienPhat'];
    $moTa = $_POST['moTa'];
    $nguoiTao = $_POST['nguoiTao'];
    $ngayTao = date("Y-m-d H:i:s");
    $flag = 0;


    // validate
    if(empty($soQuyetDinh))
      $error['soQuyetDinh'] = 'Vui lòng nhập <b> số quyết định </b>';
    if($nhanVien == 'chon')
      $error['nhanVien'] = 'Vui lòng chọn <b> nhân viên </b>';
    if($loaiKhenThuong == 'chon')
      $error['loaiKyLuat'] = 'Vui lòng chọn <b> loại kỷ luật </b>';
    if($hinhThuc == 'chon')
      $error['hinhThuc'] = 'Vui lòng chọn <b> hình thức </b>';
    if(empty($soTienPhat))
      $error['soTienPhat'] = 'Vui lòng nhập <b> số tiền phạt </b>';

    if(!$error)
    {
      $showMess = true;
      $insert = "INSERT INTO khen_thuong_ky_luat(ma_kt, so_qd, ngay_qd, nhanvien_id, ten_khen_thuong, loai_kt_id, hinh_thuc, so_tien, flag, ghi_chu, nguoi_tao, ngay_tao, nguoi_sua, ngay_sua) VALUES('$maKyLuat', '$soQuyetDinh', '$ngayQuyetDinh', '$nhanVien', '$tenKyLuat', '$loaiKyLuat', '$hinhThuc', '$soTienPhat', '$flag', '$moTa', '$nguoiTao', '$ngayTao', '$nguoiTao', '$ngayTao')";
      $result = mysqli_query($conn, $insert);
      if($result)
      {
        $success['success'] = 'Kỷ luật thành công';
        echo '<script>setTimeout("window.location=\'ky-luat.php?p=bonus-discipline&a=discipline&ky-luat\'",1000);</script>';
      }
    }
  }

  // delete record
  if(isset($_POST['delete']))
  {
    $showMess = true;
    $id = $_POST['id'];

    // chon xoa bang nao?
    $table = substr($id, 0, 3);

    // neu xoa loai khen thuong
    if($table == 'LKL')
    {
      $delete = "DELETE FROM loai_khen_thuong_ky_luat WHERE ma_loai = '$id'";
      mysqli_query($conn, $delete);
      $success['success'] = 'Xóa loại kỷ luật thành công.';
      echo '<script>setTimeout("window.location=\'ky-luat.php?p=bonus-discipline&a=discipline&tao-loai\'",1000);</script>';
    }
    
    // neu xoa khen thuong
    if($table == 'MKL')
    {
      $delete = "DELETE FROM khen_thuong_ky_luat WHERE ma_kt = '$id'";
      mysqli_query($conn, $delete);
      $success['success'] = 'Xóa kỷ luật thành công.';
      echo '<script>setTimeout("window.location=\'ky-luat.php?p=bonus-discipline&a=discipline&ky-luat\'",1000);</script>';
    }

  }

?>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <span style="font-size: 18px;">Thông báo</span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id">
            Bạn có thực sự muốn xóa <?php if(isset($_GET['tao-loai'])){ echo "loại kỷ luật"; }else{ echo "kỷ luật"; } ?> này?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy bỏ</button>
            <button type="submit" class="btn btn-primary" name="delete">Xóa</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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
        <li class="active">Kỷ luật nhân viên</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Thao tác chức năng</h3>
            </div>
            <div class="box-body">
              <a  href="ky-luat.php?p=bonus-discipline&a=discipline&tao-loai" class="btn btn-app">
                <i class="fa fa-plus"></i> Loại kỷ luật
              </a>
              <a href="ky-luat.php?p=bonus-discipline&a=discipline&ky-luat" class="btn btn-app">
                <i class="fa fa-user"></i> Kỷ luật nhân viên
              </a>
              <?php 
                if(isset($_GET['tao-loai']) || isset($_GET['ky-luat']))
                  echo "<a href='ky-luat.php?p=bonus-discipline&a=discipline' class='btn btn-app'>
                          <i class='fa fa-close'></i> Hủy bỏ
                        </a>";
              ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- box -->
          <?php 
          if(isset($_GET['tao-loai']))
          {
          ?>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Tạo loại kỷ luật</h3>
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
                      <label for="exampleInputEmail1">Mã loại: </label>
                      <input type="text" class="form-control" name="speacialCode" value="<?php echo $maLoai; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tên loại: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên loại" name="tenLoai">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mô tả: </label>
                      <textarea id="editor1" rows="10" cols="80" name="moTa">
                      </textarea>
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
                        echo "<button type='submit' class='btn btn-primary' name='taoLoai'><i class='fa fa-plus'></i> Tạo loại kỷ luật</button>";
                    ?>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <?php 
          }
          ?>
          <!-- /.box -->
          <?php 
          if(isset($_GET['ky-luat']))
          {
          ?>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Kỷ luật nhân viên</h3>
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
                      <label for="exampleInputEmail1">Mã kỷ luật: </label>
                      <input type="text" class="form-control" name="maKyLuat" value="<?php echo $maKyLuat; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Số quyết định <span style="color: red;">*</span>: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập số quyết định" name="soQuyetDinh" value="<?php echo isset($_POST['soQuyetDinh']) ? $_POST['soQuyetDinh'] : ''; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Ngày quyết định: </label>
                      <input type="date" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên loại" value="<?php echo date('Y-m-d'); ?>" name="ngayQuyetDinh">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tên kỷ luật <span style="color: red;">*</span>: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên kỷ luật" name="tenKyLuat">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Chọn nhân viên: </label>
                      <select class="form-control" name="nhanVien">
                      <option value="chon">--- Chọn nhân viên ---</option>
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
                      <option value="chon">--- Chọn kỷ luật ---</option>
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
                        <option value="chon">--- Chọn hình thức ---</option>
                        <option value="1">Trừ tiền qua thẻ</option>
                        <option value="0">Trừ tiền mặt</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Số tiền phạt: <span style="color: red;">*</span>: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập số tiền thưởng" name="soTienPhat" value="<?php echo isset($_POST['soTienPhat']) ? $_POST['soTienPhat'] : ''; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mô tả: </label>
                      <textarea class="form-control" id="editor1" name="moTa"></textarea>
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
                        echo "<button type='submit' class='btn btn-primary' name='taoKyLuat'><i class='fa fa-check'></i> Tiến hành kỷ luật</button>";
                    ?>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <?php 
          }
          ?>
          <!-- Bảng loại kỷ luật -->
          <?php 
          if(isset($_GET['tao-loai']))
          {
          ?>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Danh sách loại kỷ luật</h3>
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
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã loại</th>
                    <th>Tên loại</th>
                    <th>Mô tả</th>
                    <th>Người tạo</th>
                    <th>Ngày tạo</th>
                    <th>Người sửa</th>
                    <th>Ngày sửa</th>
                    <th>Sửa</th>
                    <th>Xóa</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $count = 1;
                    foreach ($arrShow as $arrS) 
                    {
                  ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $arrS['ma_loai']; ?></td>
                        <td><?php echo $arrS['ten_loai']; ?></td>
                        <td><?php echo $arrS['ghi_chu']; ?></td>
                        <td><?php echo $arrS['nguoi_tao']; ?></td>
                        <td><?php echo $arrS['ngay_tao']; ?></td>
                        <td><?php echo $arrS['nguoi_sua']; ?></td>
                        <td><?php echo $arrS['ngay_sua']; ?></td>
                        <th>
                          <?php 
                            if($row_acc['quyen'] == 1)
                            {
                              echo "<form method='POST'>";
                              echo "<input type='hidden' value='".$arrS['id']."' name='idLoai'/>";
                              echo "<button type='submit' class='btn bg-orange btn-flat'  name='suaLoai'><i class='fa fa-edit'></i></button>";
                              echo "</form>";
                            }
                            else
                            {
                              echo "<button type='button' class='btn bg-orange btn-flat' disabled><i class='fa fa-edit'></i></button>";
                            }
                          ?>
                          
                        </th>
                        <th>
                          <?php 
                            if($row_acc['quyen'] == 1)
                            {
                              echo "<button type='button' class='btn bg-maroon btn-flat' data-toggle='modal' data-target='#exampleModal' data-whatever='".$arrS['ma_loai']."'><i class='fa fa-trash'></i></button>";
                            }
                            else
                            {
                              echo "<button type='button' class='btn bg-maroon btn-flat' disabled><i class='fa fa-trash'></i></button>";
                            }
                          ?>
                        </th>
                      </tr>
                  <?php
                      $count++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <?php 
          }
          ?>
          <!-- Bảng kỷ luật -->
          <?php 
          if(isset($_GET['ky-luat']))
          {
          ?>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Danh sách kỷ luật</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã kỷ luật</th>
                    <th>Tên kỷ luật</th>
                    <th>Tên nhân viên</th>
                    <th>Số quyết định</th>
                    <th>Ngày quyết định</th>
                    <th>Tên loại</th>
                    <th>Hình thức</th>
                    <th>Số tiền</th>
                    <th>Ngày kỷ luật</th>
                    <th>Sửa</th>
                    <th>Xóa</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $count = 1;
                    foreach ($arrKT as $kt) 
                    {
                  ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $kt['ma_kt']; ?></td>
                        <td><?php echo $kt['ten_khen_thuong']; ?></td>
                        <td><?php echo $kt['ten_nv']; ?></td>
                        <td><?php echo $kt['so_qd']; ?></td>
                        <td><?php echo date_format(date_create($kt['ngay_qd']), "d-m-Y"); ?></td>
                        <td><?php echo $kt['ten_loai']; ?></td>
                        <td>
                        <?php 
                          if($kt['hinh_thuc'] == 1)
                          {
                            echo "Trừ tiền qua thẻ";
                          }
                          else
                          {
                            echo "Trừ tiền mặt";
                          }
                        ?>
                        </td>
                        <td><?php echo "<span style='color: red; font-weight: bold;'>". number_format($kt['so_tien'])."vnđ </span>"; ?></td>
                        <td><?php echo date_format(date_create($kt['ngay_tao']), "d-m-Y"); ?></td>
                        <th>
                          <?php 
                            if($row_acc['quyen'] == 1)
                            {
                              echo "<form method='POST'>";
                              echo "<input type='hidden' value='".$kt['ma_kt']."' name='idKyLuat'/>";
                              echo "<button type='submit' class='btn bg-orange btn-flat'  name='suaKyLuat'><i class='fa fa-edit'></i></button>";
                              echo "</form>";
                            }
                            else
                            {
                              echo "<button type='button' class='btn bg-orange btn-flat' disabled><i class='fa fa-edit'></i></button>";
                            }
                          ?>
                          
                        </th>
                        <th>
                          <?php 
                            if($row_acc['quyen'] == 1)
                            {
                              echo "<button type='button' class='btn bg-maroon btn-flat' data-toggle='modal' data-target='#exampleModal' data-whatever='".$kt['ma_kt']."'><i class='fa fa-trash'></i></button>";
                            }
                            else
                            {
                              echo "<button type='button' class='btn bg-maroon btn-flat' disabled><i class='fa fa-trash'></i></button>";
                            }
                          ?>
                        </th>
                      </tr>
                  <?php
                      $count++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <?php 
          }
          ?>
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