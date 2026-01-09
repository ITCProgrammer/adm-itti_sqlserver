<?php
ini_set("error_reporting", 1);
session_start();
include_once '../koneksi.php';

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($cona, $_POST['nama']);
    $email = mysqli_real_escape_string($cona, $_POST['email']);
    $departemen = mysqli_real_escape_string($cona, $_POST['departemen']);
    // Cek apakah data sudah ada
    $cek = mysqli_query($cona, "SELECT 1 FROM master_email WHERE nama='$nama' AND email='$email' LIMIT 1");
    if (mysqli_num_rows($cek) > 0) {
        $message = '<div class="alert alert-warning">Data dengan nama, email, dan departemen yang sama sudah ada. Data tidak bisa disimpan dua kali.</div>';
    } else {
        $sql = "INSERT INTO master_email (nama, email, departemen) VALUES ('$nama', '$email', '$departemen')";
        if (mysqli_query($cona, $sql)) {
            echo '<script>alert("Data berhasil disimpan!"); window.location.href = "?p=input_email";</script>';
            exit();
        } else {
            $message = '<div class="alert alert-danger">Gagal menyimpan data: ' . mysqli_error($cona) . '</div>';
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
                $qdept = mysqli_query($cona, "SELECT nama FROM tbl_dept ORDER BY nama ASC");
                while ($d = mysqli_fetch_assoc($qdept)) {
                    echo '<option value="'.htmlspecialchars($d['nama']).'">'.htmlspecialchars($d['nama']).'</option>';
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
            $result = mysqli_query($cona, "SELECT nama, email, departemen FROM master_email ORDER BY id DESC");
            if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                        <td>'.$no++.'</td>
                        <td>'.htmlspecialchars($row['nama']).'</td>
                        <td>'.htmlspecialchars($row['email']).'</td>
                        <td>'.htmlspecialchars($row['departemen']).'</td>
                    </tr>';
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
