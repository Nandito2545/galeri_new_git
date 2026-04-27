<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $query = GaleriFolder::query();

        if ($request->filled('search')) {
            $query->where('nama_folder', 'like', '%' . $request->search . '%');
        }

        // withCount('fotos') digunakan untuk menghitung jumlah isi foto per folder
        $folders = $query->withCount('fotos')->orderBy('tanggal_dibuat', 'desc')->paginate(10);

        return view('admin.galeri.index', compact('folders'));
    }

    public function create()
    {
        return view('admin.galeri.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_folder'    => 'required|max:255',
            'deskripsi'      => 'nullable',
            'tanggal_dibuat' => 'required|date',
            'foto_cover'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:publish,draft',
        ]);

        $nama_gambar = null;
        if ($request->hasFile('foto_cover')) {
            $file = $request->file('foto_cover');
            $nama_gambar = time() . '_cover.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/galeri'), $nama_gambar); // Disimpan di folder public/img/galeri/
        }

        GaleriFolder::create([
            'nama_folder'    => $request->nama_folder,
            'deskripsi'      => $request->deskripsi,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'foto_cover'     => $nama_gambar,
            'status'         => $request->status ?? 'publish',
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Folder Galeri berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $folder = GaleriFolder::findOrFail($id);
        return view('admin.galeri.edit', compact('folder'));
    }

    public function update(Request $request, $id)
    {
        $folder = GaleriFolder::findOrFail($id);

        $request->validate([
            'nama_folder'    => 'required|max:255',
            'deskripsi'      => 'nullable',
            'tanggal_dibuat' => 'required|date',
            'foto_cover'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:publish,draft',
        ]);

        $nama_gambar = $folder->foto_cover;

        if ($request->hasFile('foto_cover')) {
            // Hapus gambar cover lama
            if ($folder->foto_cover && File::exists(public_path('img/galeri/' . $folder->foto_cover))) {
                File::delete(public_path('img/galeri/' . $folder->foto_cover));
            }

            $file = $request->file('foto_cover');
            $nama_gambar = time() . '_cover.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/galeri'), $nama_gambar);
        }

        $folder->update([
            'nama_folder'    => $request->nama_folder,
            'deskripsi'      => $request->deskripsi,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'foto_cover'     => $nama_gambar,
            'status'         => $request->status ?? 'publish',
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Folder Galeri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $folder = GaleriFolder::with('fotos')->findOrFail($id);

        // 1. Hapus gambar cover folder
        if ($folder->foto_cover && File::exists(public_path('img/galeri/' . $folder->foto_cover))) {
            File::delete(public_path('img/galeri/' . $folder->foto_cover));
        }

        // 2. Hapus semua file fisik foto di dalam folder ini agar memori server tidak penuh
        foreach ($folder->fotos as $foto) {
            if ($foto->file_foto && File::exists(public_path('img/galeri/isi/' . $foto->file_foto))) {
                File::delete(public_path('img/galeri/isi/' . $foto->file_foto));
            }
        }

        // 3. Hapus data dari database (otomatis terhapus cascade jika tersetting di DB, tp ini cara aman)
        $folder->fotos()->delete(); 
        $folder->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Folder beserta seluruh isinya berhasil dihapus!');
    }

    // Toggle status publish <-> draft
    public function toggleStatus($id)
    {
        $folder = GaleriFolder::findOrFail($id);
        $folder->status = ($folder->status === 'publish') ? 'draft' : 'publish';
        $folder->save();

        return back()->with('success', 'Status galeri berhasil diubah ke ' . ucfirst($folder->status) . '!');
    }
}