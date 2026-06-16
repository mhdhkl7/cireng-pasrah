<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::whereIn('role', ['customer', 'driver'])
            ->withCount('pesanans')
            ->latest()
            ->paginate(15);

        return view('admin.customer.index', compact('customers'));
    }

    public function show(User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $pesanans = $user->pesanans()->with('detailPesanans')->latest()->paginate(10);

        return view('admin.customer.show', compact('user', 'pesanans'));
    }

    public function destroy(User $user)
    {
        // Tidak boleh hapus admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        // Tidak boleh hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('admin.customer.index')
            ->with('success', "Akun \"$nama\" berhasil dihapus.");
    }

    public function assignDriver(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat mengubah role admin.');
        }

        $newRole = $user->isDriver() ? 'customer' : 'driver';
        $user->update(['role' => $newRole]);

        $label = $newRole === 'driver' ? 'Driver' : 'Customer';
        return back()->with('success', "Role {$user->name} berhasil diubah menjadi {$label}.");
    }
}
