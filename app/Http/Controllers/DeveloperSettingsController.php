<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeveloperSettingsController extends Controller
{
    /**
     * Tampilkan halaman pengaturan akun developer.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Pastikan hanya developer yang boleh buka halaman ini
        if ($user->role !== 'developer') {
            abort(403);
        }

        return view('developer.settings.index', compact('user'));
    }

    /**
     * Simpan perubahan profil developer.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Pastikan hanya developer yang boleh update
        if ($user->role !== 'developer') {
            abort(403);
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'], // maks 2MB
        ]);

        // Handle upload foto (kalau ada)
        if ($request->hasFile('photo')) {
            // Hapus foto lama kalau ada
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            // Simpan foto baru ke storage/app/public/avatars
            $path = $request->file('photo')->store('avatars', 'public');

            $validated['photo_path'] = $path;
        }

        // Simpan ke database (tanpa pakai ->update() biar Intelephense diam)
        $user->fill($validated);
        $user->save();

        return redirect()
            ->route('developer.settings.index')
            ->with('status', 'Profil berhasil diperbarui.');
    }
}