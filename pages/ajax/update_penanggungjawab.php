<?php
include "../../koneksi.php";

if (isset($_POST['id']) && isset($_POST['penanggungjawabbuyer'])) {

  $id = (int) $_POST['id'];
  $penanggungjawabbuyer = $_POST['penanggungjawabbuyer'];

  $sql  = "UPDATE db_dying.tbl_hasilcelup SET penanggungjawabbuyer = ? WHERE id = ?";
  $stmt = sqlsrv_query($con, $sql, [$penanggungjawabbuyer, $id]);

  if ($stmt === false) {
    http_response_code(500);
    echo "Gagal: " . print_r(sqlsrv_errors(), true);
  } else {
    echo "Sukses";
  }
}
?>
