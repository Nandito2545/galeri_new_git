<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriFolder;
use App\Models\GaleriFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GaleriFotoController extends Controller
{
    public function index($id)
    {
        $folder = GaleriFolder::findOrFail($id);
        $fotos = GaleriFoto::where('folder_id', $id)->orderBy('tanggal_upload', 'desc')->get();
        
        return view('admin.galeri.isi', compact('folder', 'fotos'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'nama_foto' => 'required|max:255',
            'file_foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'deskripsi' => 'nullable',
        ]);

        $folder = GaleriFolder::findOrFail($id);
        
        $nama_gambar = null;
        if ($request->hasFile('file_foto')) {
            $file = $request->file('file_foto');
            $nama_gambar = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/galeri/isi'), $nama_gambar);
        }

        GaleriFoto::create([
            'folder_id' => $id,
            'nama_foto' => $request->nama_foto,
            'file_foto' => $nama_gambar,
            'deskripsi' => $request->deskripsi,
            'tanggal_upload' => now(),
        ]);

        return redirect()->route('admin.galeri.isi', $id)->with('success', 'Foto berhasil ditambahkan ke galeri!');
    }

    public function destroy($id)
    {
        $foto = GaleriFoto::findOrFail($id);
        $folder_id = $foto->folder_id;

        if ($foto->file_foto && File::exists(public_path('img/galeri/isi/' . $foto->file_foto))) {
            File::delete(public_path('img/galeri/isi/' . $foto->file_foto));
        }

        $foto->delete();

        return redirect()->route('admin.galeri.isi', $folder_id)->with('success', 'Foto berhasil dihapus!');
    }
}
