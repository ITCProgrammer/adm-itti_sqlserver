<?php
ini_set("error_reporting", 1);
session_start();
//include config
//require_once "waktu.php";
include_once 'koneksi.php';
//include"koneksi.php";
include_once ('tgl_indo.php');
?>



<?php
//set base constant
if (!isset($_SESSION['user_id10'])) {
?>
  <script>
    setTimeout("location.href='login'", 500);
  </script>
<?php
  die('Illegal Acces');
} elseif (!isset($_SESSION['pass_id10'])) {
?>
  <script>
    setTimeout("location.href='lockscreen'", 500);
  </script>
<?php
  die('Illegal Acces');
}

//request page
$page = isset($_GET['p']) ? $_GET['p'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$page = strtolower($page);
$iduser = $_SESSION['id10'];
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
  <title>Adm |
    <?php if ($_GET['p'] != "") {
      echo ucwords($_GET['p']);
    } else {
      echo "Home";
    } ?>
  </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- toast CSS -->
  <link href="bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link href="bower_components/datatables.net-bs/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">

  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="dist/css/skins/skin-purple.min.css">
  <!-- Sweet Alert -->
  <link href="bower_components/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
  <!-- Sweet Alert -->
  <script type="text/javascript" src="bower_components/sweetalert/sweetalert2.min.js"></script>
  <!-- Select2 -->
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  <?php if ($_GET['p'] == "input-bon-kain"): ?>
        <!-- X Editable -->
        <link href="bower_components/xeditable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <?php endif; ?>	
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <!--
  <link rel="stylesheet"
        href="dist/css/font/font.css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  -->
  <link rel="icon" type="image/png" href="dist/img/ITTI_Logo index.ico">
  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <style>
    .blink_me {
      animation: blinker 1s linear infinite;
    }

    .blink_me1 {
      animation: blinker 7s linear infinite;
    }

    .main-sidebar {
      position: sticky;
      overflow-y: scroll;
      max-height: 100vh;
    }

    .bulat {
      border-radius: 50%;
      /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);*/
    }

    .border-dashed {
      border: 3px dashed #083255;
    }

    .border-dashed-tujuan {
      border: 3px dashed #FF0007;
    }

    @keyframes blinker {
      50% {
        opacity: 0;
      }
    }

    body {
      font-family: Calibri, "sans-serif", "Courier New";
      /* "Calibri Light","serif" */
      font-style: normal;
    }
  </style>

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition skin-purple sidebar-collapse fixed">

  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header ">

      <!-- Logo -->
      <a href="?p=Home" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>ADM</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>ADM</b> ITTI</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
    <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <?php $qryNCP = sqlsrv_query($cond, "SELECT COUNT(*) as jml from db_qc.tbl_ncp_qcf WHERE tgl_rencana IS NULL $Wdept AND status='Belum OK'");
                        $rNCP = sqlsrv_fetch_array($qryNCP, SQLSRV_FETCH_ASSOC);
                        ?>

                        <!-- Notifications Menu -->
                        <li class="dropdown notifications-menu">
                            <!-- Menu toggle button -->
                            <a href="?p=Status-NCP-New" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning"><?php echo $rNCP['jml']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Ada <span
                                        class="label label-warning"><?php echo $rNCP['jml']; ?></span> NCP Baru </li>
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <ul class="menu">
                                        <?php $qryNCP1 = sqlsrv_query($cond, "SELECT no_ncp FROM db_qc.tbl_ncp_qcf WHERE tgl_rencana IS NULL $Wdept AND status='Belum OK'");
                                        while ($rNCP1 = sqlsrv_fetch_array($qryNCP1, SQLSRV_FETCH_ASSOC)) {
                                            ?>
                                            <li><!-- start notification -->
                                                <a href="?p=Status-NCP-New">
                                                    <i class="fa fa-file-text text-aqua"></i>
                                                    <?php echo "No NCP: " . $rNCP1['no_ncp']; ?>
                                                </a>
                                            </li>
                                            <!-- end notification -->
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li class="footer"><a href="?p=Status-NCP-New">Tampil Semua</a></li>
                            </ul>
                        </li>
                        <?php $qryNCP2 = sqlsrv_query($cond, "SELECT
                                                           
                                                                    COUNT(*) AS jml 
                                                                FROM
                                                                    db_qc.tbl_ncp_qcf 
                                                                WHERE
                                                                    tgl_rencana IS NOT NULL $Wdept 
                                                                    AND STATUS = 'Belum OK' 
                                                                    AND (
                                                                    penyelesaian = '' 
                                                                    OR penyelesaian IS NULL)");
                        $rNCP2 = sqlsrv_fetch_array($qryNCP2, SQLSRV_FETCH_ASSOC);
                        ?>

                        <!-- Tasks Menu -->
                        <li class="dropdown tasks-menu">
                            <!-- Menu Toggle Button -->
                            <a href="?p=Status-NCP-New" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-info"><?php echo $rNCP2['jml']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Ada <span
                                        class="label label-primary"><?php echo $rNCP2['jml']; ?></span> NCP sedang
                                    proses</li>
                                <li>
                                    <!-- Inner menu: contains the tasks -->
                                    <ul class="menu">
                                        <?php $qryNCP3 = sqlsrv_query($cond, "SELECT
                                                                                    no_ncp 
                                                                                FROM
                                                                                    db_qc.tbl_ncp_qcf 
                                                                                WHERE
                                                                                    tgl_rencana IS NOT NULL $Wdept 
                                                                                    AND STATUS = 'Belum OK' 
                                                                                    AND (
                                                                                    penyelesaian = '' 
                                                                                    OR penyelesaian IS NULL)");
                                        while ($rNCP3 = sqlsrv_fetch_array($qryNCP3, SQLSRV_FETCH_ASSOC)) {
                                            ?>
                                            <li><!-- Task item -->
                                                <a href="?p=Status-NCP-New">
                                                    <!-- Task title and progress text -->
                                                    <h3>
                                                        <?php echo "No NCP: " . $rNCP3['no_ncp']; ?>
                                                        <small class="pull-right"><?php echo "50"; ?>%</small>
                                                    </h3>
                                                    <!-- The progress bar -->
                                                    <div class="progress xs">
                                                        <!-- Change the css width attribute to simulate progress -->
                                                        <div class="progress-bar <?php if ($prsn == "100") {
                                                            echo "bg-green";
                                                        } else if (51 > 50) {
                                                            echo "bg-aqua";
                                                        } ?> "
                                                            style="width: <?php echo "50"; ?>%" role="progressbar"
                                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only"><?php echo "50"; ?>% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="?p=Status-NCP-New">Tampil Semua</a>
                                </li>
                            </ul>
                        </li>
                        <?php $qryNCP4 = sqlsrv_query($cond, "SELECT
                                                                    COUNT(*) AS jml 
                                                                FROM
                                                                    db_qc.tbl_ncp_qcf 
                                                                WHERE
                                                                    tgl_rencana IS NULL $Wdept 
                                                                    AND STATUS = 'Belum OK' 
                                                                    AND NOT penyelesaian = ''");
                        $rNCP4 = sqlsrv_fetch_array($qryNCP4, SQLSRV_FETCH_ASSOC);
                        ?>
                        <!-- Revisi Menu -->
                        <li class="dropdown tasks-menu">
                            <!-- Menu Toggle Button -->
                            <a href="?p=Status-NCP-New" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa  fa-flag-checkered"></i>
                                <span class="label label-danger"><?php echo $rNCP4['jml']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Ada <span
                                        class="label label-danger"><?php echo $rNCP4['jml']; ?></span> NCP yang Tunggu
                                    OK QCF</li>
                                <li>
                                    <!-- Inner menu: contains the tasks -->
                                    <ul class="menu">
                                        <?php $qryNCP5 = sqlsrv_query($cond, "SELECT
                                                                                no_ncp 
                                                                            FROM
                                                                                db_qc.tbl_ncp_qcf 
                                                                            WHERE
                                                                                tgl_rencana IS NOT NULL $Wdept 
                                                                                AND STATUS = 'Belum OK' 
                                                                                AND NOT penyelesaian = ''");
                                        while ($rNCP5 = sqlsrv_fetch_array($qryNCP5, SQLSRV_FETCH_ASSOC)) { ?>
                                            <li><!-- Task item -->
                                                <a href="?p=Status-NCP-New">
                                                    <!-- Task title and progress text -->
                                                    <h3>
                                                        <?php echo "No NCP: " . $rNCP5['no_ncp'] . ""; ?>
                                                    </h3>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="?p=Status-NCP-New">Tampil Semua</a>
                                </li>
                            </ul>
                        </li>
            <?php $qryNCP6 = sqlsrv_query($cond, "SELECT
                                                                    COUNT(*) AS jml 
                                                                FROM
                                                                    db_qc.tbl_ncp_qcf_now 
                                                                WHERE
                                  ncp_hitung = 'ya'
                                                                    $Wdept 
                                                                    AND STATUS = 'Belum OK' 
                                                                    AND penyelesaian = ''");
                        $rNCP6 = sqlsrv_fetch_array($qryNCP6, SQLSRV_FETCH_ASSOC);
                        ?>
            <li class="dropdown tasks-menu">
                            <!-- Menu Toggle Button -->
                            <a href="?p=Status-NCP-New" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa  fa-bell"></i>
                                <span class="label label-danger"><?php echo $rNCP6['jml']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Ada <span
                                        class="label label-danger"><?php echo $rNCP6['jml']; ?></span> NCP Belum di isi Detail</li>
                                <li>
                                    <!-- Inner menu: contains the tasks -->
                                    <ul class="menu">
                                        <?php $qryNCP7 = sqlsrv_query($cond, "SELECT
                                                                                no_ncp 
                                                                            FROM
                                                                                db_qc.tbl_ncp_qcf_now 
                                                                            WHERE
                                                                                ncp_hitung = 'ya'
                                        $Wdept 
                                        AND STATUS = 'Belum OK' 
                                        AND penyelesaian = ''");
                                        while ($rNCP7 = sqlsrv_fetch_array($qryNCP7, SQLSRV_FETCH_ASSOC)) { ?>
                                            <li><!-- Task item -->
                                                <a href="?p=Status-NCP-New">
                                                    <!-- Task title and progress text -->
                                                    <h3>
                                                        <?php echo "No NCP: " . $rNCP7['no_ncp'] . ""; ?>
                                                    </h3>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="?p=Status-NCP-New">Tampil Semua</a>
                                </li>
                            </ul>
                        </li>
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="dist/img/<?php echo $_SESSION['foto10']; ?>.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">
                  <?php echo strtoupper($_SESSION['user_id10']); ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="dist/img/<?php echo $_SESSION['foto10']; ?>.png" class="img-circle" alt="User Image">

                  <p>
                    <?php echo strtoupper($_SESSION['user_id10']); ?>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" id="<?php echo $iduser; ?>" class="btn btn-default open_change_password">Change Password</a>
                  </div>
                  <div class="pull-right">
                    <a href="logout" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
           </ul>
         </div>  
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="dist/img/<?php echo $_SESSION['foto10']; ?>.png" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>
              <?php echo strtoupper($_SESSION['user_id10']); ?>
            </p>
            <!-- Status -->
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>     <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">HEADER</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="<?php if ($_GET['p'] == "Home" or $_GET['p'] == "") {
                        echo "active";
                      } ?>"><a href="?p=Home"><i class="fa fa-dashboard text-success"></i> <span>DashBoard</span></a></li>
      <?php if ($_SESSION['lvl_id10'] == "6" or $_SESSION['lvl_id10'] == "2") { ?>
            <li class="treeview <?php if ($_GET['p'] == "Input-Bon" or $_GET['p'] == "Lap-Bon" or $_GET['p'] == "input-bon-kain") {
                                  echo "active";
                                } ?>">
              <a href="#"><i class="fa fa-clone text-aqua"></i> <span>Ganti Kain</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if ($_GET['p'] == "Input-Bon") {
                              echo "active";
                            } ?>"><a href="?p=Input-Bon"><i class="fa fa-columns text-yellow"></i> <span>Input Bon</span></a></li>
                <li class="<?php if ($_GET['p'] == "Lap-Bon" or $_GET['p'] == "input-bon-kain") {
                              echo "active";
                            } ?>"><a href="?p=Lap-Bon"><i class="fa fa-columns text-blue"></i> <span>Laporan Bon</span></a></li>
                <?php if ($_SESSION['dept10'] == 'CSR') { ?>
                        <li class="<?php if ($_GET['p'] == 'input_email') { echo 'active'; } ?>">
                          <a href="?p=input_email"><i class="fa fa-envelope text-green"></i> <span>Input Email</span></a>
                        </li>
                <?php } ?>
              </ul>
            </li>
      <?php } ?>
      <?php if ($_SESSION['dept10']=="QCF" or $_SESSION['dept10']=="FIN" or $_SESSION['dept10']=="BRS" or $_SESSION['dept10']=="DYE" or $_SESSION['dept10']=="GKG" or $_SESSION['dept10']=="DIT") { ?>
            <li class="treeview <?php if ($_GET['p'] == "Input-Stop-Mesin" or $_GET['p'] == "Lap-Stoppage-Mesin") {
                                  echo "active";
                                } ?>">
              <a href="#"><i class="fa fa-clone text-aqua"></i> <span>Stoppage Mesin</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if ($_GET['p'] == "Input-Stoppage-Mesin") {
                              echo "active";
                            } ?>"><a href="?p=Input-Stoppage-Mesin"><i class="fa fa-columns text-yellow"></i> <span>Input Stoppage Mesin</span></a></li>
                <li class="<?php if ($_GET['p'] == "Lap-Stoppage-Mesin") {
                              echo "active";
                            } ?>"><a href="?p=Lap-Stoppage-Mesin"><i class="fa fa-columns text-blue"></i> <span>Laporan Stoppage Mesin</span></a></li>
              </ul>
            </li>
      <?php } ?>
      <li class="treeview <?php if ($_GET['p'] == "Input-NCP-New" or $_GET['p'] == "Status-NCP-New") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-cubes text-red"></i> <span>Data NCP</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if ($_SESSION['dept10']=="CQA" or $_SESSION['dept10']=="QCF" or $_SESSION['dept10']=="DIT" or $_SESSION['dept10']=="TAS" or $_SESSION['dept10']=="MNF") { ?>
                                    <li class="<?php if ($_GET['p'] == "Input-NCP-New") {
                                        echo "active";
                                    } ?>"><a href="?p=Input-NCP-New"><i
                                                class="fa fa-calendar-check-o text-green"></i> <span>Input NCP</span></a></li>
                                <?php } ?>
                                <li class="<?php if ($_GET['p'] == "Status-NCP-New") {
                                    echo "active";
                                } ?>"><a href="?p=Status-NCP-New"><i class="fa fa-area-chart text-navy"></i>
                                        <span>Status NCP</span> </a></li>
                            </ul>
                        </li>
      <?php if ($_SESSION['dept10']=="CQA" or $_SESSION['dept10']=="QCF" or $_SESSION['dept10']=="DYE" or $_SESSION['dept10']=="TAS" or $_SESSION['dept10']=="MNF") { ?>
      <li class="treeview <?php if ($_GET['p'] == "Lap-NCP" or $_GET['p'] == "Lap-NCP-New" or $_GET['p'] == "Grafik-NCP-Now" or $_GET['p'] == "Lap-Kesesuaian-Colorist" or $_GET['p'] == "Lap-NCP-CanDis-Now" or $_GET['p'] == "Lap-3Besar-NCP-Now" or $_GET['p'] == "Lap-5Besar-NCP-Now" or $_GET['p'] == "Lap-Pencapaian-Now" or $_GET['p'] == "Lap-NCP-Bulan-Now" or $_GET['p'] == "Lap-NCP-Now") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-file-o text-blue"></i> <span>Reports NCP</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php if ($_GET['p'] == "Lap-NCP-Now") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-NCP-Now"><i class="fa fa-circle-o text-navy"></i>
                                        <span>Laporan NCP</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Lap-NCP-Bulan-Now") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-NCP-Bulan-Now"><i class="fa fa-circle-o text-blue"></i>
                                        <span>Laporan NCP Bulanan</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Lap-Pencapaian-Now") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-Pencapaian-Now"><i
                                            class="fa fa-circle-o text-warning"></i> <span>Laporan Pencapaian</span></a>
                                </li>
                                <li class="<?php if ($_GET['p'] == "Lap-5Besar-NCP-Now") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-5Besar-NCP-Now"><i class="fa fa-circle-o text-orange"></i>
                                        <span>Laporan 5 Besar NCP</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Lap-3Besar-NCP-Now") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-3Besar-NCP-Now"><i class="fa fa-circle-o text-teal"></i>
                                        <span>Laporan 3 Besar NCP</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Lap-NCP-CanDis-Now") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-NCP-CanDis-Now"><i class="fa fa-circle-o text-green"></i>
                                        <span>Lap NCP Cancel/Disposisi</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Lap-Kesesuaian-Colorist") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-Kesesuaian-Colorist"><i class="fa fa-circle-o text-red"></i>
                                        <span>Lap Kesesuaian Colorist</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Grafik-NCP-Now") {
                                    echo "active";
                                } ?>"><a href="?p=Grafik-NCP-Now"><i class="fa fa-circle-o text-purple"></i>
                                        <span>Grafik NCP</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Lap-NCP-New") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-NCP-New"><i class="fa fa-circle-o text-navy"></i>
                                        <span>Laporan NCP Lama 2022</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Lap-NCP") {
                                    echo "active";
                                } ?>"><a href="?p=Lap-NCP"><i class="fa fa-circle-o text-navy"></i>
                                        <span>Laporan NCP Lama 2019</span></a></li>
                            </ul>
                        </li>
      <?php } ?>
      <!-- Lap Cocok Warna -->
      <?php if ($_SESSION['lvl_id10'] == "6" or $_SESSION['lvl_id10'] == "2") { ?>
                        <li class="treeview <?php if ($_GET['p'] == "Input-Lap-Cwarna-Fin-New" or $_GET['p'] == "Input-Lap-Cwarna-Dye-New" or $_GET['p'] == "Lihat-Data-Cwarna-Dye-New" or $_GET['p'] == "Lihat-Data-Cwarna-Fin-New") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-cubes"></i> <span>Cocok Warna</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php if ($_GET['p'] == "Input-Lap-Cwarna-Fin-New" or $_GET['p'] == "Lihat-Data-Cwarna-Fin-New") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['ad10'] == "1") {
                                      echo "hidden";
                                  } ?>"><a href="?p=Input-Lap-Cwarna-Fin-New"><i class="fa fa-calendar"></i>
                                        <span>Lap Cocok Warna Finishing</span></a></li>
                                <li class="<?php if ($_GET['p'] == "Input-Lap-Cwarna-Dye-New" or $_GET['p'] == "Lihat-Data-Cwarna-Dye-New") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['ad10'] == "1") {
                                      echo "hidden";
                                  } ?>"><a href="?p=Input-Lap-Cwarna-Dye-New"><i class="fa fa-calendar"></i>
                                        <span>Lap Cocok Warna Dyeing</span></a></li>
                            </ul>
                        </li>
      <?php } ?>
      <!-- Laporan Jahit -->
        <li class="treeview <?php if ($_GET['p'] == "Input-Lap-Jahit-New" or $_GET['p'] == "Lihat-Data-Jahit-New") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-file-o text-yellow"></i> <span>Laporan Jahit</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li 
                                class="<?php if ($_GET['p'] == "Input-Lap-Jahit-New" or $_GET['p'] == "Lihat-Data-Jahit-New") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['ad10'] == "biasa") {
                                      echo "hidden";
                                  } ?>"><a href="?p=Input-Lap-Jahit-New"><i
                                            class="fa fa-file-text text-orange"></i> <span>Lap Jahit</span></a></li>
                            </ul>
          </li>
        <!-- lAPORAN sHADDING -->
              <li class="treeview <?php if ($_GET['p'] == "Input-Lap-Shading" or $_GET['p'] == "Lihat-Data-Shading") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-file-o text-red"></i> <span>Laporan Shading</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php if ($_GET['p'] == "Input-Lap-Shading" or $_GET['p'] == "Lihat-Data-Shading") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['ad10'] == "biasa") {
                                      echo "hidden";
                                  } ?>"><a href="?p=Input-Lap-Shading"><i
                                            class="fa fa-gear text-teal"></i> <span>Lap Shading</span></a></li>
                            </ul>
                        </li>
        <!-- Laporan Beda Roll  -->
        <!-- <li class="treeview <?php if ($_GET['p'] == "Input-Lap-Beda-Roll" or $_GET['p'] == "Lihat-Data-Beda-Roll") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-file-o"></i> <span>Laporan Beda Roll</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php if ($_GET['p'] == "Input-Lap-Beda-Roll" or $_GET['p'] == "Lihat-Data-Beda-Roll") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['akses'] == "biasa") {
                                      echo "hidden";
                                  } ?>"><a href="InputLapBedaRoll"><i
                                            class="fa fa-gear text-teal"></i> <span>Lap Beda Roll</span></a></li>
                            </ul>
                        </li> -->
        <!-- Laporan Jahit Shading -->
        <!-- <li class="treeview <?php if ($_GET['p'] == "Input-Lap-Jahit-Shading" or $_GET['p'] == "LihatDataJahitShading") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-file text-green"></i> <span>Laporan Jahit Shading</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right text-green"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php if ($_GET['p'] == "Input-Lap-Jahit-Shading" or $_GET['p'] == "LihatDataJahitShading") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['akses'] == "biasa") {
                                      echo "hidden";
                                  } ?>"><a href="InputLapJahitShading"><i
                                            class="fa fa-gear text-teal"></i> <span>Lap Jahit Shading</span></a></li>
                            </ul>
                        </li> -->
        <!-- Laporan Tempel Beda Roll  -->
        <!-- <li class="treeview <?php if ($_GET['p'] == "Input-Tempel-Beda-Roll" or $_GET['p'] == "LihatTempelBedaRoll") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-file text-blue"></i> <span>Laporan Tempel Beda Roll</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php if ($_GET['p'] == "Input-Tempel-Beda-Roll" or $_GET['p'] == "LihatTempelBedaRoll") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['akses'] == "biasa") {
                                      echo "hidden";
                                  } ?>"><a href="InputTempelBedaRoll"><i
                                            class="fa fa-gear text-teal"></i> <span>Lap Tempel Beda Roll</span></a></li>
                            </ul>
                        </li> -->
        <!-- Sticker custom -->
        <!-- <li class="treeview <?php if ($_GET['p'] == "Stiker_Custom") {
                            echo "active";
                        } ?>">
                            <a href="#"><i class="fa fa-tag text-red"></i> <span>Sticker Custom</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php if ($_GET['p'] == "Stiker_Custom") {
                                    echo "active";
                                } ?> <?php if ($_SESSION['akses'] == "biasa") {
                                      echo "hidden";
                                  } ?>"><a href="StikerCustom"><i class="fa fa-file-text"></i>
                                        <span>Stiker Custom</span></a></li>
                            </ul>
                        </li> -->
        <!-- Lap Harian Bulanan -->
        <li class="treeview <?php if ($_GET['p'] == "LapHarianFIN" || $_GET['p'] == "LapBulananFIN") { echo "active"; } ?>">
          <a href="#"><i class="fa fa-bar-chart text-warning"></i> <span>Reports Finishing</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if ($_GET['p'] == "LapHarianFIN") { echo "active"; } ?>">
              <a href="?p=LapHarianFIN"><i class="fa fa-file text-red"></i> <span>Lap Harian Finishing</span></a></li>
            <li class="<?php if ($_GET['p'] == "LapBulananFIN") { echo "active"; } ?>">
              <a href="?p=LapBulananFIN"><i class="fa fa-file text-yellow"></i> <span>Lap Bulanan Finishing</span></a></li>  
          </ul>
        </li>
        <li class="treeview <?php if ($_GET['p'] == "LapHarianBrs" || $_GET['p'] == "Laporan-Brs" || $_GET['p'] == "LapBulananBrs") { echo "active"; } ?>">
          <a href="#"><i class="fa fa-bar-chart text-warning"></i> <span>Reports Brushing</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if ($_GET['p'] == "LapHarianBrs") { echo "active"; } ?>">
              <a href="?p=LapHarianBrs"><i class="fa fa-file text-red"></i> <span>Lap Harian Brushing</span></a></li>
            <li class="<?php if ($_GET['p'] == "LapBulananBrs") { echo "active"; } ?>">
              <a href="?p=LapBulananBrs"><i class="fa fa-file text-yellow"></i> <span>Lap Bulanan Brushing</span></a></li>  
          </ul>
        </li>
        <li class="treeview <?php if ($_GET['p'] == "LapHarianBrs" || $_GET['p'] == "Laporan-Brs" || $_GET['p'] == "LapBulananBrs") { echo "active"; } ?>">
          <a href="#"><i class="fa fa-bar-chart text-warning"></i> <span>Reports Dyeing</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if ($_GET['p'] == "LapHarianDye") { echo "active"; } ?>">
              <a href="?p=LapHarianDye"><i class="fa fa-file text-red"></i> <span>Lap Harian Dyeing</span></a></li> 
          </ul>
        </li>
        <li class="treeview <?php if ($_GET['p'] == "LapRFTQCF") { echo "active"; } ?>">
          <a href="#"><i class="fa fa-bar-chart text-warning"></i> <span>Reports QCF</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if ($_GET['p'] == "LapRFTQCF") { echo "active"; } ?>">
              <a href="?p=LapRFTQCF"><i class="fa fa-file text-red"></i> <span>Lap RFT</span></a></li> 
          </ul>
        </li>
        <li class="treeview <?php if ($_GET['p'] == "LapTolakBasah" || $_GET['p'] == "LapGagalProses" || $_GET['p'] == "Lap-Sum-CQA") { echo "active"; } ?>">
          <a href="#"><i class="fa fa-tasks text-success"></i> <span>Summary CQA</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if ($_GET['p'] == "ketRecipe") {echo "active";} ?>">
              <a href="?p=ketRecipe"><i class="fa  fa-recycle text-green"></i><span>Ket Resep DYE</span></a>
            </li>
            <li class="<?php if ($_GET['p'] == "LapTolakBasah") {echo "active";} ?>">
              <a href="?p=LapTolakBasah"><i class="fa fa-thermometer-half text-yellow"></i><span>Tolak Basah DYE</span></a>
            </li>
            <li class="<?php if ($_GET['p'] == "LapGagalProses") {echo "active";} ?>">
              <a href="?p=LapGagalProses"><i class="fa fa-window-close-o text-red"></i><span>Gagal Proses DYE</span></a>
            </li>
            <li class="<?php if ($_GET['p'] == "Lap-Sum-CQA") {echo "active";} ?>">
              <a href="?p=Lap-Sum-CQA"><i class="fa fa-clipboard text-blue"></i><span>Summary All</span></a>
            </li>
            <li class="<?php if ($_GET['p'] == "Lap-Sum-Recipe") {echo "active";} ?>">
              <a href="?p=Lap-Sum-Recipe"><i class="fa fa-file text-green"></i><span>Summary Recipe</span></a>
            </li>
            <li class="<?php if ($_GET['p'] == "grafik-recipe") {echo "active";} ?>">
              <a href="?p=grafik-recipe"><i class="fa fa-line-chart text-blue"></i><span>Grafik Recipe</span></a>
            </li>
          </ul>
        </li>
        <?php if($_SESSION['dept10']=="CQA" || $_SESSION['dept10']=="QCF" || $_SESSION['dept10']=="DIT" || $_SESSION['dept10']=="MKT" || strtolower(substr($_SESSION['user_id10'], 0, 3)) == 'adm'):?>
          <!-- <li <?php if ($_GET['p'] == "FirstLot") { echo "active"; } ?>">
            <li class="<?php if ($_GET['p'] == "FirstLot") { echo "active"; } ?>">
              <a href="?p=FirstLot"><i class="fa fa-file text-red"></i> <span>Laporan Summary First Lot</span></a>
            </li>
          </li> -->
        <?php endif;?>
      </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->


      <!-- Main content -->
      <section class="content container-fluid">
        <?php
        if (!empty($page) and !empty($act)) {
          $files = 'pages/' . $page . '.' . $act . '.php';
        } elseif (!empty($page)) {
          $files = 'pages/' . $page . '.php';
        } else {
          $files = 'pages/home.php';
        }

        if (file_exists($files)) {
          include_once $files;
        } else {
          include_once "blank.php";
        }
        ?>

      </section>
      <div id="ChangePassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="pull-right hidden-xs">
        DIT
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2025 <a href="#">DIT ITTI</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <!--
  <aside class="control-sidebar control-sidebar-dark">

    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>

    <div class="tab-content">

      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>


        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>


      </div>

      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>

      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>

        </form>
      </div>

    </div>
  </aside>
  -->
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED JS SCRIPTS -->


  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- Select2 -->
  <script src="bower_components/select2/dist/js/select2.full.min.js"></script>
  <!-- DataTables -->
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <!-- start - This is for export functionality only -->
  <script src="bower_components/datatables.net-bs/js/dataTables.buttons.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/buttons.flash.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/jszip.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/pdfmake.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/vfs_fonts.js"></script>
  <script src="bower_components/datatables.net-bs/js/buttons.html5.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/buttons.print.min.js"></script>
  <!-- end - This is for export functionality only -->
  <!-- InputMask -->
  <script src="plugins/input-mask/jquery.inputmask.js"></script>
  <script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
  <!-- bootstrap datepicker -->
  <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- bootstrap time picker -->
  <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
  <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
  <script src="bower_components/toast-master/js/jquery.toast.js"></script>
  <?php if ($_GET['p'] == "input-bon-kain" or $_GET['p'] == "Lihat-Data-Cwarna-Dye-New" or $_GET['p'] == "Lihat-Data-Cwarna-Fin-New" 
              or $_GET['p'] == "Lihat-Data-Jahit-New" or $_GET['p'] == "Lihat-Data-Shading" or $_GET['p'] == "ketRecipe"or $_GET['p'] == "LapGagalProses"
              or $_GET['p'] == "FirstLot" or $_GET['p'] == "Lap-Bon"): ?>
        <script src="bower_components/xeditable/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <?php endif; ?>

    <script>
        //turn to popup mode
        $.fn.editable.defaults.mode = 'inline';
              $(document).ready(function () {           
                  $('.analisa').editable({
                      type: 'textarea',
                      url: 'pages/editable/editable_analisa.php', 
                    error: function(response) {
                    alert(response.responseText);
                }
                  });
                  $('.pencegahan').editable({
                      type: 'textarea',
                      url: 'pages/editable/editable_pencegahan.php',
                    error: function(response) {
                    alert(response.responseText);
                }
                  });
            $('.kg_bruto').editable({
                      type: 'text',
                      url: 'pages/editable/editable_kg_bruto.php',
                  });
            $('.akar_penyebab').editable({
                type: 'select2',
                url: 'pages/editable/editable_akar_penyebab.php',
                showbuttons: true,
                disabled: false,
                select2: {
                    multiple: true,
                    tokenSeparators: [',', ' '],
                    width: '120px',
                    dropdownAutoWidth: false
                },
                source: [
                    {value: "MAN", text: "MAN"},
                    {value: "MACHINE", text: "MACHINE"},
                    {value: "METHODE", text: "METHODE"},
                    {value: "MATERIAL", text: "MATERIAL"},
                    {value: "ENVIRONMENT", text: "ENVIRONMENT"}
                ],
                pk: function(params) {
                    return {
                        id: params.pk,
                        name: 'akar_penyebab',
                        value: Array.isArray(params.value) ? params.value.join(',') : params.value
                    };
                },
                success: function(response, newValue) {
                    setTimeout(function() {
                        location.reload();
                    }, 50);
                },
                error: function(response, newValue) {
                    alert('Update gagal: ' + response.responseText);
                }
            });
            $('.kg1').editable({
                      type: 'text',
                      url: 'pages/editable/editable_kg1.php',
                  });
            $('.kg2').editable({
                      type: 'text',
                      url: 'pages/editable/editable_kg2.php',
                  });
            $('.kg3').editable({
                      type: 'text',
                      url: 'pages/editable/editable_kg3.php',
                  });
            $('.pjg1').editable({
                      type: 'text',
                      url: 'pages/editable/editable_pjg1.php',
                  });
            $('.pjg2').editable({
                      type: 'text',
                      url: 'pages/editable/editable_pjg2.php',
                  });
            $('.pjg3').editable({
                      type: 'text',
                      url: 'pages/editable/editable_pjg3.php',
                  });
                  $('.sts_bon').editable({
              type: 'select',
              source: [
                {value: 'Open', text: 'Open'},
                {value: 'Closed', text: 'Closed'},
                {value: 'Cancel', text: 'Cancel'}
              ],
              url: 'pages/editable/editable_sts_bon.php'
            });
          // Editable Gagal Proses
           $('.analisa-gproses-editable').editable({
              rows: 5,
              showbuttons: 'bottom',
              validate: function (value) {
                  if (value.length > 1000) {
                      return 'Input tidak boleh lebih dari 1000 karakter.';
                  }
              },
              params: function (params) {
                  const $el = $(this);
                  params.montemp    = $el.data('montemp');
                  params.hasilcelup = $el.data('hasilcelup');
                  params.schedule   = $el.data('schedule');
                  return params;
              }
          });
           $('.keterangan-gproses-editable').editable({
              rows: 5,
              showbuttons: 'bottom',
              validate: function (value) {
                  if (value.length > 1000) {
                      return 'Input tidak boleh lebih dari 1000 karakter.';
                  }
              },
              params: function (params) {
                  const $el = $(this);
                  params.montemp    = $el.data('montemp');
                  params.hasilcelup = $el.data('hasilcelup');
                  params.schedule   = $el.data('schedule');
                  return params;
              }
          });

          $('.dept-gproses-editable').editable({
              source: 'pages/ajax/get_dept.php', 
              showbuttons: 'bottom',
              
              params: function(params) {
                  var extraData = $(this).data();
                  params.montemp = extraData.montemp;
                  params.hasilcelup = extraData.hasilcelup;
                  params.schedule = extraData.schedule;
                  
                  return params;
              },

              success: function(response, newValue) {
                  console.log('Update berhasil. Nilai baru:', newValue);
              },
              error: function(response, newValue) {
                  console.error('Update gagal.', response.responseText);
              }
          });
          $('.acc-recipe-gproses-editable').editable({
                source: 'pages/ajax/get_acc_recipe.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
          $('.acc-recipe2-gproses-editable').editable({
                source: 'pages/ajax/get_acc_recipe.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });

          // Editable Lap Bon
          $('.persen1-bon-editable').editable({
              showbuttons: 'bottom',    
              validate: function (value) {
                  const cleanValue = value.trim();
                  if (cleanValue === '') {
                      return 'Input tidak boleh kosong.';
                  }
                  const numberValue = parseInt(cleanValue, 10);
                  if (isNaN(numberValue)) {
                      return 'Input harus berupa angka bulat.';
                  }
                  if (numberValue < 0 || numberValue > 100) {
                      return 'Nilai harus antara 0 dan 100.';
                  }
              },
          });
          $('.persen2-bon-editable').editable({
              showbuttons: 'bottom',    
              validate: function (value) {
                  const cleanValue = value.trim();
                  if (cleanValue === '') {
                      return 'Input tidak boleh kosong.';
                  }
                  const numberValue = parseInt(cleanValue, 10);
                  if (isNaN(numberValue)) {
                      return 'Input harus berupa angka bulat.';
                  }
                  if (numberValue < 0 || numberValue > 100) {
                      return 'Nilai harus antara 0 dan 100.';
                  }
              },
          });
          $('.persen3-bon-editable').editable({
              showbuttons: 'bottom',    
              validate: function (value) {
                  const cleanValue = value.trim();
                  if (cleanValue === '') {
                      return 'Input tidak boleh kosong.';
                  }
                  const numberValue = parseInt(cleanValue, 10);
                  if (isNaN(numberValue)) {
                      return 'Input harus berupa angka bulat.';
                  }
                  if (numberValue < 0 || numberValue > 100) {
                      return 'Nilai harus antara 0 dan 100.';
                  }
              },
          });
          $('.persen4-bon-editable').editable({
              showbuttons: 'bottom',    
              validate: function (value) {
                  const cleanValue = value.trim();
                  if (cleanValue === '') {
                      return 'Input tidak boleh kosong.';
                  }
                  const numberValue = parseInt(cleanValue, 10);
                  if (isNaN(numberValue)) {
                      return 'Input harus berupa angka bulat.';
                  }
                  if (numberValue < 0 || numberValue > 100) {
                      return 'Nilai harus antara 0 dan 100.';
                  }
              },
          });
          $('.persen5-bon-editable').editable({
              showbuttons: 'bottom',    
              validate: function (value) {
                  const cleanValue = value.trim();
                  if (cleanValue === '') {
                      return 'Input tidak boleh kosong.';
                  }
                  const numberValue = parseInt(cleanValue, 10);
                  if (isNaN(numberValue)) {
                      return 'Input harus berupa angka bulat.';
                  }
                  if (numberValue < 0 || numberValue > 100) {
                      return 'Nilai harus antara 0 dan 100.';
                  }
              },
          });

          $('.tjawab1-bon-editable').editable({
                source: 'pages/ajax/get_dept.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
          $('.tjawab2-bon-editable').editable({
                source: 'pages/ajax/get_dept.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
          $('.tjawab3-bon-editable').editable({
                source: 'pages/ajax/get_dept.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
          $('.tjawab4-bon-editable').editable({
                source: 'pages/ajax/get_dept.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
          $('.tjawab5-bon-editable').editable({
                source: 'pages/ajax/get_dept.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
          // Editable ketRecipe
            $('.colorist-lab-editable').editable({
                source: 'pages/ajax/get_colorist_lab.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
            $('.colorist-dye-editable').editable({
                source: 'pages/ajax/get_colorist_dye.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
            $('.setting-sebelum-editable').editable({
                source: 'pages/ajax/get_colorist_dye.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
            $('.setting-sesudah-editable').editable({
                source: 'pages/ajax/get_colorist_dye.php',
                showbuttons: false, 
                success: function(response, newValue) {
                    console.log('Update berhasil. Nilai baru:', newValue);
                },
                error: function(response, newValue) {
                    console.error('Update gagal.');
                }
            });
            // $('.setting-sebelum-editable').editable({
            //     rows: 7,
            //     showbuttons: 'bottom',
            //     validate: function(value) {
            //         if (value.length > 1000) {
            //             return 'Input tidak boleh lebih dari 1000 karakter.';
            //         }
            //     }
            // });
            // $('.setting-sesudah-editable').editable({
            //     rows: 7,
            //     showbuttons: 'bottom',
            //     validate: function(value) {
            //         if (value.length > 1000) {
            //             return 'Input tidak boleh lebih dari 1000 karakter.';
            //         }
            //     }
            // });
            $('.suffix1-editable').editable({
                rows: 1,
                showbuttons: 'right'
            });
            $('.suffix2-editable').editable({
                rows: 1,
                showbuttons: 'right'
            });
            $('.analisa-resep-editable').editable({
                rows: 7,
                showbuttons: 'bottom'
            });
            $('.analisa-penyebab-editable').editable({
                rows: 7,
                showbuttons: 'bottom',
                validate: function(value) {
                    if (value.length > 1000) {
                        return 'Input tidak boleh lebih dari 1000 karakter.';
                    }
                }
            });
              $('.status-resep-editable').editable({
                showbuttons: false, 
                source: [
                    { value: '', text: 'Pilih' },
                    { value: 'Belum Analisa', text: 'Belum Analisa' },
                    { value: 'Tidak Oke', text: 'Tidak Oke' },
                    { value: 'Test Celup', text: 'Test Celup' },
                    { value: 'Oke', text: 'Oke' },
                    { value: 'Tidak Analisa', text: 'Tidak Analisa' },
                    { value: 'Follow', text: 'Follow' },
                    { value: 'Review', text: 'Review' },
                    { value: 'Test LAB', text: 'Test LAB' }
                ]
            });
              $('.kestabilan-resep-editable').editable({
                showbuttons: false, 
                source: [
                    { value: '', text: 'Pilih' },
                    { value: '0x', text: '0x' },
                    { value: '1x', text: '1x' },
                    { value: '2x', text: '2x' },
                    { value: '3x', text: '3x' },
                    { value: '4x', text: '4x' },
                    { value: '5x', text: '5x' },
                    { value: '6x', text: '6x' },
                    { value: '7x', text: '7x' },
                    { value: '8x', text: '8x' },
                    { value: '9x', text: '9x' },
                    { value: '10x', text: '10x' }
                ]
            });
              $('.dept-penyebab-editable').editable({
                showbuttons: false, 
                source: [
                    { value: '', text: 'Pilih' },
                    { value: 'LAB', text: 'LAB' },
                    { value: 'DYE', text: 'DYE' },
                    { value: 'CQA', text: 'CQA' },
                    { value: 'LAB/DYE', text: 'LAB/DYE' },
                    { value: 'LAB/CQA', text: 'LAB/CQA' },
                    { value: 'DYE/CQA', text: 'DYE/CQA' },
                    { value: 'LAB/DYE/CQA', text: 'LAB/DYE/CQA' },
                    { value: 'DEPT LAIN', text: 'DEPT LAIN' }
                ]
            });
              $('.akar-penyebab-editable').editable({
                showbuttons: false, 
                source: [
                    { value: '', text: 'Pilih' },
                    { value: 'MAN', text: 'MAN' },
                    { value: 'MACHINE', text: 'MACHINE' },
                    { value: 'METHODE', text: 'METHODE' },
                    { value: 'MATERIAL', text: 'MATERIAL' },
                    { value: 'MEASUREMENT', text: 'MEASUREMENT' },
                    { value: 'ENVIRONMENT', text: 'ENVIRONMENT' }
                ]
            });
              $('.ket-hitung-editable').editable({
                showbuttons: false, 
                source: [
                    { value: '', text: 'Pilih' },
                    { value: '1', text: '✔️' }, // Centang
                    { value: '0', text: '❌' }  // Silang
                ]
            });
              $('.tindakan-perbaikan-editable').editable({
                showbuttons: false, 
                source: [
                    { value: '', text: 'Pilih' },
                    { value: 'SETTING RESEP', text: 'SETTING RESEP' },
                    { value: 'MATCHING ULANG', text: 'MATCHING ULANG' },
                    { value: 'TEST CELUP LAGI', text: 'TEST CELUP LAGI' },
                    { value: '-', text: '-' }
                ]
            });
             $('.resep-editable').editable({
                showbuttons: false,
                source: [
                    { value: '', text: 'Pilih' },
                    { value: 'Baru', text: 'R.B' },
                    { value: 'Lama', text: 'R.L' },
                    { value: 'Setting', text: 'R.S' }
                ]
            });
          // Editable lap cwarna dye
            $('.tgl_celup').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_cwarna_dye_tgl_celup.php',
            });
            $('.jml_roll').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_cwarna_dye_jml_roll.php',
            });
            $('.bruto').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_cwarna_dye_bruto.php',
            });
            $('.sts_warna').editable({
                type: 'select',
                url: 'pages/editable/editable_stswarna.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                },
                {
                    value: "OK",
                    text: "OK"
                },
                {
                    value: "TOLAK BASAH BEDA WARNA",
                    text: "TOLAK BASAH BEDA WARNA"
                },
                {
                    value: "TOLAK BASAH LUNTUR",
                    text: "TOLAK BASAH LUNTUR"
                },
                {
                    value: "TOLAK BASAH BEDA WARNA + LUNTUR",
                    text: "TOLAK BASAH BEDA WARNA + LUNTUR"
                },
                {
                    value: "DISPOSISI",
                    text: "DISPOSISI"
                }
                ]
            });
            $('.colorist_qcf').editable({
                type: 'select',
                url: 'pages/editable/editable_colorist_qcf.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "AGUNG",
                    text: "AGUNG"
                }, {
                    value: "AGUS",
                    text: "AGUS"
                }, {
                    value: "ANDI",
                    text: "ANDI"
                }, {
                    value: "DEWI",
                    text: "DEWI"
                }, {
                    value: "FERRY",
                    text: "FERRY"
                }, {
                    value: "PRIMA",
                    text: "PRIMA"
                }, {
                    value: "RUDI",
                    text: "RUDI"
                }, {
                    value: "TRI",
                    text: "TRI"
                }, {
                    value: "WAWAN",
                    text: "WAWAN"
                }, {
                    value: "UJUK",
                    text: "UJUK"
                }]
            });
            $('.review_qcf_dye').editable({
                type: 'select',
                url: 'pages/editable/editable_review_qcf_dye.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "AGUNG",
                    text: "AGUNG"
                }, {
                    value: "DEWI",
                    text: "DEWI"
                }, {
                    value: "FERRY",
                    text: "FERRY"
                }]
            });
            $('.remark_qcf_dye').editable({
                type: 'select',
                url: 'pages/editable/editable_remark_qcf_dye.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "SESUAI",
                    text: "SESUAI"
                }, {
                    value: "TIDAK SESUAI",
                    text: "TIDAK SESUAI"
                }]
            });
            $('.ket_cdye').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_ket_cdye.php',
            });
            $('.disposisi_cdye').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_disposisi_cdye.php',
            });
            $('.grouping_dye').editable({
                type: 'select',
                url: 'pages/editable/editable_groupingdye.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "A",
                    text: "A"
                }, {
                    value: "B",
                    text: "B"
                }, {
                    value: "C",
                    text: "C"
                }, {
                    value: "D",
                    text: "D"
                }]
            });
            $('.hue_dye').editable({
                type: 'select',
                url: 'pages/editable/editable_huedye.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "Red",
                    text: "Red"
                }, {
                    value: "Yellow",
                    text: "Yellow"
                }, {
                    value: "Green",
                    text: "Green"
                }, {
                    value: "Blue",
                    text: "Blue"
                }]
            });
          //Cocok Warna Fin
            $('.sts_fin').editable({
                type: 'select',
                url: 'pages/editable/editable_stsfin.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "OK",
                    text: "OK"
                }, {
                    value: "BW",
                    text: "BW"
                }, {
                    value: "TBD",
                    text: "TBD"
                }]
            });
            $('.code_proses').editable({
                type: 'select',
                url: 'pages/editable/editable_codeproses.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "Fin",
                    text: "Fin"
                }, {
                    value: "Fin 1X",
                    text: "Fin 1X"
                }, {
                    value: "Pdr",
                    text: "Pdr"
                }, {
                    value: "Oven",
                    text: "Oven"
                }, {
                    value: "Comp",
                    text: "Comp"
                }, {
                    value: "Setting",
                    text: "Setting"
                }, {
                    value: "AP",
                    text: "AP"
                }, {
                    value: "PB",
                    text: "PB"
                }]
            });
            $('.review_qcf').editable({
                type: 'select',
                url: 'pages/editable/editable_review_qcf.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "AGUNG",
                    text: "AGUNG"
                }, {
                    value: "DEWI",
                    text: "DEWI"
                }, {
                    value: "FERRY",
                    text: "FERRY"
                }]
            });
            $('.remark_qcf').editable({
                type: 'select',
                url: 'pages/editable/editable_remark_qcf.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "SESUAI",
                    text: "SESUAI"
                }, {
                    value: "TIDAK SESUAI",
                    text: "TIDAK SESUAI"
                }]
            });
            $('.ket_fin').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_ket_fin.php',
            });
            $('.ket_tbrol').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_ket_tbrol.php',
            });
            $('.spectro_dye').editable({
                type: 'select',
                url: 'pages/editable/editable_spectro_dye.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "1",
                    text: '✔'
                }, {
                    value: "0",
                    text: '✖'
                }]
            });
            $('.spectro_fin').editable({
                type: 'select',
                disabled: false,
                url: 'pages/editable/editable_spectro_fin.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "1",
                    text: '✔'
                }, {
                    value: "0",
                    text: '✖'
                }]
            });
            $('.colorist_qcf_fin').editable({
                type: 'select',
                url: 'pages/editable/editable_colorist_qcf_fin.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "AGUNG",
                    text: "AGUNG"
                }, {
                    value: "AGUS",
                    text: "AGUS"
                }, {
                    value: "ANDI",
                    text: "ANDI"
                }, {
                    value: "DEWI",
                    text: "DEWI"
                }, {
                    value: "FERRY",
                    text: "FERRY"
                }, {
                    value: "PRIMA",
                    text: "PRIMA"
                }, {
                    value: "RUDI",
                    text: "RUDI"
                }, {
                    value: "TRI",
                    text: "TRI"
                }, {
                    value: "WAWAN",
                    text: "WAWAN"
                }, {
                    value: "UJUK",
                    text: "UJUK"
                }]
            });
            $('.disposisi_fin').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_disposisi_fin.php',
            });
            $('.grouping_fin').editable({
                type: 'select',
                url: 'pages/editable/editable_groupingfin.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "A",
                    text: "A"
                }, {
                    value: "B",
                    text: "B"
                }, {
                    value: "C",
                    text: "C"
                }, {
                    value: "D",
                    text: "D"
                }]
            });
            $('.hue_fin').editable({
                type: 'select',
                url: 'pages/editable/editable_huefin.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "Red",
                    text: "Red"
                }, {
                    value: "Yellow",
                    text: "Yellow"
                }, {
                    value: "Green",
                    text: "Green"
                }, {
                    value: "Blue",
                    text: "Blue"
                }]
            });
          //Lap Jahit
            $('.sts_jahit').editable({
                type: 'select',
                url: 'pages/editable/editable_stsjahit.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "OK",
                    text: "OK"
                }, {
                    value: "BEDA WARNA",
                    text: "BEDA WARNA"
                }, {
                    value: "BELUM OK",
                    text: "BELUM OK"
                }]
            });
            $('.ket_jahit').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_ket_jahit.php',
            });
            $('.shift_jahit').editable({
                type: 'select',
                url: 'pages/editable/editable_shiftjahit.php',
                showbuttons: false,
                disabled: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "A",
                    text: "A"
                }, {
                    value: "B",
                    text: "B"
                }, {
                    value: "C",
                    text: "C"
                }]
            });
            $('.lot_body').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_lot_body.php',
            });
            $('.colorist_qcf_jahit').editable({
                type: 'select',
                url: 'pages/editable/editable_colorist_qcf_jahit.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "AGUNG",
                    text: "AGUNG"
                }, {
                    value: "AGUS",
                    text: "AGUS"
                }, {
                    value: "ANDI",
                    text: "ANDI"
                }, {
                    value: "DEWI",
                    text: "DEWI"
                }, {
                    value: "RUDI",
                    text: "RUDI"
                }]
            });
            $('.disposisi_jahit').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_disposisi_jahit.php',
            });
          //Lap Potong
            $('.cmtinternal').editable({
                type: 'text',
                url: 'pages/editable/editable_cmtinternal.php',
            });
          //Sisa Siap Packing
            $('.sisa_packing').editable({
                type: 'text',
                url: 'pages/editable/editable_sisapacking.php',
            });
          //Lap Shading
            $('.review_shading').editable({
                type: 'select',
                url: 'pages/editable/editable_review_shading.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "AGUNG",
                    text: "AGUNG"
                }, {
                    value: "DEWI",
                    text: "DEWI"
                }, {
                    value: "AGUS",
                    text: "AGUS"
                }, {
                    value: "RUDI",
                    text: "RUDI"
                }, {
                    value: "ANDI",
                    text: "ANDI"
                }]
            });
            $('.remark_shading').editable({
                type: 'select',
                url: 'pages/editable/editable_remark_shading.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "SESUAI",
                    text: "SESUAI"
                }, {
                    value: "TIDAK SESUAI",
                    text: "TIDAK SESUAI"
                }]
            });
             $('.jml_yard_inspeksi').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_yard_inspek.php',
            });
             $('.edit-catatan').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_catatan.php',
            });
             $('.jml_yard_inspeksi2').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_yard_inspek2.php',
            });
            $('.jml_roll_inspeksi').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_roll_inspek.php',
            });
            $('.jml_roll_inspeksi2').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_roll_inspek2.php',
            });
            $('.qty_inspeksi').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_qty_inspek.php',
            });
            $('.qty_inspeksi2').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_qty_inspek2.php',
            });
            $('.qty_bs').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_qty_bs.php',
            });
            $('.disposisi_shading').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_disposisi_shading.php',
            });
            $('.comment_shading').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_comment_shading.php',
            });
            $('.lot_legacy_beda_roll').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_lot_legacy_beda_roll.php',
            });
            $('.comment_beda_roll').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_comment_beda_roll.php',
            });
            $('.pengarsipan_shading').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_pengarsipan_shading.php',
            });
          //Lap Beda Roll
            $('.review_beda_roll').editable({
                type: 'select',
                url: 'pages/editable/editable_review_beda_roll.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "AGUNG",
                    text: "AGUNG"
                }, {
                    value: "DEWI",
                    text: "DEWI"
                }, {
                    value: "AGUS",
                    text: "AGUS"
                }, {
                    value: "RUDI",
                    text: "RUDI"
                }, {
                    value: "ANDI",
                    text: "ANDI"
                }]
            });
            $('.remark_beda_roll').editable({
                type: 'select',
                url: 'pages/editable/editable_remark_beda_roll.php',
                disabled: false,
                showbuttons: false,
                source: [{
                    value: "",
                    text: ""
                }, {
                    value: "SESUAI",
                    text: "SESUAI"
                }, {
                    value: "TIDAK SESUAI",
                    text: "TIDAK SESUAI"
                }]
            });
            $('.disposisi_beda_roll').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_disposisi_beda_roll.php',
            });
            $('.comment_beda_roll').editable({
                type: 'text',
                disabled: false,
                url: 'pages/editable/editable_comment_beda_roll.php',
            });

        })
    </script>
      
  <script>
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
      }),
      //Date picker
      $('#datepicker1').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
      }),
      //Date picker
      $('#datepicker2').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
      }),
      //Date picker
      $('#datepicker3').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
      });
    //Timepicker
    $('#timepicker').timepicker({
      showInputs: false
    })
  </script>
  <script>
    $(function() {

      $('#example1').DataTable({
        'scrollX': true,
        'scrollY': '350px',
        'paging': false,
        dom: 'Bfrtip',
        buttons: [
          'excel',
        ]
      });
       $('#example99').DataTable({
        'scrollX': true,
        'scrollY': '390px',
        'paging': true,
        dom: 'Bfrtip',
        buttons: [
          // 'excel',
        ]
      });
      $('#example2').DataTable();
      $('#example3').DataTable({
        'scrollX': true,
        dom: 'Bfrtip',
        buttons: [
          'excel',
          {
            orientation: 'portrait',
            pageSize: 'LEGAL',
            extend: 'pdf',
            footer: true,
          },
        ]
      });
      $('#example4').DataTable({
        'paging': false,
        dom: 'Bfrtip',
        buttons: [
          'excel',
        ]  
      });
      $('#example5').DataTable({
        'paging': true,
        'ordering': false,
        'info': false,
        'searching': true
      });
      $('#tblr1').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr2').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr3').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr4').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr5').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr6').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr7').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr8').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr9').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr10').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr11').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr12').DataTable({
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false
      });
      $('#tblr13').DataTable()
      $('#tblr14').DataTable()
      $('#tblr15').DataTable()
      $('#tblr16').DataTable()
      $('#tblr17').DataTable()
      $('#tblr18').DataTable()
      $('#tblr19').DataTable()
      $('#tblr20').DataTable()
      $('#example10').DataTable({
        'scrollX': true,
        'scrollY': '600px',
        'paging': false,
        dom: 'Bfrtip',
        buttons: [
          'excel',
        ]


      });	
      $('#example110').DataTable({
        'scrollX': true,
        'scrollY': '600px',
        'paging': false,
        dom: 'Bfrtip',
        buttons: [
          'excel',
        ]


      });
      //First Lot
        $('.editable-demand').editable({
            type: 'select',
            success: function(response, newValue) {
                if (response === 'OK') {
                    // refresh halaman setelah berhasil simpan
                    location.reload();
                }
            }
        });

        $('.editable-lot').editable({
            type: 'select'
        });

        $('.editable-submit-round').editable({
            validate: function(value) {
                if ($.trim(value) === '') {
                    return 'Wajib diisi';
                }
                if (!/^\d+$/.test(value)) {
                    return 'Hanya boleh angka';
                }
                if (value.length > 4) {
                    return 'Maksimal 4 digit';
                }
            }
        });
        $('.editable-comm-int-qc').editable({ 
          validate: function(value) {
            if ($.trim(value) === '') {
                return 'Wajib diisi';
            }
            if (value.length > 200) {
                return 'Maksimal 200 karakter';
            }
          }
        });

        $('.editable-comm-indra').editable();

        $('.editable-comm-duc').editable();

        $(document).on('change', '.tgl-kirim', function() {
          let pk   = $(this).data('pk');
          let date = $(this).val();

          $.ajax({
              url: 'pages/editable/save_tgl_kirim.php',
              type: 'POST',
              data: { pk: pk, value: date },
              success: function(res) {
                  console.log("Response:", res);
                  if (res === 'OK') {
                      alert('Tanggal berhasil disimpan');
                  } else {
                      alert('Gagal simpan: ' + res);
                  }
              },
              error: function(xhr, status, error) {
                  console.log("Error:", status, error);
                  alert('Terjadi error koneksi: ' + status + ' - ' + error);
              }
          });
        });
        
        $(document).on('change', '.tgl-approve', function() {
          let pk   = $(this).data('pk');
          let date = $(this).val();

          $.ajax({
              url: 'pages/editable/save_tgl_approve.php',
              type: 'POST',
              data: { pk: pk, value: date },
              success: function(res) {
                  console.log("Response:", res);
                  if (res === 'OK') {
                      alert('Tanggal berhasil disimpan');
                  } else {
                      alert('Gagal simpan: ' + res);
                  }
              },
              error: function(xhr, status, error) {
                  console.log("Error:", status, error);
                  alert('Terjadi error koneksi: ' + status + ' - ' + error);
              }
          });
        });
    })
  </script>
  <!-- Javascript untuk popup modal Edit-->
  <script type="text/javascript">
    $(document).ready(function() {

    });
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()
    });
  </script>
  <script type="text/javascript">
    //            jika dipilih, PO akan masuk ke input dan modal di tutup
    $(document).on('click', '.pilih', function(e) {
      document.getElementById("no_po").value = $(this).attr('data-po');
      document.getElementById("no_po").focus();
      $('#myModal').modal('hide');
    });
    //            jika dipilih, BON akan masuk ke input dan modal di tutup
    $(document).on('click', '.pilih-bon', function(e) {
      document.getElementById("no_bon").value = $(this).attr('data-bon');
      document.getElementById("no_bon").focus();
      $('#myModal').modal('hide');
    });
    // jika dipilih, Kode Benang akan masuk ke input dan modal di tutup
    $(document).on('click', '.pilih-kd', function(e) {
      document.getElementById("kd").value = $(this).attr('data-kd');
      document.getElementById("kd").focus();
      $('#myModal').modal('hide');
    });
    $(document).on('click', '.detail_roll_shading', function (e) {
            var m = $(this).attr("id");
            $.ajax({
                url: "pages/detail_roll_shading.php",
                type: "GET",
                data: {
                    id: m,
                },
                success: function (ajaxData) {
                    $("#DetailRollShading").html(ajaxData);
                    $("#DetailRollShading").modal('show', {
                        backdrop: 'true'
                    });
                }
            });
        });
    $(document).on('click', '.mesin_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/mesin_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#MesinEdit").html(ajaxData);
          $("#MesinEdit").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.staff_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/staff_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#StaffEdit").html(ajaxData);
          $("#StaffEdit").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.potong_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/potong_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#PotongEdit").html(ajaxData);
          $("#PotongEdit").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.stop_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/stop_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#StopMesin").html(ajaxData);
          $("#StopMesin").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.edit_shift', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/shift_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#EditShift").html(ajaxData);
          $("#EditShift").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.edit_jammasukkain', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/jammasukkain_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#EditJamMasukKain").html(ajaxData);
          $("#EditJamMasukKain").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.edit_stscelup', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/stspro_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#EditStsCelup").html(ajaxData);
          $("#EditStsCelup").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.user_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/user_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#UserEdit").html(ajaxData);
          $("#UserEdit").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.news_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/news_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#NewsEdit").html(ajaxData);
          $("#NewsEdit").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.detail_status', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/cek-status-mesin.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#CekDetailStatus").html(ajaxData);
          $("#CekDetailStatus").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.detail_status_orgatex', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/cek-status-mesin-orgatex.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#CekDetailStatusOrgatex").html(ajaxData);
          $("#CekDetailStatusOrgatex").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });

    $(document).on('click', '.edit_status_mesin', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/edit-status-mesin.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#EditStatusMesin").html(ajaxData);
          $("#EditStatusMesin").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.detail_kartu', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/detail-kartu.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#DetailKartu").html(ajaxData);
          $("#DetailKartu").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.schedule_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/schedule_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#ScheduleEdit").html(ajaxData);
          $("#ScheduleEdit").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.tambah_analisa', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/tambah_analisa.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#TambahAnalisa").html(ajaxData);
          $("#TambahAnalisa").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.analisa_masalah', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/analisa_masalah.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#AnalisaMasalah").html(ajaxData);
          $("#AnalisaMasalah").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.tambah_analisa_new', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/tambah_analisa_new.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#TambahAnalisaNew").html(ajaxData);
          $("#TambahAnalisaNew").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.schedule_edit1', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/schedule_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#ScheduleEdit1").html(ajaxData);
          $("#ScheduleEdit1").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.resep', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/resep.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#Resep").html(ajaxData);
          $("#Resep").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.std_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/std_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#StdEdit").html(ajaxData);
          $("#StdEdit").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.shift_edit1', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/shift1_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#ShiftEdit1").html(ajaxData);
          $("#ShiftEdit1").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.edit_sts_dok', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/edit_sts_dok.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#EditStsDok").html(ajaxData);
          $("#EditStsDok").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.tambah_inout', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/tambah_inout.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#TambahInOut").html(ajaxData);
          $("#TambahInOut").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.detail_inout', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/detail_inout.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#DetailInOut").html(ajaxData);
          $("#DetailInOut").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.dokumen_edit', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/dokumen_edit.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#EditDok").html(ajaxData);
          $("#EditDok").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.posisi_kk', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/posisikk.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#PosisiKK").html(ajaxData);
          $("#PosisiKK").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.open_change_password', function(e) {
      var m = $(this).attr("id");
      $.ajax({
        url: "pages/change_password.php",
        type: "GET",
        data: {
          id: m,
        },
        success: function(ajaxData) {
          $("#ChangePassword").html(ajaxData);
          $("#ChangePassword").modal('show', {
            backdrop: 'true'
          });
        }
      });
    });
    $(document).on('click', '.detail_ncp', function (e) {
            var m = $(this).attr("id");
            $.ajax({
                url: "pages/detailncp.php",
                type: "GET",
                data: {
                    id: m,
                },
                success: function (ajaxData) {
                    $("#DetailNCP").html(ajaxData);
                    $("#DetailNCP").modal('show', {
                        backdrop: 'true'
                    });
                }
            });
        });
    //            tabel lookup KO status terima
    $(function() {
      $("#lookup").dataTable();
    });
    $(function() {
      $("#lookup1").dataTable();
    });
    $(function() {
      $("#lookup2").dataTable();
    });
  </script>
  <script type="text/javascript">
    function bukaInfo() {
      $('#myModal').modal('show');
    }
    $(function() {
      //Timepicker

      $('.timepicker').timepicker({
        minuteStep: 1,
        showInputs: true,
        showMeridian: false,
        defaultTime: false

      })
      $('.timepicker1').timepicker({
        template: 'dropdown'
      })
    })
  </script>

</body>

</html>