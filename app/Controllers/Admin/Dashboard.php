<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\StaseModel;

class Dashboard extends Controller
{
    protected $mahasiswaModel;
    protected $userModel;
    protected $dokterModel;
    protected $staseModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->userModel = new UserModel();
        $this->dokterModel = new DoctorModel();
        $this->staseModel = new StaseModel();
    }

    public function index()
    {
        $students = $this->mahasiswaModel->getLatestStudents();

        foreach ($students as &$student) {
            $userData = $this->userModel->find($student['user_id']);
            $student['email'] = $userData['email'] ?? '';
        }

        $doctors = $this->dokterModel->getLatestDoctors();

        foreach ($doctors as &$doctor) {
            $userData = $this->userModel->find($doctor['user_id']);
            $doctor['email'] = $userData['email'] ?? '';
        }

        $stases = $this->staseModel->getLatestStases();

        $totalUsers = $this->userModel->getTotalUsers();
        $totalMahasiswa = $this->mahasiswaModel->getTotalMahasiswa();
        $totalDoctors = $this->dokterModel->getTotalDoctors();
        $totalStase = $this->staseModel->getTotalStase();

        $data = [
            'title' => 'Dashboard | SI-COASS',
            'students' => $students,
            'doctors' => $doctors,
            'stases' => $stases,
            'totalUsers' => $totalUsers,
            'totalMahasiswa' => $totalMahasiswa,
            'totalDoctors' => $totalDoctors,
            'totalStase' => $totalStase
        ];

        return view('admin/dashboard', $data);
    }
}
