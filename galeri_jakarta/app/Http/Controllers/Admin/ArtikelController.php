<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::query();

        if ($request->filled('kategori') && $request->kategori != 'all') {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('judul')) {
            $query->where('judul', 'like', '%' . $request->judul . '%');
        }

        $sort = $request->sort ?? 'tanggal_publish';
        if ($sort == 'views') {
            $query->orderBy('views', 'desc');
        } else {
            $query->orderBy('tanggal_publish', 'desc');
        }

        $limit = $request->limit ?? 10;
        $articles = $query->paginate($limit);

        return view('admin.artikel.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.artikel.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'           => 'required|max:255',
            'kategori'        => 'required',
            'penulis'         => 'required|max:100',
            'tanggal_publish' => 'required|date',
            'status'          => 'required',
            'ringkasan'       => 'required',
            'isi'             => 'required',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($request->judul);

        $nama_gambar = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $nama_gambar = time() . '-' . $slug . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $nama_gambar);
        }

        Artikel::create([
            'judul'           => $request->judul,
            'slug'            => $slug,
            'kategori'        => $request->kategori,
            'thumbnail'       => $nama_gambar,
            'ringkasan'       => $request->ringkasan,
            'isi'             => $request->isi,
            'penulis'         => $request->penulis,
            'tanggal_publish' => $request->tanggal_publish,
            'status'          => $request->status,
            'views'           => 0,
        ]);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $request->validate([
            'judul'           => 'required|max:255',
            'kategori'        => 'required',
            'penulis'         => 'required|max:100',
            'tanggal_publish' => 'required|date',
            'status'          => 'required',
            'ringkasan'       => 'required',
            'isi'             => 'required',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($request->judul);
        $nama_gambar = $artikel->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($artikel->thumbnail && File::exists(public_path('img/' . $artikel->thumbnail))) {
                File::delete(public_path('img/' . $artikel->thumbnail));
            }
            $file = $request->file('thumbnail');
            $nama_gambar = time() . '-' . $slug . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $nama_gambar);
        }

        $artikel->update([
            'judul'           => $request->judul,
            'slug'            => $slug,
            'kategori'        => $request->kategori,
            'thumbnail'       => $nama_gambar,
            'ringkasan'       => $request->ringkasan,
            'isi'             => $request->isi,
            'penulis'         => $request->penulis,
            'tanggal_publish' => $request->tanggal_publish,
            'status'          => $request->status,
        ]);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->thumbnail && File::exists(public_path('img/' . $artikel->thumbnail))) {
            File::delete(public_path('img/' . $artikel->thumbnail));
        }

        $artikel->delete();
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus!');
    }

    // Toggle status publish <-> draft per baris
    public function toggleStatus($id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->status = ($artikel->status === 'publish') ? 'draft' : 'publish';
        $artikel->save();

        return back()->with('success', 'Status artikel berhasil diubah ke ' . ucfirst($artikel->status) . '!');
    }

    // Bulk action (publish, draft, delete)
    public function bulkAction(Request $request)
    {
        $ids = $request->input('artikel_id', []);
        $action = $request->input('bulk_action');

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu artikel terlebih dahulu!');
        }

        if ($action === 'publish') {
            Artikel::whereIn('id', $ids)->update(['status' => 'publish']);
            return back()->with('success', count($ids) . ' artikel berhasil diubah ke Publish!');
        }
        if ($action === 'draft') {
            Artikel::whereIn('id', $ids)->update(['status' => 'draft']);
            return back()->with('success', count($ids) . ' artikel berhasil diubah ke Draft!');
        }
        if ($action === 'delete') {
            $articles = Artikel::whereIn('id', $ids)->get();
            foreach ($articles as $a) {
                if ($a->thumbnail && File::exists(public_path('img/' . $a->thumbnail))) {
                    File::delete(public_path('img/' . $a->thumbnail));
                }
                $a->delete();
            }
            return back()->with('success', count($ids) . ' artikel berhasil dihapus!');
        }

        return back()->with('error', 'Aksi tidak valid!');
    }
}