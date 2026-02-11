<?php
ini_set("error_reporting", 1);
session_start();
include_once '../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = '';

// Ambil data bon
$bon = sqlsrv_fetch_array(sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_bonkain WHERE id=$id"), SQLSRV_FETCH_ASSOC);

// Simpan pilihan stok
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah sudah pernah diisi
    $cek = sqlsrv_fetch_array(sqlsrv_query($cona, "SELECT ket_ppc, personil_ppc FROM db_adm.tbl_bonkain WHERE id=$id"), SQLSRV_FETCH_ASSOC);
    if (!empty($cek['ket_ppc']) && !empty($cek['personil_ppc'])) {
        $message = '<div class="alert alert-warning">Status stok sudah pernah diinput dan tidak bisa diubah lagi.</div>';
    } else {
        $pilihan = $_POST['stok'];
        // $personil = isset($_SESSION['nama10']) ? sqlsrv_real_escape_string($cona, $_SESSION['nama10']) : 'PPC';
        $sql = "UPDATE db_adm.tbl_bonkain SET ket_ppc='$pilihan' WHERE id=$id";
        if (sqlsrv_query($cona, $sql)) {
            $message = '<div class="alert alert-success">Pilihan berhasil disimpan!</div>';
        } else {
            $message = '<div class="alert alert-danger">Gagal menyimpan </div>';
            // $message = '<div class="alert alert-danger">Gagal menyimpan: ' . sqlsrv_errors($cona) . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Input Status Stok PPC</title>
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top:40px; max-width:400px;">
    <h3>Status Stok Bon: <span style="color:blue;">#<?php echo htmlspecialchars($bon['no_bon']); ?></span></h3>
    <?php echo $message; ?>
    <form method="post">
        <div class="form-group">
            <label for="stok">Pilih Status Stok</label>
            <select class="form-control" id="stok" name="stok" required>
                <option value="">-- Pilih --</option>
                <option value="Ada stok">Ada stok</option>
                <option value="Tidak Ada Stok">Tidak Ada Stok</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
</body>
</html>
