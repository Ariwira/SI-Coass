<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Dokter extends Controller
{
    public function dashboard()
    {
        return view('dokter/dashboard');
    }

    // Other dokter methods...
}
