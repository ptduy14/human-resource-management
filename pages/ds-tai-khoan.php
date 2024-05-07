<?php 

// create session
session_start();

if(isset($_SESSION['username']) && isset($_SESSION['level']))
{
  // include file
  include('../layouts/header.php');
  include('../layouts/topbar.php');
  include('../layouts/sidebar.php');

  if(isset($_POST['edit']))
  {
    $id = $_POST['idAccount'];
    echo "<script>location.href='sua-tai-khoan.php?p=account&a=list-account&id=".$id."'</script>";
  }

  // show data
  $showData = "SELECT * FROM tai_khoan WHERE id <> 1 ORDER BY ngay_tao DESC";
  $result = mysqli_query($conn, $showData);
  $arrShow = array();
  while ($row = mysqli_fetch_array($result)) {
    $arrShow[] = $row;
  }

  // delete record
  if(isset($_POST['delete']))
  {
    // create array error
    $error = array();
    $success = array();
    $showMess = false;

    // get id in form
    $id = $_POST['idAccount'];
    //$error['test'] = $id;

    // check account using then cannot delete
    if($id == $row_acc['id'])
      $error['accUsing'] = 'Tài khoản <b> đang sử dụng </b>! Bạn không thể xóa tài khoản.';

    if(!$error)
    {
      $showMess = true;

      // remove image
      $dir = '../uploads/images/';
      $getImage = "SELECT hinh_anh FROM tai_khoan WHERE id = $id";
      $rs_getImage = mysqli_query($conn, $getImage);
      $row_getImage = mysqli_fetch_array($rs_getImage);
      $image = $row_getImage['hinh_anh'];
      if($image != 'admin.png')
        unlink($dir . $image);

      // remove record
      $delRecord = "DELETE FROM tai_khoan WHERE id = $id";
      mysqli_query($conn, $delRecord);
      $success['success'] = 'Xóa tài khoản thành công.';
        echo '<script>setTimeout("window.location=\'ds-tai-khoan.php?p=account&a=list-account\'",1000);</script>';
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
            <input type="hidden" name="idAccount">
            Bạn có thực sự muốn xóa tài khoản này?
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
        Tài khoản
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="ds-tai-khoan.php?p=account&a=list-account">Tài khoản</a></li>
        <li class="active">Danh sách tài khoản</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Danh sách tài khoản</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
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
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Ảnh</th>
                    <th>Họ</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Truy cập</th>
                    <th>Điện thoại</th>
                    <th>Quyền hạn</th>
                    <th>Trạng thái</th>
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
                        <td><img src="../uploads/images/<?php echo $arrS['hinh_anh']; ?>" width="80"></td>
                        <td><?php echo $arrS['ho']; ?></td>
                        <td><?php echo $arrS['ten']; ?></td>
                        <td><?php echo $arrS['email']; ?></td>
                        <td><?php echo number_format($arrS['truy_cap']); ?></td>
                        <td><?php echo $arrS['so_dt']; ?></td>
                        <th>
                          <?php 
                            if($arrS['quyen'] == 1)
                            {
                              echo "<span class='label label-primary'>Quản trị viên</span>";
                            }
                            else
                            {
                               echo "<span class='label label-info'>Nhân viên</span>";
                            }
                          ?>
                        </th>
                        <th>
                          <?php 
                            if($arrS['trang_thai'] == 1)
                            {
                              echo "<span class='label label-success'>Đang hoạt động</span>";
                            }
                            else
                            {
                               echo "<span class='label label-danger'>Ngưng hoạt động</span>";
                            }
                          ?>
                        </th>
                        <th>
                          <?php 
                            if($row_acc['quyen'] == 1)
                            {
                              echo "<form method='POST'>";
                              echo "<input type='hidden' value='".$arrS['id']."' name='idAccount'/>";
                              echo "<button type='submit' class='btn bg-orange btn-flat'  name='edit'><i class='fa fa-edit'></i></button>";
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
                              echo "<button type='button' class='btn bg-maroon btn-flat' data-toggle='modal' data-target='#exampleModal' data-whatever='".$arrS['id']."'><i class='fa fa-trash'></i></button>";
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