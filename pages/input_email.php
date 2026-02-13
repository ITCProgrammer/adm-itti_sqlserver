<?php
ini_set("error_reporting", 1);
session_start();
include_once '../koneksi.php';

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = str_replace("'", "''", $_POST['nama']);
    $email = str_replace("'", "''", $_POST['email']);
    $departemen = str_replace("'", "''", $_POST['departemen']);
    // Cek apakah data sudah ada
    $cek = sqlsrv_query($cona, "SELECT TOP 1 1 AS x FROM db_adm.master_email WHERE nama='$nama' AND email='$email'");
    $cekRow = ($cek) ? sqlsrv_fetch_array($cek, SQLSRV_FETCH_ASSOC) : false;

    if ($cekRow) {
        $message = '<div class="alert alert-warning">Data dengan nama, email, dan departemen yang sama sudah ada. Data tidak bisa disimpan dua kali.</div>';
    } else {
        $sql = "INSERT INTO db_adm.master_email (nama, email, departemen) VALUES ('$nama', '$email', '$departemen')";
        $ins = sqlsrv_query($cona, $sql);
        if ($ins) {
            echo '<script>alert("Data berhasil disimpan!"); window.location.href = "?p=input_email";</script>';
            exit();
        } else {
            $message = '<div class="alert alert-danger">Gagal menyimpan data: ' . print_r(sqlsrv_errors(), true) . '</div>';
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Input Email</title>
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container" style="margin-top:40px; max-width:500px;">
        <h3>Form Input Email</h3>
        <?php echo $message; ?>
        <form method="post">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="departemen">Departemen</label>
                <select class="form-control" id="departemen" name="departemen" required>
                    <option value="">-- Pilih Departemen --</option>
                    <?php
                    $qdept = sqlsrv_query($cona, "SELECT nama FROM db_adm.tbl_dept ORDER BY nama ASC");
                    while ($d = sqlsrv_fetch_array($qdept, SQLSRV_FETCH_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($d['nama']) . '">' . htmlspecialchars($d['nama']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <div class="container" style="margin-top:40px; max-width:700px;">
        <h4>Daftar Email</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Departemen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = sqlsrv_query($cona, "SELECT nama, email, departemen FROM db_adm.master_email ORDER BY id DESC");
                if ($result) {
                    $no = 1;
                    $has = false;
                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                        $has = true;
                        echo '<tr>
                        <td>' . $no++ . '</td>
                        <td>' . htmlspecialchars($row['nama']) . '</td>
                        <td>' . htmlspecialchars($row['email']) . '</td>
                        <td>' . htmlspecialchars($row['departemen']) . '</td>
                    </tr>';
                    }
                    if (!$has) {
                        echo '<tr><td colspan="4" class="text-center">Belum ada data email.</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" class="text-center">Belum ada data email.</td></tr>';
                }
                ?>
        </tbody>
    </table>
</div>
</body>
</html>
