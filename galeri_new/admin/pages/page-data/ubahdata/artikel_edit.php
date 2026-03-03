<?php
include "koneksi.php";

// Ambil data artikel berdasarkan ID
if (!isset($_GET['id'])) {
    echo "<script>alert('ID artikel tidak ditemukan');location='.?hal=artikel'</script>";
    exit;
}

$id = intval($_GET['id']);
$data = mysqli_query($k, "SELECT * FROM artikel WHERE id = $id");
$artikel = mysqli_fetch_assoc($data);

if (!$artikel) {
    echo "<script>alert('Data artikel tidak ditemukan');location='.?hal=artikel'</script>";
    exit;
}
?>

<h3 class="mt-4">Form Edit Artikel</h3>
<hr>

<form method="post" enctype="multipart/form-data">
    <!-- Menu -->
    <div class="mb-3">
        <label class="form-label">Menu</label>
        <select name="menu" class="form-control" required>
            <option value="artikel" <?= $artikel['menu'] == 'artikel' ? 'selected' : '' ?>>Artikel</option>
            <option value="esai" <?= $artikel['menu'] == 'esai' ? 'selected' : '' ?>>Esai</option>
        </select>
    </div>

    <!-- Judul -->
    <div class="mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="txtjudul" value="<?= htmlspecialchars($artikel['judul']) ?>" class="form-control" required>
    </div>

    <!-- Sinopsis -->
    <div class="mb-3">
        <label class="form-label">Sinopsis</label>
        <input type="text" name="txtsinopsis" value="<?= htmlspecialchars($artikel['deskripsi_artikel']) ?>" class="form-control" required>
    </div>

    <!-- Isi Artikel -->
    <div class="mb-3">
        <label class="form-label">Isi</label>
        <textarea name="txtisi" id="txtisi" class="form-control" rows="6" required><?= htmlspecialchars($artikel['isi']) ?></textarea>
        <button type="button" class="btn btn-dark mt-2" onclick="toggleBold('txtisi')">Bold</button>
            <button type="button" class="btn btn-dark mt-2" onclick="toggleItalic('txtisi')">Italic</button>
            <button type="button" class="btn btn-dark mt-2" onclick="insertLink('txtisi')">Masukkan Link</button>
    </div>

    <!-- Gambar saat ini -->
    <div class="mb-3">
        <label class="form-label">Gambar Saat Ini</label><br>
        <img src="../img/<?= $artikel['gambar'] ?>" width="150">
    </div>

    <!-- Ganti gambar -->
    <div class="mb-3">
        <label class="form-label">Ganti Gambar (kosongkan jika tidak ingin mengganti)</label>
        <input type="file" name="gambar" class="form-control">
    </div>

    <!-- Deskripsi gambar -->
    <div class="mb-3">
        <label class="form-label">Deskripsi Gambar</label>
        <input type="text" name="descriptions" value="<?= htmlspecialchars($artikel['deskripsi_gambar']) ?>" class="form-control">
    </div>

    <!-- Tanggal -->
    <div class="mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="txttanggal" value="<?= $artikel['tanggal'] ?>" class="form-control" required>
    </div>

    <!-- Penulis -->
    <div class="mb-3">
        <label class="form-label">Penulis</label>
        <input type="text" name="txtpenulis" value="<?= htmlspecialchars($artikel['penulis']) ?>" class="form-control" required>
    </div>

    <!-- Status -->
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="draft" <?= $artikel['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="publish" <?= $artikel['status'] == 'publish' ? 'selected' : '' ?>>Publish</option>
        </select>
    </div>

    <div class="d-grid">
        <button type="submit" name="update" class="btn btn-success">Update Artikel</button>
    </div>
</form>
<script>
    function toggleBold(textareaId) {
        var textarea = document.getElementById(textareaId);
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var selectedText = textarea.value.substring(start, end);

        if (selectedText.trim() === "") {
            alert("Silakan pilih teks terlebih dahulu.");
            return;
        }

        var textBefore = textarea.value.substring(0, start);
        var textAfter = textarea.value.substring(end);
        var newText;

        if (selectedText.startsWith("<b>") && selectedText.endsWith("</b>")) {
            newText = selectedText.slice(3, -4);
        } else {
            newText = "<b>" + selectedText + "</b>";
        }

        textarea.value = textBefore + newText + textAfter;
    }

    function toggleItalic(textareaId) {
        var textarea = document.getElementById(textareaId);
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var selectedText = textarea.value.substring(start, end);

        if (selectedText.trim() === "") {
            alert("Silakan pilih teks terlebih dahulu.");
            return;
        }

        var textBefore = textarea.value.substring(0, start);
        var textAfter = textarea.value.substring(end);
        var newText;

        if (selectedText.startsWith("<i>") && selectedText.endsWith("</i>")) {
            newText = selectedText.slice(3, -4);
        } else {
            newText = "<i>" + selectedText + "</i>";
        }

        textarea.value = textBefore + newText + textAfter;
    }

    function insertLink(textareaId) {
        var textarea = document.getElementById(textareaId);
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var selectedText = textarea.value.substring(start, end);

        var link = prompt("Masukkan URL:");
        if (link === null || link.trim() === "") {
            alert("URL tidak valid.");
            return;
        }

        var textBefore = textarea.value.substring(0, start);
        var textAfter = textarea.value.substring(end);
        var newText = '<a href="' + link + '">' + selectedText + '</a>';

        textarea.value = textBefore + newText + textAfter;
    }
</script>
<?php
if (isset($_POST['update'])) {
    $judul     = mysqli_real_escape_string($k, $_POST['txtjudul']);
    $sinopsis  = mysqli_real_escape_string($k, $_POST['txtsinopsis']);
    $isi       = mysqli_real_escape_string($k, $_POST['txtisi']);
    $penulis   = mysqli_real_escape_string($k, $_POST['txtpenulis']);
    $deskripsi = mysqli_real_escape_string($k, $_POST['descriptions']);
    $status    = mysqli_real_escape_string($k, $_POST['status']);
    $menu      = mysqli_real_escape_string($k, $_POST['menu']);
    $tanggal   = $_POST['txttanggal'];

    // Gunakan gambar lama secara default
    $gambar_name = $artikel['gambar'];

    // Cek jika ada gambar baru diupload
    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        // Hapus gambar lama jika ada
        $gambar_lama_path = "../img/" . $artikel['gambar'];
        if (file_exists($gambar_lama_path) && is_file($gambar_lama_path)) {
            unlink($gambar_lama_path);
        }

        // Proses upload gambar baru
        $tmp = $_FILES['gambar']['tmp_name'];
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar_name = 'g' . uniqid() . '.' . $ext;
        move_uploaded_file($tmp, "../img/$gambar_name");
    }

    // Query update data artikel
    $update = mysqli_query($k, "UPDATE artikel SET 
        judul = '$judul', 
        deskripsi_artikel = '$sinopsis', 
        isi = '$isi', 
        gambar = '$gambar_name', 
        deskripsi_gambar = '$deskripsi', 
        penulis = '$penulis', 
        tanggal = '$tanggal', 
        status = '$status',
        menu = '$menu'
        WHERE id = $id");

    if ($update) {
        echo "<script>alert('Data berhasil diperbarui');location='.?hal=artikel'</script>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($k);
    }
}
?>
