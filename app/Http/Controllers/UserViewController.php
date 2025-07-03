<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('https://api-check-c3yv.onrender.com/api/users');

        if ($response->successful()) {
            $body = $response->json();
            $users = $body['data'] ?? [];      
        } else {
            $users = [];
            session()->flash('error', 'Gagal mengambil data user dari API');
        }

        $userbiasa = User::all();
        return view('user.index', compact('users', 'userbiasa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function sync()
    {
        $response = Http::get('https://api-check-c3yv.onrender.com/api/users');

        if ($response->successful()) {
            $data = $response->json()['data'] ?? [];

            foreach ($data as $item) {
                // Cek apakah user dengan email ini sudah ada, jika belum maka buat
                User::updateOrCreate(
                    ['email' => $item['email']],
                    [
                        'name' => $item['name'],
                        'password' => bcrypt('password') // default password
                    ]
                );
            }

            return redirect()->route('user')->with('success', 'Data berhasil disinkronisasi!');
        }

        return redirect()->route('user')->with('error', 'Gagal menyinkronkan data dari API.');
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $response = Http::post('https://api-check-c3yv.onrender.com/api/tambah', [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            return redirect()->route('user')->with('success', 'User berhasil ditambahkan ke API eksternal.');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan user.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $response = Http::get("https://api-check-c3yv.onrender.com/api/users/{$id}");
        if ($response->successful()) {
            $user = $response->json();
            return view('user.edit', compact('user'));
        }

        return redirect()->back()->with('error', 'Gagal mengambil data user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Kirim password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $response = Http::put("https://api-check-c3yv.onrender.com/api/update/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('user')->with('success', 'User berhasil diupdate ke API!');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate user ke API');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = Http::delete("https://api-check-c3yv.onrender.com/api/hapus/{$id}");

        if ($response->successful()) {
            return redirect()->route('user')->with('success', 'User berhasil dihapus dari API!');
        }

        return redirect()->back()->with('error', 'Gagal menghapus user dari API');
    }
}
