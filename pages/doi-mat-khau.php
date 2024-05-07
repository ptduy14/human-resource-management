<?php 

// create session
session_start();

if(isset($_SESSION['username']) && isset($_SESSION['level']))
{
  // include file
  include('../layouts/header.php');
  include('../layouts/topbar.php');
  include('../layouts/sidebar.php');


  // save
  if(isset($_POST['save']))
  {
    // create error array
    $error = array();
    $success = array();
    $showMess = false;

    // get value
    $email = $row_acc['email'];
    $oldPass = md5($_POST['oldPass']);
    $newPass = md5($_POST['newPass']);
    $reNewPass = md5($_POST['reNewPass']);

    // validate
    if(empty($_POST['oldPass']))
      $error['oldPass'] = 'Vui lòng nhập <b> mật khẩu cũ </b>';

    if(empty($_POST['newPass']))
      $error['newPass'] = 'Vui lòng nhập <b> mật khẩu mới </b>';

    if(empty($_POST['reNewPass']))
      $error['reNewPass'] = 'Vui lòng nhập lại <b> mật khẩu mới </b>';

    if(!empty($_POST['oldPass']) && $oldPass != $row_acc['mat_khau'])
      $error['errorPass'] = 'Mật khẩu cũ <b> không đúng </b>. Vui lòng thử lại!';

    if($newPass != $reNewPass)
      $error['checkNotSame'] = 'Mật khẩu mới không <b> trùng nhau </b>. Vui lòng thử lại!';


    // save to db
    if(!$error)
    {
      $showMess = true;
      // update record
      $update = " UPDATE tai_khoan SET
                  mat_khau = '$newPass'
                  WHERE email = '$email'";   
      mysqli_query($conn, $update);
      $success['success'] = 'Thay đổi mật khẩu mới thành công.';
      echo '<script>setTimeout("window.location=\'doi-mat-khau.php?p=account&a=changepass\'",1000);</script>';
    }
  }

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Đổi mật khẩu
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="thong-tin-tai-khoan.php?p=account&a=profile">Tài khoản</a></li>
        <li class="active">Đổi mật khẩu</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="../uploads/images/<?php echo $row_acc['hinh_anh']; ?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo $row_acc['ten']; ?> <?php echo $row_acc['ho']; ?></h3>

              <p class="text-muted text-center">
                <?php 

                  if($row_acc['quyen'] == 1)
                  {
                    echo "Quản trị viên";
                  }
                  else
                  {
                    echo "Nhân viên";
                  }

                ?>
              </p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Lượt truy cập:</b> <a class="pull-right"><?php echo number_format($row_acc['truy_cap']); ?></a>
                </li>
                <li class="list-group-item">
                  <b>Ngày tạo:</b> <a class="pull-right">
                    <?php 
                      $date_cre = date_create($row_acc['ngay_tao']);
                      echo date_format($date_cre, 'd/m/Y');
                    ?>
                  </a>
                </li>
                <li class="list-group-item">
                  <b>Ngày sửa:</b> <a class="pull-right">
                    <?php 
                      $date_edi = date_create($row_acc['ngay_sua']);
                      echo date_format($date_edi, 'd/m/Y');
                    ?>
                  </a>
                </li>
                <li class="list-group-item">
                  <b>Trạng thái:</b> <a class="pull-right">Đang hoạt động</a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#settings" data-toggle="tab">Đổi mật khẩu</a></li>
            </ul>
            <div class="tab-content">
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
                // show error
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
              <div class="active tab-pane" id="settings">
                <form class="form-horizontal" method="POST">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Nhập mật khẩu cũ <b style="color: red;">*</b></label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputName" placeholder="Nhập mật khẩu cũ" name="oldPass" value="<?php echo isset($_POST['oldPass']) ? $_POST['oldPass'] : ''; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Nhập mật khẩu mới <b style="color: red;">*</b></label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputEmail" placeholder="Nhập mật khẩu mới" name="newPass" value="<?php echo isset($_POST['newPass']) ? $_POST['newPass'] : ''; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Nhập lại mật khẩu mới <b style="color: red;">*</b></label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputName" placeholder="Nhập lại mật khẩu mới" name="reNewPass" value="<?php echo isset($_POST['reNewPass']) ? $_POST['reNewPass'] : ''; ?>"> 
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" name="save"><i class="fa fa-save"></i> Lưu lại</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
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