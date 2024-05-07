<?php 

// create session
session_start();

if(isset($_SESSION['username']) && isset($_SESSION['level']))
{
  // include file
  include('../layouts/header.php');
  include('../layouts/topbar.php');
  include('../layouts/sidebar.php');

  // danh sach nhom
  $nhom = "SELECT ma_nhom, ten_nhom FROM nhom ORDER BY id DESC";
  $resultNhom = mysqli_query($conn, $nhom);
  $arrNhom = array();
  while($rowNhom = mysqli_fetch_array($resultNhom))
  {
    $arrNhom[] = $rowNhom;
  }

  // xoa nhom
  if(isset($_GET['id']))
  {
    $showMess = true;
    $ma_nhom = $_GET['id'];
    // xoa nhan vien trong nhom
    $xoaNV = "DELETE FROM chi_tiet_nhom WHERE ma_nhom = '$ma_nhom'";
    $resultXoaNV = mysqli_query($conn, $xoaNV);
    if($resultXoaNV)
    { 
      // xoa nhom
      $xoaNhom = "DELETE FROM nhom WHERE ma_nhom = '$ma_nhom'";
      $resultXoaNhom = mysqli_query($conn, $xoaNhom);
      if($resultXoaNhom)
      {
        $success['success'] = 'Xóa nhóm thành công';
        echo '<script>setTimeout("window.location=\'danh-sach-nhom.php?p=group&a=list-group\'",1000);</script>';
      }
    }
  }

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Quản lý nhóm
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
          <li class="active">Quản lý nhóm</li>
        </ol>
      </section>
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-lg-12">
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
          </div>
        </div>
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <?php 
          foreach ($arrNhom as $nh) 
          {
            $maNhom = $nh['ma_nhom'];
            // dem so luong thanh vien
            $soLuong = "SELECT count(nhan_vien_id) as soluong FROM chi_tiet_nhom WHERE ma_nhom = '$maNhom'";
            $resultSoLuong = mysqli_query($conn, $soLuong);
            $rowSoluong = mysqli_fetch_array($resultSoLuong);
        ?>
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $rowSoluong['soluong']; ?></h3>
                <h5 style="font-weight: bold;"><?php echo $nh['ten_nhom']; ?></h5>
                <p><a href="chi-tiet-nhom.php?p=group&a=list-group&id=<?php echo $nh['ma_nhom']; ?>" class="small-box-footer" style='color: #fff;'>Chi tiết nhóm <i class="fa fa-arrow-circle-right"></i></a></p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
              <a onclick="return confirm('Bạn có thực sự muốn xóa nhóm này?')" href="danh-sach-nhom.php?id=<?php echo $nh['ma_nhom']; ?>" class="small-box-footer">Xóa nhóm <i class="fa fa-trash"></i></a>
            </div>
          </div>
        <?php
          }
        ?>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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