<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil status saat ini
    $cek = mysqli_query($k, "SELECT status FROM artikel WHERE id = $id");
    if ($row = mysqli_fetch_assoc($cek)) {
        $status_lama = $row['status'];
        $status_baru = ($status_lama === 'publish') ? 'draft' : 'publish';

        // Update ke status baru
        $update = mysqli_query($k, "UPDATE artikel SET status = '$status_baru' WHERE id = $id");
    }
}

// Ganti header dengan redirect JS
echo "<script>location='?hal=artikel'</script>";
exit;
?>
