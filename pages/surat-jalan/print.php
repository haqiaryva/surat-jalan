<?php
require_once '../../config/database.php';

$db = new Database();
$conn = $db->connect();

$id = $_GET['id'];

// Ambil data surat jalan
$query = "SELECT sj.*, p.nama_pelanggan, p.alamat as alamat_pelanggan, p.telepon as telp_pelanggan
          FROM surat_jalan sj
          LEFT JOIN pelanggan p ON sj.id_pelanggan = p.id
          WHERE sj.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$surat = $stmt->get_result()->fetch_assoc();

// Ambil detail barang
$detailQuery = "SELECT * FROM detail_surat_jalan WHERE id_surat_jalan = ?";
$stmtDetail = $conn->prepare($detailQuery);
$stmtDetail->bind_param("i", $id);
$stmtDetail->execute();
$details = $stmtDetail->get_result();

// Ambil data perusahaan
$perusahaan = $conn->query("SELECT * FROM perusahaan LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - <?php echo $surat['no_surat']; ?></title>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0;
            font-size: 11px;
        }

        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-left,
        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
            background: #f9f9f9;
        }

        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }

        .info-box p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table th {
            background: #34495e;
            color: white;
            font-weight: bold;
        }

        .signature {
            margin-top: 50px;
            display: table;
            width: 100%;
        }

        .signature div {
            display: table-cell;
            width: 33.33%;
            text-align: center;
        }

        .signature-box {
            height: 145px;
            margin-bottom: 10px;
            
        }

        .btn-print {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 10px 5px;
        }

        .btn-print:hover {
            background: #2980b9;
        }

        .btn-back {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 5px;
        }

        .title-surat {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <a href="list.php" class="btn-back">← Kembali</a>
        <button onclick="window.print()" class="btn-print">Cetak Surat</button>
    </div>

    <div class="header">
        <img src="../../img/logo_company.jpg" alt="CV. Panca Karya Nova" style="max-width:100%; height:auto;">
    </div>

    <div class="title-surat">SURAT JALAN</div>

    <div class="info-section">
        <div class="info-left">
            <div class="info-box">
                <h3>Informasi Surat</h3>
                <p><strong>No. Surat:</strong> <?php echo $surat['no_surat']; ?></p>
                <p><strong>Tanggal:</strong> <?php echo date('d F Y', strtotime($surat['tanggal'])); ?></p>
                <?php if (!empty($surat['nomor_po'])): ?>
                <p><strong>Nomor PO:</strong> <?php echo htmlspecialchars($surat['nomor_po']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="info-right">
            <div class="info-box">
                <h3>Kepada</h3>
                <p><strong><?php echo htmlspecialchars($surat['nama_pelanggan']); ?></strong></p>
                <p><?php echo htmlspecialchars($surat['alamat_pelanggan']); ?></p>
                <p>Telp: <?php echo htmlspecialchars($surat['telp_pelanggan']); ?></p>
            </div>
        </div>
    </div>

    <div class="info-box">
        <h3>Informasi Pengiriman</h3>
        <p><strong>Tujuan:</strong> <?php echo htmlspecialchars($surat['tujuan']); ?></p>
        <!-- <p><strong>Kendaraan:</strong> <?php echo htmlspecialchars($surat['kendaraan']); ?> | <strong>Sopir:</strong> <?php echo htmlspecialchars($surat['sopir']); ?></p> -->
        <?php if (!empty($surat['keterangan'])): ?>
            <p><strong>Keterangan:</strong> <?php echo htmlspecialchars($surat['keterangan']); ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Barang</th>
                <th width="80">Jumlah</th>
                <th width="80">Satuan</th>
                <!-- <th width="100">Berat (Kg)</th> -->
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $totalBerat = 0;
            while ($detail = $details->fetch_assoc()):
                $totalBerat += $detail['berat'];
            ?>
                <tr>
                    <td style="text-align: center;"><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($detail['nama_barang']); ?></td>
                    <td style="text-align: center;"><?php echo $detail['jumlah']; ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($detail['satuan']); ?></td>
                    <!-- <td style="text-align: right;"><?php echo number_format($detail['berat'], 2); ?></td> -->
                    <td><?php echo htmlspecialchars($detail['keterangan']); ?></td>
                </tr>
            <?php endwhile; ?>
            <!-- <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Berat:</strong></td>
                <td style="text-align: right;"><strong><?php echo number_format($totalBerat, 2); ?> Kg</strong></td>
                <td></td>
            </tr> -->
        </tbody>
    </table>

    <div class="signature">
        <div>
            <p><strong>Pengirim</strong></p>
            <div class="signature-box">
                <img src="../../img/ttd_company.jpg" alt="ttd CV. Panca Karya Nova" style="max-width:100%; height:auto;">
            </div>
            <p>( Sukarji )</p>
        </div>
        <!-- <div>
            <p><strong>Sopir</strong></p>
            <div class="signature-box"></div>
            <p><?php echo htmlspecialchars($surat['sopir']); ?></p>
        </div> -->
        <div>
            <p><strong>Penerima</strong></p>
            <div class="signature-box"></div>
            <p>( <?php echo htmlspecialchars($surat['nama_pelanggan']); ?> )</p>
        </div>
    </div>

    <p style="margin-top: 30px; font-size: 10px; text-align: center; color: #7f8c8d;">
        Dicetak pada: <?php echo date('d F Y H:i:s'); ?>
    </p>
</body>

</html>