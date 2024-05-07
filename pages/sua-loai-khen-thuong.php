<?php 

// create session
session_start();

if(isset($_SESSION['username']) && isset($_SESSION['level']))
{
  // include file
  include('../layouts/header.php');
  include('../layouts/topbar.php');
  include('../layouts/sidebar.php');

  // show data
  if(isset($_GET['id']))
  {
    $id = $_GET['id'];
    $showData = "SELECT id, ma_loai, ten_loai, ghi_chu, nguoi_sua, ngay_sua FROM loai_khen_thuong_ky_luat WHERE id = $id";
    $result = mysqli_query($conn, $showData);
    $row = mysqli_fetch_array($result);
    $nguoiSua = $row_acc['ho'] . " " . $row_acc['ten'];
  }

  // save record
  if(isset($_POST['save']))
  {
    // create array error
    $error = array();
    $success = array();
    $showMess = false;

    // get id in form
    $tenLoai = $_POST['tenLoai'];
    $moTa = $_POST['moTa'];
    $ngaySua = date("Y-m-d H:i:s");

    // validate
    if(empty($tenLoai))
      $error['tenLoai'] = 'Vui lòng nhập <b> tên loại </b>';

    if(!$error)
    {
      $showMess = true;
      $update = " UPDATE loai_khen_thuong_ky_luat SET
                  ten_loai = '$tenLoai',
                  ghi_chu = '$moTa',
                  nguoi_sua = '$nguoiSua',
                  ngay_sua = '$ngaySua'
                  WHERE id = $id";
      mysqli_query($conn, $update);
      $success['success'] = 'Lưu lại thành công.';
      echo '<script>setTimeout("window.location=\'sua-loai-khen-thuong.php?p=bonus-discipline&a=bonus&id='.$id.'\'",1000);</script>';
    }

  }

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Khen thưởng
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="khen-thuong.php?p=bonus-discipline&a=bonus">Khen thưởng</a></li>
        <li class="active">Khen thưởng nhân viên</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Chỉnh sửa loại khen thưởng</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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
                      <input type="text" class="form-control" id="exampleInputEmail1" name="maLoai" value="<?php echo $row['ma_loai']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tên loại: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên loại" value="<?php echo $row['ten_loai']; ?>" name="tenLoai">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mô tả: </label>
                      <textarea id="editor1" rows="10" cols="80" name="moTa"><?php echo $row['ghi_chu']; ?>
                      </textarea>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Người sửa: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $nguoiSua; ?> " name="nguoiSua" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Ngày sửa: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo date('d-m-Y H:i:s'); ?>" name="ngaySua" readonly>
                    </div>
                    <!-- /.form-group -->
                    <button type="submit" class="btn btn-warning" name="save"><i class="fa fa-save"></i> Lưu lại </button>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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