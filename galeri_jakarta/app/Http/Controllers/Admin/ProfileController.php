<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'no_telepon'  => 'nullable|string|max:20',
            'bio'         => 'nullable|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'no_telepon' => $request->no_telepon,
            'bio'        => $request->bio,
        ];

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil && File::exists(public_path('img/profil/' . $user->foto_profil))) {
                File::delete(public_path('img/profil/' . $user->foto_profil));
            }
            $file = $request->file('foto_profil');
            $nama_foto = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/profil'), $nama_foto);
            $data['foto_profil'] = $nama_foto;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'  => 'required',
            'password_baru'  => 'required|min:6|confirmed',
        ], [
            'password_lama.required'    => 'Password lama wajib diisi.',
            'password_baru.required'    => 'Password baru wajib diisi.',
            'password_baru.min'         => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai!'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success_password', 'Password berhasil diubah!');
    }
}
