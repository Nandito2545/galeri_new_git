<?php
include "koneksi.php";
?>

<h3 class="mt-4">Form Tambah Artikel</h3>
<hr>

<style>
    .bold-button {
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        margin-left: 5px;
    }
    body {
        overflow-x: hidden;
    }
    .container, .row, form {
        max-width: 100%;
        overflow-x: hidden;
    }
</style>

<form id="imageForm" method="post" action="" enctype="multipart/form-data">
    <!-- Menu -->
    <div class="row mb-3">
    <div class="col-md-12">
        <label class="form-label">Menu</label>
        <select name="menu" class="form-control" required>
        <option value="artikel">Artikel</option>
        <option value="esai">Esai</option>
        </select>
    </div>
    </div>

    <!-- Judul -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating mb-3 mb-md-0">
                <input name="txtjudul" class="form-control" type="text" placeholder="Masukkan Judul" required />
                <label class="form-label">Judul</label>
            </div>
        </div>
    </div>

    <!-- Sinopsis / Deskripsi Artikel -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating mb-3 mb-md-0">
                <input name="txtsinopsis" class="form-control" type="text" placeholder="Masukkan sinopsis" required />
                <label class="form-label">Sinopsis</label>
            </div>
        </div>
    </div>

    <!-- Isi Artikel -->
    <div class="row mb-3">
        <div class="form-group col-md-12">
            <label class="form-label">Isi</label>
            <textarea id="txtisi" name="txtisi" class="form-control" rows="5" required></textarea>
            <button type="button" class="btn btn-dark mt-2" onclick="toggleBold('txtisi')">Bold</button>
            <button type="button" class="btn btn-dark mt-2" onclick="toggleItalic('txtisi')">Italic</button>
            <button type="button" class="btn btn-dark mt-2" onclick="insertLink('txtisi')">Masukkan Link</button>
        </div>
    </div>

    <!-- Gambar & Deskripsi Gambar -->
    <div class="row mb-1" id="imageFieldsContainer">
        <div class="mb-3 col-md-12">
            <input name="gambar" class="form-control" type="file" required />
            <label class="form-label">Gambar Utama</label>
        </div>

        <div class="mb-3 col-md-12">
            <input name="descriptions" type="text" class="form-control" placeholder="Deskripsi Gambar" />
            <label class="form-label">Masukkan Deskripsi Gambar</label>
        </div>
    </div>

    <!-- Tanggal -->
    <div class="row mb-3 mt-3">
        <div class="col-md-12">
            <div class="form-floating mb-3 mb-md-0">
                <input name="txttanggal" class="form-control" type="date" required />
                <label class="form-label">Tanggal</label>
            </div>
        </div>
    </div>

    <!-- Penulis -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating mb-3 mb-md-0">
                <input name="txtpenulis" class="form-control" type="text" placeholder="Masukkan Penulis" required />
                <label class="form-label">Penulis</label>
            </div>
        </div>
    </div>

    <!-- Status (Draft/Publish) -->
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label">Status Artikel</label>
            <select name="status" class="form-control" required>
                <option value="draft">Draft</option>
                <option value="publish">Publish</option>
            </select>
        </div>
    </div>


    <!-- Tombol Simpan -->
    <div class="mt-4 mb-0">
        <div class="d-grid">
            <input type="submit" value="Simpan" name="simpan" class="btn btn-dark btn-block">
        </div>
    </div>

    <?php
    if (isset($_POST['simpan'])) {
        $judul     = mysqli_real_escape_string($k, $_POST['txtjudul']);
        $sinopsis  = mysqli_real_escape_string($k, $_POST['txtsinopsis']);
        $isi       = mysqli_real_escape_string($k, $_POST['txtisi']);
        $penulis   = mysqli_real_escape_string($k, $_POST['txtpenulis']);
        $deskripsi = mysqli_real_escape_string($k, $_POST['descriptions']);
        $status = mysqli_real_escape_string($k, $_POST['status']);
        $menu = mysqli_real_escape_string($k, $_POST['menu']);
        $tanggal   = $_POST['txttanggal'];

        $upload_error = "";
        $gambar_name = "";

        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['gambar']['tmp_name'];
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar_name = 'g' . uniqid() . '.' . $ext;

            if (!move_uploaded_file($tmp, "../img/$gambar_name")) {
                $upload_error = "Gagal upload gambar.";
            }
        } else {
            $upload_error = "Gambar belum dipilih.";
        }

        if (empty($upload_error)) {
            $query = mysqli_query($k, "INSERT INTO artikel 
                    (judul, deskripsi_artikel, tanggal, isi, gambar, deskripsi_gambar, penulis, status, menu) 
                    VALUES ('$judul', '$sinopsis', '$tanggal', '$isi', '$gambar_name', '$deskripsi', '$penulis', '$status', '$menu')");


            if ($query) {
                echo "<script>alert('Data berhasil disimpan');location='.?hal=artikel'</script>";
            } else {
                echo "Gagal menyimpan ke database: " . mysqli_error($k);
            }
        } else {
            echo $upload_error;
        }
    }
    ?>
</form>

<!-- JavaScript: Bold / Italic / Link -->
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
