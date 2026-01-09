<?php
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
$tahunSebelumnya = $tahun - 1;

$bulan = [
    "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
    "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Filter Tahunan Mesin Stenter</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<h2>Filter Produksi Mesin Stenter per Tahun</h2>

<form method="GET">
    <label for="tahun">Pilih Tahun: </label>
    <select name="tahun" id="tahun">
        <?php for ($i = 2022; $i <= 2050; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $tahun ? "selected" : "" ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <button type="submit">Tampilkan</button>
</form>

<br>

<table>
    <tr>
        <th rowspan="2">BULAN</th>
        <th rowspan="2">FINISHING AKHIR (a)</th>
        <th rowspan="2">PRESET (b)</th>
        <th rowspan="2">TARIK LEBAR (c)</th>
        <th rowspan="2">FIN 1X (d)</th>
        <th colspan="5">MESIN STENTER</th>
        <th rowspan="2">NAIK SUHU (J)</th>
        <th rowspan="2">PADDER (K)</th>
        <th rowspan="2">POTONG PINGGIR (L)</th>
        <th rowspan="2">FIN ULANG (M)</th>
        <th rowspan="2">TOTAL PRODUKSI STENTER</th>
    </tr>
    <tr>
        <th>OVEN STENTER FLEECE (E)</th>
        <th>OVEN FLEECE ULANG (F)</th>
        <th>OVEN STENTER ULANG (G)</th>
        <th>OVEN STENTER (H)</th>
        <th>OVEN STENTER DYEING</th>
    </tr>

    <!-- Total tahun sebelumnya -->
    <tr>
        <td>Total'<?= substr($tahunSebelumnya, 2) ?></td>
        <?php for ($i = 0; $i < 14; $i++): ?>
            <td></td>
        <?php endfor; ?>
    </tr>

    <!-- Desember tahun sebelumnya -->
    <tr>
        <td>Des'<?= substr($tahunSebelumnya, 2) ?></td>
        <?php for ($i = 0; $i < 14; $i++): ?>
            <td></td>
        <?php endfor; ?>
    </tr>

    <!-- Data bulan per tahun yang dipilih -->
    <?php foreach ($bulan as $namaBulan): ?>
        <tr>
            <td><?= $namaBulan . "'" . substr($tahun, 2) ?></td>
            <?php for ($i = 0; $i < 14; $i++): ?>
                <td></td>
            <?php endfor; ?>
        </tr>
    <?php endforeach; ?>

    <!-- Baris penutup -->
    <tr>
        <td>x</td>
        <?php for ($i = 0; $i < 14; $i++): ?>
            <td></td>
        <?php endfor; ?>
    </tr>
</table>

</body>
</html>
