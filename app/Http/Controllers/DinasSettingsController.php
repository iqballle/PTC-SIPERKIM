<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DinasSettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Pastikan yang akses benar-benar akun dinas
        if ($user->role !== 'dinas') {
            abort(403, 'Hanya akun dinas yang boleh mengakses halaman ini.');
        }

        return view('dinas.settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'dinas') {
            abort(403, 'Hanya akun dinas yang boleh mengakses halaman ini.');
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'max:2048'], // maks 2MB
        ]);

        // Upload / ganti foto
        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->photo_path = $path;
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        $user->save();

        return redirect()
            ->route('dinas.settings.index')
            ->with('status', 'Profil dinas berhasil diperbarui.');
    }
}
