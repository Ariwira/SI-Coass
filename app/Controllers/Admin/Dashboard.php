<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $data['title'] = 'Dashboard | SI-COASS';
        return view('admin/dashboard', $data);
    }
}
