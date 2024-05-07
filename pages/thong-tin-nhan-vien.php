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
    $showData = "SELECT nv.id as id, ma_nv, hinh_anh, ten_nv, biet_danh, gioi_tinh, nv.ngay_tao as ngay_tao, ngay_sinh, noi_sinh, so_cmnd, ten_tinh_trang, ngay_cap_cmnd, noi_cap_cmnd, nguyen_quan, ten_quoc_tich, ten_dan_toc, ten_ton_giao, ho_khau, tam_tru, ten_loai_nv, ten_trinh_do, ten_chuyen_mon, ten_bang_cap, ten_phong_ban, ten_chuc_vu, trang_thai FROM nhanvien nv, quoc_tich qt, dan_toc dt, ton_giao tg, loai_nv lnv, trinh_do td, chuyen_mon cm, bang_cap bc, phong_ban pb, chuc_vu cv, tinh_trang_hon_nhan hn WHERE nv.quoc_tich_id = qt.id AND nv.dan_toc_id = dt.id AND nv.ton_giao_id = tg.id AND nv.loai_nv_id = lnv.id AND nv.trinh_do_id = td.id AND nv.chuyen_mon_id = cm.id AND nv.bang_cap_id = bc.id AND nv.phong_ban_id = pb.id AND nv.chuc_vu_id = cv.id AND nv.hon_nhan_id = hn.id AND nv.id = $id";
    $result = mysqli_query($conn, $showData);
    $row = mysqli_fetch_array($result);
  }

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Thông tin nhân viên
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?p=index&a=statistic"><i class="fa fa-dashboard"></i> Tổng quan</a></li>
        <li><a href="danh-sach-nhan-vien.php?p=staff&a=list-staff">Danh sách nhân viên</a></li>
        <li class="active">Thông tin nhân viên</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Mã nhân viên: <?php echo $row['ma_nv']; ?></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-lg-2">
                  <img src="../uploads/staffs/<?php echo $row['hinh_anh']; ?>" width="100%">
                </div>
                <div class="col-lg-5 col-sm-5 col-md-6 col-xs-12">
                  <p class="box-title">Tên nhân viên: <b><?php echo $row['ten_nv']; ?></b></p>
                  <p class="box-title">Biệt danh: 
                    <?php if($row['biet_danh'] == ""){ echo "Không có"; } else { echo $row['biet_danh']; } ?>
                  </p>
                  <p class="box-title">Giới tính: 
                    <?php if($row['gioi_tinh'] == 1){ echo "Nam"; } else { echo "Nữ"; } ?>
                  </p>
                  <p class="box-title">Ngày sinh: 
                    <b><?php $date = date_create($row['ngay_sinh']); echo date_format($date, 'd-m-Y'); ?></b>
                  </p>
                  <p class="box-title">Nơi sinh: 
                    <?php echo $row['noi_sinh']; ?>
                  </p>
                  <p class="box-title">Tình trạng hôn nhân: 
                    <?php echo $row['ten_tinh_trang']; ?>
                  </p>
                  <p class="box-title">Số CMND: 
                    <b> <?php echo $row['so_cmnd']; ?> </b>
                  </p>
                  <p class="box-title">Ngày cấp: 
                    <?php $ngayCap = date_create($row['ngay_cap_cmnd']); echo date_format($ngayCap, 'd-m-Y'); ?>
                  </p>
                  <p class="box-title">Nơi cấp: 
                    <?php echo $row['noi_cap_cmnd']; ?>
                  </p>
                  <p class="box-title">Nguyên quán: 
                    <?php echo $row['noi_cap_cmnd']; ?>
                  </p>
                  <p class="box-title">Quốc tịch: 
                    <?php echo $row['ten_quoc_tich']; ?>
                  </p>
                  <p class="box-title">Dân tộc: 
                    <?php echo $row['ten_dan_toc']; ?>
                  </p>
                  <p class="box-title">Tôn giáo: 
                    <?php echo $row['ten_ton_giao']; ?>
                  </p>
                </div>
                <!-- col-5 -->
                <div class="col-lg-5 col-sm-5 col-md-6 col-xs-12">
                  <p class="box-title">Hộ khẩu: 
                    <b> <?php echo $row['ho_khau']; ?> </b>
                  </p>
                  <p class="box-title">Tạm trú: 
                    <?php echo $row['tam_tru']; ?>
                  </p>
                  <p class="box-title">Loại nhân viên: 
                    <b><?php echo $row['ten_loai_nv']; ?></b>
                  </p>
                  <p class="box-title">Trình độ: 
                    <b><?php echo $row['ten_trinh_do']; ?></b>
                  </p>
                  <p class="box-title">Chuyên môn: 
                    <b><?php echo $row['ten_chuyen_mon']; ?></b>
                  </p>
                  <p class="box-title">Bằng cấp: 
                    <b><?php echo $row['ten_bang_cap']; ?></b>
                  </p>
                  <p class="box-title">Phòng ban: 
                    <b><?php echo $row['ten_phong_ban']; ?></b>
                  </p>
                  <p class="box-title">Chức vụ: 
                    <b><?php echo $row['ten_chuc_vu']; ?></b>
                  </p>
                  <p class="box-title">Trạng thái: 
                    
                      <?php 
                        if($row['trang_thai'] == 1)
                        {
                          echo '<span class="badge bg-blue"> Đang làm việc </span>';
                        }
                        else
                        {
                          echo '<span class="badge bg-red"> Đã nghỉ việc </span>';
                        }
                      ?>
                    </span>
                  </p>
                </div>
                <!-- col-5 -->
              </div>
              <!-- row -->
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