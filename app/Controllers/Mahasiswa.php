<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Mahasiswa extends Controller
{
    public function dashboard()
    {
        return view('mahasiswa/dashboard');
    }

    // Other mahasiswa methods...
}
