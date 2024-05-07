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
    $id = $_GET['id'];
    $nhom = "SELECT * FROM nhom WHERE ma_nhom = '$id'";
    $resultNhom = mysqli_query($conn, $nhom);
    $rowNhom = mysqli_fetch_array($resultNhom);
  }

  // show data
  $showData = "SELECT id, ma_nv, ten_nv FROM nhanvien ORDER BY id DESC";
  $result = mysqli_query($conn, $showData);
  $nvhow = array();
  while ($row = mysqli_fetch_array($result)) {
    $nvhow[] = $row;
  }

  // hien thi nhan vien trong nhom
  $nv = "SELECT ctn.id as id, ma_nv, hinh_anh, ten_nv, gioi_tinh, ngay_sinh, ten_chuc_vu, ten_phong_ban, ctn.ngay_tao as ngay_tao, trang_thai FROM chi_tiet_nhom ctn, nhanvien nv, chuc_vu cv, phong_ban pb WHERE ctn.nhan_vien_id = nv.id AND nv.chuc_vu_id = cv.id AND nv.phong_ban_id = pb.id AND ma_nhom = '$id'";
  $resultNV = mysqli_query($conn, $nv);
  $arrNV = array();
  while($rowNV = mysqli_fetch_array($resultNV)) 
  {
    $arrNV[] = $rowNV;
  }

  // chinh sua thong tin nhom
  if(isset($_POST['luuNhom']))
  {
    // create array error
    $error = array();
    $success = array();
    $showMess = false;

    // get id in form
    $tenNhom = $_POST['tenNhom'];
    $moTa = $_POST['moTa'];
    $nguoiSua = $row_acc['ho'] . $row_acc['ten'];
    $ngaySua = date("Y-m-d H:i:s");

    // validate
    if(empty($tenNhom))
      $error['tenNhom'] = 'Vui lòng nhập <b> tên nhóm </b>';

    if(!$error)
    {
      $showMess = true;
      $update = " UPDATE nhom SET
                  ten_nhom = '$tenNhom',
                  mo_ta = '$moTa',
                  nguoi_sua = '$nguoiSua',
                  ngay_sua = '$ngaySua'
                  WHERE ma_nhom = '$id'";
      $result = mysqli_query($conn, $update);
      if($result)
      {
        $success['success'] = 'Lưu thông tin nhóm thành công';
        echo '<script>setTimeout("window.location=\'chi-tiet-nhom.php?p=group&a=list-group&id='.$id.'&edit\'",1000);</script>';
      }
    }
  }
    // chinh sua thong tin nhom
  if(isset($_POST['luuNhanVien']))
  {
    // create array error
    $error = array();
    $success = array();
    $showMess = false;

    // get id in form
    $nhanVien = $_POST['nhanVien'];
    $nguoiTao = $row_acc['ho'] . $row_acc['ten'];
    $ngayTao = date("Y-m-d H:i:s");

    // validate
    if($nhanVien == 'chon')
      $error['nhanVien'] = 'Vui lòng <b> chọn nhân viên </b>';
    // kiem tra nhan vien da ton tai
    $kt = "SELECT nhan_vien_id FROM chi_tiet_nhom WHERE nhan_vien_id = '$nhanVien' AND ma_nhom = '$id'";
    $resultKT = mysqli_query($conn, $kt);
    if(mysqli_num_rows($resultKT) != 0)
      $error['tonTai'] = 'Nhân viên này <b> đã tồn tại </b> trong nhóm';

    if(!$error)
    {
      $showMess = true;
      $insert = "INSERT INTO chi_tiet_nhom(ma_nhom, nhan_vien_id, nguoi_tao, ngay_tao) VALUES('$id', '$nhanVien', '$nguoiTao', '$ngayTao')";
      $result = mysqli_query($conn, $insert);
      if($result)
      {
        $success['success'] = 'Thêm nhân viên vào nhóm thành công';
        echo '<script>setTimeout("window.location=\'chi-tiet-nhom.php?p=group&a=list-group&id='.$id.'&add\'",1000);</script>';
      }
    }

  }

  // delete record
  if(isset($_POST['delete']))
  {
    $showMess = true;
    $maNhom = $_POST['maNhom'];
    $delete = "DELETE FROM chi_tiet_nhom WHERE id = $maNhom";
    mysqli_query($conn, $delete);
    $success['success'] = 'Đã xóa nhân viên ra khỏi nhóm thành công.';
    echo '<script>setTimeout("window.location=\'chi-tiet-nhom.php?p=group&a=list-group&id='.$id.'\'",1000);</script>';
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
            <input type="hidden" name="maNhom">
            Bạn có thực sự muốn xóa nhân viên này ra khỏi nhóm?
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
        Quản lý nhóm
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="danh-sach-nhom.php?p=group&a=list-group">Danh sách nhóm</a></li>
        <li class="active">Quản lý nhóm</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Thao tác nhóm</h3>
            </div>
            <div class="box-body">
              <a  href="chi-tiet-nhom.php?p=group&a=list-group&id=<?php echo $id; ?>&edit" class="btn btn-app">
                <i class="fa fa-edit"></i> Chỉnh sửa nhóm
              </a>
              <a href="chi-tiet-nhom.php?p=group&a=list-group&id=<?php echo $id; ?>&add" class="btn btn-app">
                <i class="fa fa-plus"></i> Thêm nhân viên
              </a>
              <a  href="chi-tiet-nhom.php?p=group&a=list-group&id=<?php echo $id; ?>" class="btn btn-app">
                <i class="fa fa-close"></i> Hủy bỏ
              </a>
            </div>
            <!-- /.box-body -->
          </div>
          <?php 
          if(isset($_GET['edit']))
          {
          ?>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Chỉnh sửa thông tin nhóm</h3>
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
                      <label for="exampleInputEmail1">Mã nhóm: </label>
                      <input type="text" class="form-control" name="maNhom" value="<?php echo $id; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tên nhóm <span style="color: red;">*</span>: </label>
                      <input type="text" class="form-control" placeholder="Nhập tên nhóm" name="tenNhom" value="<?php echo $rowNhom['ten_nhom']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mô tả: </label>
                      <textarea id="editor1" rows="10" cols="80" name="moTa"><?php echo $rowNhom['mo_ta']; ?>
                      </textarea>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Người tạo: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row_acc['ho']; echo " "; echo $row_acc['ten']; ?>" name="nguoiTao" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Ngày tạo: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo date('d-m-Y H:i:s'); ?>" name="ngayTao" readonly>
                    </div>
                    <!-- /.form-group -->
                    <?php 
                      if($_SESSION['level'] == 1)
                        echo "<button type='submit' class='btn btn-warning' name='luuNhom'><i class='fa fa-save'></i> Lưu lại thông tin</button>";
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
          <?php 
          if(isset($_GET['add']))
          {
          ?>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Thêm nhân viên vào nhóm</h3>
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
                      <label for="exampleInputEmail1">Mã nhóm: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" name="maNhom" value="<?php echo $id; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Chọn nhân viên: </label>
                      <select class="form-control" name="nhanVien">
                      <option value="chon">--- Chọn nhân viên ---</option>
                      <?php
                        foreach ($nvhow as $nv) 
                        {
                          echo "<option value='".$nv['id']."'>".$nv['ma_nv']." - ".$nv['ten_nv']."</option>";
                        }
                      ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Người thêm: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row_acc['ho']; echo " "; echo $row_acc['ten']; ?>" name="nguoiTao" readonly>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Ngày thêm: </label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo date('d-m-Y H:i:s'); ?>" name="ngayTao" readonly>
                    </div>
                    <!-- /.form-group -->
                    <?php 
                      if($_SESSION['level'] == 1)
                        echo "<button type='submit' class='btn btn-primary' name='luuNhanVien'><i class='fa fa-plus'></i> Thêm nhân viên</button>";
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
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Nhân viên trong nhóm</h3>
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
                    <th>Mã nhân viên</th>
                    <th>Ảnh</th>
                    <th>Tên nhân viên</th>
                    <th>Giới tính</th>
                    <th>Năm sinh</th>
                    <th>Chức vụ</th>
                    <th>Phòng ban</th>
                    <th>Ngày thêm</th>
                    <th>Trạng thái</th>
                    <th>Xóa</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $count = 1;
                    foreach ($arrNV as $nv) 
                    {
                  ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $nv['ma_nv']; ?></td>
                        <td><img src="../uploads/staffs/<?php echo $nv['hinh_anh']; ?>" width="80"></td>
                        <td><?php echo $nv['ten_nv']; ?></td>
                        <td>
                        <?php
                          if($nv['gioi_tinh'] == 1)
                          {
                            echo "Nam";
                          } 
                          else
                          {
                            echo "Nữ";
                          }
                        ?>
                        </td>
                        <td>
                        <?php 
                          $date = date_create($nv['ngay_sinh']);
                          echo date_format($date, 'd-m-Y');
                        ?>
                        </td>
                        <td><?php echo $nv['ten_chuc_vu']; ?></td>
                        <td><?php echo $nv['ten_phong_ban']; ?></td>
                        <td>
                        <?php 
                          $ngayThem = date_create($nv['ngay_tao']);
                          echo date_format($ngayThem, 'd-m-Y');
                        ?>
                        </td>
                        <td>
                        <?php 
                          if($nv['trang_thai'] == 1)
                          {
                            echo '<span class="badge bg-blue"> Đang làm việc </span>';
                          }
                          else
                          {
                            echo '<span class="badge bg-red"> Đã nghỉ việc </span>';
                          }
                        ?>
                        </td>
                        <th>
                          <?php 
                            if($row_acc['quyen'] == 1)
                            {
                              echo "<button type='button' class='btn bg-maroon btn-flat' data-toggle='modal' data-target='#exampleModal' data-whatever='".$nv['id']."'><i class='fa fa-trash'></i></button>";
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