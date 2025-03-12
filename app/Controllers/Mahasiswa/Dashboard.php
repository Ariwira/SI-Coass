<?php

namespace App\Controllers\Mahasiswa;

use CodeIgniter\Controller;
use App\Models\MahasiswaModel;

class Dashboard extends Controller
{
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
    }

    public function index()
    {
        // Ambil user_id dari session
        $userId = session()->get('id');
        
        // Cari data mahasiswa
        $mahasiswa = $this->mahasiswaModel
            ->where('user_id', $userId)
            ->first();

        // Validasi data
        if (!$mahasiswa) {
            return redirect()->to('/auth/login')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Tampilkan view
        return view('mahasiswa/dashboard', ['mahasiswa' => $mahasiswa]);
    }
}
