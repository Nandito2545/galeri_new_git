<?php
include "koneksi.php";
$gid = $_GET['id'];
?>

<h3>Tambah Foto ke Galeri</h3>

<form method="post" enctype="multipart/form-data" id="uploadForm">
  <div 
    class="border p-4 mb-3 text-center" 
    id="drop-area"
    style="border: 2px dashed #aaa; cursor: pointer;">
    <p>Drag & drop gambar di sini atau klik untuk memilih</p>
    <input type="file" name="gambar[]" id="fileElem" multiple accept="image/*" style="display:none">
  </div>

  <div id="preview-area" class="row"></div>

  <button type="submit" name="simpan" class="btn btn-dark mt-3">Simpan Semua</button>
</form>

<style>
.preview-item {
  position: relative;
  margin: 10px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 10px;
  width: 220px;
}
.preview-item img {
  max-width: 100%;
  height: auto;
}
.remove-btn {
  position: absolute;
  top: 5px;
  right: 5px;
  background: rgba(255,0,0,0.8);
  color: white;
  border: none;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  cursor: pointer;
  font-size: 16px;
  line-height: 20px;
}
</style>

<script>
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('fileElem');
const previewArea = document.getElementById('preview-area');

let selectedFiles = [];

dropArea.addEventListener('click', () => fileInput.click());

dropArea.addEventListener('dragover', (e) => {
  e.preventDefault();
  dropArea.classList.add('bg-light');
});
dropArea.addEventListener('dragleave', () => {
  dropArea.classList.remove('bg-light');
});
dropArea.addEventListener('drop', (e) => {
  e.preventDefault();
  dropArea.classList.remove('bg-light');
  handleFiles(e.dataTransfer.files);
});

fileInput.addEventListener('change', () => {
  handleFiles(fileInput.files);
});

function handleFiles(files) {
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    if (!file.type.startsWith('image/')) continue;

    selectedFiles.push(file);
    previewFile(file, selectedFiles.length - 1);
  }

  updateFileInput();
}

function previewFile(file, index) {
  const reader = new FileReader();
  reader.onload = (e) => {
    const div = document.createElement('div');
    div.className = 'preview-item col-md-3';
    div.innerHTML = `
      <button type="button" class="remove-btn" onclick="removeFile(${index})">&times;</button>
      <img src="${e.target.result}" class="img-thumbnail mb-2">
      <textarea name="deskripsi[]" class="form-control mb-2" placeholder="Deskripsi foto..."></textarea>
    `;
    div.dataset.index = index;
    previewArea.appendChild(div);
  };
  reader.readAsDataURL(file);
}

function removeFile(index) {
  // Hapus dari array
  selectedFiles[index] = null;

  // Hapus elemen pratinjau
  const previewItems = previewArea.querySelectorAll(`.preview-item`);
  previewItems.forEach(item => {
    if (item.dataset.index == index) item.remove();
  });

  updateFileInput();
}

function updateFileInput() {
  const dataTransfer = new DataTransfer();
  selectedFiles.forEach(file => {
    if (file) dataTransfer.items.add(file);
  });
  fileInput.files = dataTransfer.files;
}
</script>

<?php
if (isset($_POST['simpan'])) {
  if (!$k) {
    die("Koneksi database gagal.");
  }

  foreach ($_FILES['gambar']['tmp_name'] as $i => $tmp) {
    if ($_FILES['gambar']['error'][$i] === UPLOAD_ERR_OK) {
      $originalName = $_FILES['gambar']['name'][$i];
      $ext = pathinfo($originalName, PATHINFO_EXTENSION);
      $newName = 'foto_' . time() . '_' . $i . '.' . $ext;

      if (move_uploaded_file($tmp, "../img/" . $newName)) {
        $desc = isset($_POST['deskripsi'][$i]) ? mysqli_real_escape_string($k, $_POST['deskripsi'][$i]) : '';
        $query = "INSERT INTO isi_galeri (galeri_id, nama_file, deskripsi_foto)
                  VALUES ($gid, '$newName', '$desc')";
        mysqli_query($k, $query);
      }
    }
  }

  echo "<script>alert('Semua gambar berhasil diupload');location='?hal=isi_galeri&id=$gid';</script>";
}
?>
