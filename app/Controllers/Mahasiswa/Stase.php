<?php

namespace App\Controllers\Mahasiswa;

use CodeIgniter\Controller;
use App\Models\StaseModel;
use App\Models\MahasiswaStaseModel;

class Stase extends Controller
{
    protected $staseModel;
    protected $mahasiswaStaseModel;
    protected $encrypter;


    public function __construct()
    {
        $this->staseModel = new StaseModel();
        $this->mahasiswaStaseModel = new MahasiswaStaseModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_stases') ?? 1;
        $perPage = 10;

        // Ambil coass_id dari sesi
        $coass_id = session()->get('coass_id');

        // Ambil stase yang terkait dengan coass_id
        $stases = $this->mahasiswaStaseModel->getStasesByCoassId($coass_id, $keyword, $perPage, $currentPage);

        // Ambil detail stase dengan dokter
        $staseDetails = [];
        foreach ($stases as $stase) {
            // Get doctor name
            $doctor = $this->staseModel->getDoctorById($stase['doctor_id']);
            $staseDetails[] = [
                'stase_id' => $stase['stase_id'],
                'name' => $stase['name'],
                'doctor_name' => $doctor ? $doctor['name'] : 'Dokter tidak ditemukan',
                'status' => $stase['status'],
                'duration_weeks' => $stase['duration_weeks'],
                'department' => $stase['department'],
                'start_date' => $stase['start_date'],
                'end_date' => $stase['end_date'],
            ];
        }

        $data = [
            'title'         => 'Stase Mahasiswa | SI-COASS',
            'stases'        => $staseDetails,
            'pager'         => $this->mahasiswaStaseModel->pager,
            'currentPage'   => $currentPage,
            'keyword'       => $keyword,
        ];

        return view('mahasiswa/stase/index', $data);
    }

    public function detail($encryptedID)
    {
        // Dekripsi ID stase
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data stase
        $stase = $this->staseModel->find($id);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil nama dokter untuk stase ini
        $doctor = $this->staseModel->getDoctorById($stase['doctor_id']);
        $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';

        // Tangani pencarian
        $keyword = $this->request->getGet('keyword');

        // Ambil daftar mahasiswa yang terdaftar dalam stase ini dengan pagination
        $perPage = 5; // Jumlah data per halaman
        $mahasiswaQuery = $this->mahasiswaStaseModel->getMahasiswaByStaseId($id, $keyword);

        // **Gunakan paginate() langsung tanpa mengatur $currentPage**
        $mahasiswaData = $mahasiswaQuery->paginate($perPage, 'mahasiswa');

        // Ambil pager yang dihasilkan dari paginate()
        $pager = $mahasiswaQuery->pager;

        // Ambil total mahasiswa untuk pagination
        $totalMahasiswa = $mahasiswaQuery->countAllResults(false); // `false` agar tidak mereset query builder

        $data = [
            'title' => 'Detail Stase | SI-COASS',
            'stase' => $stase,
            'mahasiswa' => $mahasiswaData,
            'pager' => $pager, // Kirimkan pager ke view
            'encryptedID' => $encryptedID,
            'keyword' => $keyword, // Hapus spasi tambahan setelah 'keyword'
            'totalMahasiswa' => $totalMahasiswa, // Kirimkan total mahasiswa ke view
        ];

        return view('mahasiswa/stase/detail', $data);
    }
}
