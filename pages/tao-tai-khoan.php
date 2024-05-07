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
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $repass = md5($_POST['repass']);
    $phone = $_POST['phone'];
    $level = $_POST['level'];
    $status = $_POST['status'];
    $access = 0;
    $date_create = date("Y-m-d H:i:s");
    $date_update = date("Y-m-d H:i:s");

    // validate
    if(empty($_POST['lastName']))
      $error['lastName'] = 'Vui lòng nhập <b> họ </b>';

    if(empty($_POST['firstName']))
      $error['firstName'] = 'Vui lòng nhập <b> tên </b>';

    if(empty($_POST['email']))
      $error['email'] = 'Vui lòng nhập <b> email </b>';

    if(empty($_POST['password']))
      $error['password'] = 'Vui lòng nhập <b> mật khẩu </b>';

    if(empty($_POST['repass']))
      $error['repass'] = 'Vui lòng nhập lại <b> mật khẩu </b>';

    if((!empty($_POST['password']) && !empty($_POST['repass'])) && ($password != $repass))
      $error['checkPass'] = 'Mật khẩu không <b> trùng nhau </b>. Vui lòng nhập lại!.';

    // check email exists
    $checkEmail = "SELECT email FROM tai_khoan WHERE email = '$email'";
    $rs_checkEmail = mysqli_query($conn, $checkEmail);
    if(mysqli_num_rows($rs_checkEmail) > 0)
      $error['checkEmail'] = 'Email <b> đã được sử dụng </b>. Vui lòng nhập email khác!.';
    
    // validate file image
    $target_dir = '../uploads/images/';
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if($image)
    {
      // check file type
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif")
      {
        $error['formatImage'] = 'Ảnh không đúng định dạng: <b>jpg</b>, <b>jpeg</b>, <b>png</b>, <b>gif</b>';
      }
      else
      {
        // check exists
        if (file_exists($target_file)) 
        {
          $nameImage = time() . "." . $imageFileType;
        }
        else
        {
          $nameImage = time() . "." . $imageFileType;
        }
      }
    }

    // save to db
    if(!$error)
    {
      $showMess = true;

      if($image)
      {

        // insert record
        $insert = "INSERT INTO tai_khoan(ho, ten, hinh_anh, email, mat_khau, so_dt, quyen, trang_thai, truy_cap, ngay_sua, ngay_tao) VALUES('$lastName', '$firstName', '$nameImage', '$email', '$password', '$phone', $level, $status, $access, '$date_create', '$date_update')";   
        mysqli_query($conn, $insert);
        // add image to folder
        $dirFile = $target_dir . $nameImage;
        move_uploaded_file($_FILES["image"]["tmp_name"], $dirFile);
        $success['success'] = 'Tạo tài khoản mới thành công.';
        echo '<script>setTimeout("window.location=\'tao-tai-khoan.php?p=account&a=add-account\'",1000);</script>';
      }
      else
      {
        $nameImage = 'admin.png';
        // insert record
        $insert = "INSERT INTO tai_khoan(ho, ten, hinh_anh, email, mat_khau, so_dt, quyen, trang_thai, truy_cap, ngay_sua, ngay_tao) VALUES('$lastName', '$firstName', '$nameImage', '$email', '$password', '$phone', $level, $status, $access, '$date_create', '$date_update')";   
        mysqli_query($conn, $insert);
        $success['success'] = 'Tạo tài khoản mới thành công.';
        echo '<script>setTimeout("window.location=\'tao-tai-khoan.php?p=account&a=add-account\'",1000);</script>';
      }
    }
  }

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tài khoản
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="tao-tai-khoan.php?p=account&a=add-account">Tài khoản</a></li>
        <li class="active">Tạo mới tài khoản</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Tạo tài khoản</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data">
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
                <div class="form-group">
                  <label for="exampleInputEmail1">Chọn ảnh: </label>
                  <input type="file" class="form-control" id="exampleInputEmail1" name="image">
                  <p class="help-block">Vui lòng chọn file đúng định dạng: jpg, jpeg, png, gif.</p>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Họ: <b style="color: red;">*</b></label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Nhập họ" name="lastName" value="<?php echo isset($_POST['lastName']) ? $_POST['lastName'] : ''; ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Tên: <b style="color: red;">*</b></label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Nhập tên" name="firstName" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : ''; ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Email: <b style="color: red;">*</b></label>
                  <input type="email" class="form-control" id="exampleInputPassword1" placeholder="Nhập email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Mật khẩu: <b style="color: red;">*</b></label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Nhập mật khẩu" name="password">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Nhập lại mật khẩu: <b style="color: red;">*</b></label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Nhập lại mật khẩu" name="repass">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Số điện thoại:</label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Nhập số điện thoại" name="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Quyền hạn:</label>
                  <div class="col-md-12">
                    <label>
                      <input type="radio" name="level" class="minimal" value="1" checked>
                      Quản trị viên
                    </label>
                    <label>
                      <input type="radio" name="level" class="minimal" value="0">
                      Nhân viên
                    </label>
                  </div>
                </div> 
                <div class="form-group">
                  <label for="exampleInputPassword1">Trạng thái:</label>
                  <div class="col-md-12">
                    <label>
                      <input type="radio" name="status" class="minimal" value="1" checked>
                      Đang hoạt động
                    </label>
                    <label>
                      <input type="radio" name="status" class="minimal" value="0">
                      Ngừng hoạt động
                    </label>
                  </div>
                </div> 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <?php 
                  if($_SESSION['level'] == 1)
                    echo "<button type='submit' class='btn btn-primary' name='save'><i class='fa fa-plus'></i> Tạo tài khoản mới</button>";
                ?>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
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