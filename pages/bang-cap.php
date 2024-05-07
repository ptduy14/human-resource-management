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
    $id = $_POST['idCertificate'];
    echo "<script>location.href='sua-bang-cap.php?p=staff&a=certificate&id=".$id."'</script>";
  }

  // show data
  $showData = "SELECT id, ma_bang_cap, ten_bang_cap, ghi_chu, nguoi_tao, ngay_tao, nguoi_sua, ngay_sua FROM bang_cap ORDER BY ngay_tao DESC";
  $result = mysqli_query($conn, $showData);
  $arrShow = array();
  while ($row = mysqli_fetch_array($result)) {
    $arrShow[] = $row;
  }

  // create code room
  $certificateCode = "MBC" . time();

  // delete record
  if(isset($_POST['save']))
  {
    // create array error
    $error = array();
    $success = array();
    $showMess = false;

    // get id in form
    $certificateName = $_POST['certificateName'];
    $description = $_POST['description'];
    $personCreate = $_POST['personCreate'];
    $dateCreate = date("Y-m-d H:i:s");
    $personEdit = $_POST['personCreate'];
    $dateEdit = date("Y-m-d H:i:s");

    // validate
    if(empty($certificateName))
      $error['certificateName'] = 'Vui lòng nhập <b> tên bằng cấp </b>';

    if(!$error)
    {
      $showMess = true;
      $insert = "INSERT INTO bang_cap(ma_bang_cap, ten_bang_cap, ghi_chu, nguoi_tao, ngay_tao, nguoi_sua, ngay_sua) VALUES('$certificateCode','$certificateName', '$description', '$personCreate', '$dateCreate', '$personEdit', '$dateEdit')";
      $result = mysqli_query($conn, $insert);
      if($result)
      {
        $success['success'] = 'Thêm bằng cấp thành công';
        echo '<script>setTimeout("window.location=\'bang-cap.php?p=staff&a=certificate\'",1000);</script>';
      }
    }
  }

  // delete record
  if(isset($_POST['delete']))
  {
    $showMess = true;

    $id = $_POST['idCertificate'];
    $delete = "DELETE FROM bang_cap WHERE id = $id";
    mysqli_query($conn, $delete);
    $success['success'] = 'Xóa bằng cấp thành công.';
    echo '<script>setTimeout("window.location=\'bang-cap.php?p=staff&a=certificate\'",1000);</script>';
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
            <input type="hidden" name="idCertificate">
            Bạn có thực sự muốn xóa bằng cấp này?
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
        Bằng cấp
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="bang-cap.php?p=staff&a=certificate">Bằng cấp</a></li>
        <li class="active">Thêm bằng cấp</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Thêm bằng cấp</h3>
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
                      <label for="exampleInputEmail1">Mã bằng cấp: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" name="speacialCode" value="<?php echo $certificateCode; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tên bằng cấp: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên bằng cấp" name="certificateName">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mô tả: </label>
                      <textarea id="editor1" rows="10" cols="80" name="description">
                      </textarea>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Người tạo: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row_acc['ho']; ?> <?php echo $row_acc['ten']; ?>" name="personCreate" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Ngày tạo: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo date('d-m-Y H:i:s'); ?>" name="dateCreate" readonly>
                    </div>
                    <!-- /.form-group -->
                    <?php 
                      if($_SESSION['level'] == 1)
                        echo "<button type='submit' class='btn btn-primary' name='save'><i class='fa fa-plus'></i> Thêm bằng cấp</button>";
                    ?>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Danh sách bằng cấp</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>STT</th>
                    <th>Mã bằng cấp</th>
                    <th>Tên bằng cấp</th>
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
                        <td><?php echo $arrS['ma_bang_cap']; ?></td>
                        <td><?php echo $arrS['ten_bang_cap']; ?></td>
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
                              echo "<input type='hidden' value='".$arrS['id']."' name='idCertificate'/>";
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