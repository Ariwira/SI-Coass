<?php

namespace App\Controllers\Dokter;

use CodeIgniter\Controller;
use App\Models\DoctorModel;

class Dashboard extends Controller
{
    protected $doctorModel;

    public function __construct()
    {
        $this->doctorModel = new DoctorModel();
    }

    public function index()
    {
        // Get the user ID from the session
        $userId = session()->get('id');

        // Fetch the doctor's data based on the user ID
        $doctor = $this->doctorModel->where('user_id', $userId)->first();

        // Check if the doctor exists
        if (!$doctor) {
            // Handle the case where the doctor is not found
            return redirect()->to('/auth/login')->with('error', 'Doctor not found.');
        }

        // Pass the doctor's data to the view
        return view('dokter/dashboard', ['doctor' => $doctor]);
    }

    // Other dokter methods...
}