<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesanController extends Controller
{
    public function index()
    {
        $pesan = DB::table('pesan')->orderBy('tanggal_kirim', 'desc')->paginate(10);
        return view('admin.pesan.index', compact('pesan'));
    }

    public function show($id)
    {
        $pesan = DB::table('pesan')->where('id', $id)->first();
        if (!$pesan) {
            abort(404);
        }

        // Tandai sebagai sudah dibaca
        if ($pesan->status_baca == 'belum') {
            DB::table('pesan')->where('id', $id)->update(['status_baca' => 'sudah']);
        }

        return view('admin.pesan.show', compact('pesan'));
    }

    public function destroy($id)
    {
        DB::table('pesan')->where('id', $id)->delete();
        return redirect()->route('admin.pesan.index')->with('success', 'Pesan berhasil dihapus!');
    }
}
