<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pustaka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PustakaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pustaka::query();

        if ($request->filled('kategori') && $request->kategori != 'all') {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('judul')) {
            $query->where('judul', 'like', '%' . $request->judul . '%');
        }

        $sort = $request->sort ?? 'created_at';
        if ($sort == 'views') {
            $query->orderBy('views', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $limit = $request->limit ?? 10;
        $pustakas = $query->paginate($limit);

        return view('admin.pustaka.index', compact('pustakas'));
    }

    public function create()
    {
        return view('admin.pustaka.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'           => 'required|max:255',
            'penulis'         => 'required|max:255',
            'kategori'        => 'required',
            'harga'           => 'required|numeric|min:0',
            'penerbit'        => 'nullable|max:255',
            'tanggal_terbit'  => 'nullable|date',
            'isbn'            => 'nullable|max:50',
            'halaman'         => 'nullable|integer|min:1',
            'bahasa'          => 'nullable|max:100',
            'panjang'         => 'nullable|numeric|min:0',
            'lebar'           => 'nullable|numeric|min:0',
            'berat'           => 'nullable|numeric|min:0',
            'deskripsi'       => 'nullable',
            'status'          => 'required|in:publish,draft',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($request->judul);
        $nama_gambar = null;
        
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $nama_gambar = time() . '-' . $slug . '.' . $file->getClientOriginalExtension();
            // Membuat direktori jika belum ada
            if (!File::exists(public_path('img/pustaka'))) {
                File::makeDirectory(public_path('img/pustaka'), 0755, true);
            }
            $file->move(public_path('img/pustaka'), $nama_gambar);
        }

        Pustaka::create([
            'judul'           => $request->judul,
            'penulis'         => $request->penulis,
            'kategori'        => $request->kategori,
            'harga'           => $request->harga,
            'penerbit'        => $request->penerbit,
            'tanggal_terbit'  => $request->tanggal_terbit,
            'isbn'            => $request->isbn,
            'halaman'         => $request->halaman,
            'bahasa'          => $request->bahasa ?? 'Indonesia',
            'panjang'         => $request->panjang,
            'lebar'           => $request->lebar,
            'berat'           => $request->berat,
            'deskripsi'       => $request->deskripsi,
            'status'          => $request->status,
            'thumbnail'       => $nama_gambar,
            'views'           => 0,
        ]);

        return redirect()->route('admin.pustaka.index')->with('success', 'Data Pustaka berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pustaka = Pustaka::findOrFail($id);
        return view('admin.pustaka.edit', compact('pustaka'));
    }

    public function update(Request $request, $id)
    {
        $pustaka = Pustaka::findOrFail($id);

        $request->validate([
            'judul'           => 'required|max:255',
            'penulis'         => 'required|max:255',
            'kategori'        => 'required',
            'harga'           => 'required|numeric|min:0',
            'penerbit'        => 'nullable|max:255',
            'tanggal_terbit'  => 'nullable|date',
            'isbn'            => 'nullable|max:50',
            'halaman'         => 'nullable|integer|min:1',
            'bahasa'          => 'nullable|max:100',
            'panjang'         => 'nullable|numeric|min:0',
            'lebar'           => 'nullable|numeric|min:0',
            'berat'           => 'nullable|numeric|min:0',
            'deskripsi'       => 'nullable',
            'status'          => 'required|in:publish,draft',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($request->judul);
        $nama_gambar = $pustaka->thumbnail;

        if ($request->hasFile('thumbnail')) {
            // Hapus gambar lama
            if ($pustaka->thumbnail && File::exists(public_path('img/pustaka/' . $pustaka->thumbnail))) {
                File::delete(public_path('img/pustaka/' . $pustaka->thumbnail));
            }
            
            $file = $request->file('thumbnail');
            $nama_gambar = time() . '-' . $slug . '.' . $file->getClientOriginalExtension();
            
            if (!File::exists(public_path('img/pustaka'))) {
                File::makeDirectory(public_path('img/pustaka'), 0755, true);
            }
            
            $file->move(public_path('img/pustaka'), $nama_gambar);
        }

        $pustaka->update([
            'judul'           => $request->judul,
            'penulis'         => $request->penulis,
            'kategori'        => $request->kategori,
            'harga'           => $request->harga,
            'penerbit'        => $request->penerbit,
            'tanggal_terbit'  => $request->tanggal_terbit,
            'isbn'            => $request->isbn,
            'halaman'         => $request->halaman,
            'bahasa'          => $request->bahasa ?? 'Indonesia',
            'panjang'         => $request->panjang,
            'lebar'           => $request->lebar,
            'berat'           => $request->berat,
            'deskripsi'       => $request->deskripsi,
            'status'          => $request->status,
            'thumbnail'       => $nama_gambar,
        ]);

        return redirect()->route('admin.pustaka.index')->with('success', 'Data Pustaka berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pustaka = Pustaka::findOrFail($id);

        if ($pustaka->thumbnail && File::exists(public_path('img/pustaka/' . $pustaka->thumbnail))) {
            File::delete(public_path('img/pustaka/' . $pustaka->thumbnail));
        }

        $pustaka->delete();
        return redirect()->route('admin.pustaka.index')->with('success', 'Data Pustaka berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $pustaka = Pustaka::findOrFail($id);
        $pustaka->status = ($pustaka->status === 'publish') ? 'draft' : 'publish';
        $pustaka->save();

        return back()->with('success', 'Status pustaka berhasil diubah ke ' . ucfirst($pustaka->status) . '!');
    }

    public function bulkAction(Request $request)
    {
        $ids = $request->input('pustaka_id', []);
        $action = $request->input('bulk_action');

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu data terlebih dahulu!');
        }

        if ($action === 'publish') {
            Pustaka::whereIn('id', $ids)->update(['status' => 'publish']);
            return back()->with('success', count($ids) . ' data berhasil diubah ke Publish!');
        }
        if ($action === 'draft') {
            Pustaka::whereIn('id', $ids)->update(['status' => 'draft']);
            return back()->with('success', count($ids) . ' data berhasil diubah ke Draft!');
        }
        if ($action === 'delete') {
            $items = Pustaka::whereIn('id', $ids)->get();
            foreach ($items as $item) {
                if ($item->thumbnail && File::exists(public_path('img/pustaka/' . $item->thumbnail))) {
                    File::delete(public_path('img/pustaka/' . $item->thumbnail));
                }
                $item->delete();
            }
            return back()->with('success', count($ids) . ' data berhasil dihapus!');
        }

        return back()->with('error', 'Aksi tidak valid!');
    }
}
