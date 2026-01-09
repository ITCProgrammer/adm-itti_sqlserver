<?php
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
//include_once('koneksi.php');
//include 'ajax/logic_chart.php';
?>
<?php
if (!isset($_SESSION['user_id10']) || !isset($_SESSION['pass_id10'])) {
?>
  <script>
    setTimeout("location.href='index.php'", 500);
  </script>
<?php
  die('Illegal Acces');
}
$page  = isset($_GET['p']) ? $_GET['p'] : '';
$act  = isset($_GET['act']) ? $_GET['act'] : '';
$id    = isset($_GET['id']) ? $_GET['id'] : '';
$page  = strtolower($page);
?>
<link rel="stylesheet" href="plugins/highcharts/style_chart.css">

<body>
  <!-- <blockquote style="margin: 0px"> -->
  <div class="container" style="margin-top: -20px; margin-bottom: 10px;">
    <h2 style="font-weight: bold;" class="text-center">Welcome <u><?php echo strtoupper($_SESSION['user_id10']); ?></u> at Indo Taichen Textile Industry</h2>
  </div>
  <!-- </blockquote> -->
 
  

  <!-- ////////////////////////////////////////////////////////////////////// ASSET ////////////////////////////////////////////////////////////////////// -->  
</body>

</html>