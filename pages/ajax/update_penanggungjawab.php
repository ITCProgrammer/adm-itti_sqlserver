<?php
include "../../koneksi2.php";

if (isset($_POST['id']) && isset($_POST['penanggungjawabbuyer'])) {

  $id = (int) $_POST['id'];
  $penanggungjawabbuyer = $_POST['penanggungjawabbuyer'];

  $sql = "UPDATE db_dying.tbl_hasilcelup
          SET penanggungjawabbuyer = ?
          WHERE id = ?";

  $stmt = sqlsrv_query($con, $sql, [$penanggungjawabbuyer, $id]);

  if ($stmt === false) {
    http_response_code(500);
    echo "Gagal: " . print_r(sqlsrv_errors(), true);
    exit();
  }

  sqlsrv_free_stmt($stmt);
  echo "Sukses";
}
?>