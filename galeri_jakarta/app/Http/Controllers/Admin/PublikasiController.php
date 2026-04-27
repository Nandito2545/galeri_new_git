<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Publikasi::query();

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
        $publikasi = $query->paginate($limit);

        return view('admin.publikasi.index', compact('publikasi'));
    }

    public function create()
    {
        return view('admin.publikasi.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'           => 'required|max:255',
            'kategori'        => 'required',
            'penulis'         => 'required|max:100',
            'tanggal_publish' => 'required|date',
            'status'          => 'required',
            'ringkasan'       => 'nullable',
            'isi'             => 'required',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file_pdf'        => 'nullable|mimes:pdf|max:10240',
        ]);

        $slug = Str::slug($request->judul);

        $nama_gambar = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $nama_gambar = time() . '-' . $slug . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/publikasi'), $nama_gambar);
        }

        $nama_pdf = null;
        if ($request->hasFile('file_pdf')) {
            $pdf = $request->file('file_pdf');
            $nama_pdf = time() . '-' . $slug . '.pdf';
            $pdf->move(public_path('uploads/publikasi'), $nama_pdf);
        }

        Publikasi::create([
            'judul'           => $request->judul,
            'kategori'        => $request->kategori,
            'thumbnail'       => $nama_gambar,
            'file_pdf'        => $nama_pdf,
            'ringkasan'       => $request->ringkasan,
            'isi'             => $request->isi,
            'penulis'         => $request->penulis,
            'tanggal_publish' => $request->tanggal_publish,
            'status'          => $request->status,
            'views'           => 0,
        ]);

        return redirect()->route('admin.publikasi.index')->with('success', 'Publikasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $publikasi = Publikasi::findOrFail($id);
        return view('admin.publikasi.edit', compact('publikasi'));
    }

    public function update(Request $request, $id)
    {
        $publikasi = Publikasi::findOrFail($id);

        $request->validate([
            'judul'           => 'required|max:255',
            'kategori'        => 'required',
            'penulis'         => 'required|max:100',
            'tanggal_publish' => 'required|date',
            'status'          => 'required',
            'ringkasan'       => 'nullable',
            'isi'             => 'required',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file_pdf'        => 'nullable|mimes:pdf|max:10240',
        ]);

        $slug = Str::slug($request->judul);
        $nama_gambar = $publikasi->thumbnail;
        $nama_pdf = $publikasi->file_pdf;

        if ($request->hasFile('thumbnail')) {
            if ($publikasi->thumbnail && File::exists(public_path('img/publikasi/' . $publikasi->thumbnail))) {
                File::delete(public_path('img/publikasi/' . $publikasi->thumbnail));
            }
            $file = $request->file('thumbnail');
            $nama_gambar = time() . '-' . $slug . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/publikasi'), $nama_gambar);
        }

        if ($request->hasFile('file_pdf')) {
            if ($publikasi->file_pdf && File::exists(public_path('uploads/publikasi/' . $publikasi->file_pdf))) {
                File::delete(public_path('uploads/publikasi/' . $publikasi->file_pdf));
            }
            $pdf = $request->file('file_pdf');
            $nama_pdf = time() . '-' . $slug . '.pdf';
            $pdf->move(public_path('uploads/publikasi'), $nama_pdf);
        }

        $publikasi->update([
            'judul'           => $request->judul,
            'kategori'        => $request->kategori,
            'thumbnail'       => $nama_gambar,
            'file_pdf'        => $nama_pdf,
            'ringkasan'       => $request->ringkasan,
            'isi'             => $request->isi,
            'penulis'         => $request->penulis,
            'tanggal_publish' => $request->tanggal_publish,
            'status'          => $request->status,
        ]);

        return redirect()->route('admin.publikasi.index')->with('success', 'Publikasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $publikasi = Publikasi::findOrFail($id);

        if ($publikasi->thumbnail && File::exists(public_path('img/publikasi/' . $publikasi->thumbnail))) {
            File::delete(public_path('img/publikasi/' . $publikasi->thumbnail));
        }
        if ($publikasi->file_pdf && File::exists(public_path('uploads/publikasi/' . $publikasi->file_pdf))) {
            File::delete(public_path('uploads/publikasi/' . $publikasi->file_pdf));
        }

        $publikasi->delete();
        return redirect()->route('admin.publikasi.index')->with('success', 'Publikasi berhasil dihapus!');
    }

    // Toggle status publish <-> draft
    public function toggleStatus($id)
    {
        $publikasi = Publikasi::findOrFail($id);
        $publikasi->status = ($publikasi->status === 'publish') ? 'draft' : 'publish';
        $publikasi->save();

        return back()->with('success', 'Status publikasi berhasil diubah ke ' . ucfirst($publikasi->status) . '!');
    }

    // Bulk action
    public function bulkAction(Request $request)
    {
        $ids = $request->input('publikasi_id', []);
        $action = $request->input('bulk_action');

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu data terlebih dahulu!');
        }

        if ($action === 'publish') {
            Publikasi::whereIn('id', $ids)->update(['status' => 'publish']);
            return back()->with('success', count($ids) . ' publikasi berhasil diubah ke Publish!');
        }
        if ($action === 'draft') {
            Publikasi::whereIn('id', $ids)->update(['status' => 'draft']);
            return back()->with('success', count($ids) . ' publikasi berhasil diubah ke Draft!');
        }
        if ($action === 'delete') {
            $items = Publikasi::whereIn('id', $ids)->get();
            foreach ($items as $item) {
                if ($item->thumbnail && File::exists(public_path('img/publikasi/' . $item->thumbnail))) {
                    File::delete(public_path('img/publikasi/' . $item->thumbnail));
                }
                if ($item->file_pdf && File::exists(public_path('uploads/publikasi/' . $item->file_pdf))) {
                    File::delete(public_path('uploads/publikasi/' . $item->file_pdf));
                }
                $item->delete();
            }
            return back()->with('success', count($ids) . ' publikasi berhasil dihapus!');
        }

        return back()->with('error', 'Aksi tidak valid!');
    }
}
