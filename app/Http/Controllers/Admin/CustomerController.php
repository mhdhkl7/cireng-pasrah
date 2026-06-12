<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
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
}
