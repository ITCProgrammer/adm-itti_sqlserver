<?php
include "../../koneksi.php"; // Pastikan file ini benar-benar memuat koneksi DB2
if(isset($_POST['id']) && isset($_POST['penanggungjawabbuyer'])){
  $id = intval($_POST['id']);
  $penanggungjawabbuyer = mysqli_real_escape_string($con, $_POST['penanggungjawabbuyer']);

  $sql = "UPDATE tbl_hasilcelup SET penanggungjawabbuyer = '$penanggungjawabbuyer' WHERE id = $id";
  if(mysqli_query($con, $sql)){
    echo "Sukses";
  } else {
    http_response_code(500);
    echo "Gagal: " . mysqli_error($con);
  }
}
?>
