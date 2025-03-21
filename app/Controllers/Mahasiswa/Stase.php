<?php

namespace App\Controllers\Mahasiswa;

use CodeIgniter\Controller;
use App\Models\StaseModel;
use App\Models\MahasiswaStaseModel;

class Stase extends Controller
{
    public function index()
    {
        $staseModel = new StaseModel();
        $mahasiswaStaseModel = new MahasiswaStaseModel();

        // Ambil coass_id dari sesi
        $coass_id = session()->get('coass_id');

        // Ambil stase yang terkait dengan coass_id
        $stases = $mahasiswaStaseModel->where('coass_id', $coass_id)->findAll();

        // Ambil detail stase
        $staseDetails = [];
        foreach ($stases as $stase) {
            $staseDetail = $staseModel->find($stase['stase_id']);
            $staseDetails[] = [
                'stase_id' => $stase['stase_id'],
                'name' => $staseDetail['name'],
                'doctor_name' => $staseDetail['doctor_name'],
                'status' => $staseDetail['status'],
                'duration_weeks' => $staseDetail['duration_weeks'],
                'department' => $staseDetail['department'],
                'start_date' => $staseDetail['start_date'],
                'end_date' => $staseDetail['end_date'],
            ];
        }

        return view('mahasiswa/stase/index', [
            'stases' => $staseDetails,
        ]);
    }
}