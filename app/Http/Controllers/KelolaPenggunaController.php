<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class KelolaPenggunaController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.kelolapengguna.index', compact('users'));
    }
}
