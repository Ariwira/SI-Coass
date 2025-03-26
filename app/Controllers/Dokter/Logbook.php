<?php

namespace App\Controllers\Dokter;

use App\Models\MahasiswaModel;
use App\Models\LogbookModel;
use App\Models\StaseModel;
use CodeIgniter\Controller;

class Logbook extends Controller
{
    protected $mahasiswaModel;
    protected $logbookModel;
    protected $staseModel;
    protected $db;
    protected $encrypter;


    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->logbookModel = new LogbookModel();
        $this->staseModel = new StaseModel();
        $this->db = \Config\Database::connect();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        $session = session();
        $doctor_id = $session->get('doctor_id');

        if (!$doctor_id) {
            return redirect()->to('/login');
        }

        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_students') ?? 1;
        $perPage = 10;

        // Ambil mahasiswa yang memiliki logbook dengan stase yang sesuai dengan dokter
        $students = $this->mahasiswaModel->getStudentsByDoctor($doctor_id, $keyword, $perPage);

        // Ambil total logbook untuk setiap mahasiswa
        $totals = [];
        foreach ($students as $student) {
            $coass_id = $student['coass_id'];
            $totals[$coass_id] = $this->logbookModel->getTotals($coass_id);
        }

        $data = [
            'title' => 'Manajemen Logbook | SI-COASS',
            'students' => $students,
            'pager' => $this->mahasiswaModel->pager,
            'currentPage' => $currentPage,
            'keyword' => $keyword,
            'totals' => $totals
        ];

        return view('dokter/logbooks/index', $data);
    }

    public function detail($encryptedID)
    {
        // Dekripsi ID mahasiswa
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $session = session();
        $doctor_id = $session->get('doctor_id');

        if (!$doctor_id) {
            return redirect()->to('/login');
        }

        // Ambil data mahasiswa
        $mahasiswa = $this->mahasiswaModel->find($id);
        if (!$mahasiswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $perPage = 5;
        $keyword = $this->request->getGet('keyword');

        // Ambil data logbook yang hanya terkait dengan dokter yang login
        $logbooks = $this->logbookModel->getLogbooksByDoctor($id, $doctor_id, $keyword, $perPage);

        // Ambil semua stase yang terkait dengan logbooks
        $stase_ids = array_column($logbooks, 'stase_id');
        $staseList = [];

        if (!empty($stase_ids)) {
            $staseData = $this->staseModel->whereIn('stase_id', $stase_ids)->findAll();

            // Buat array dengan key sebagai stase_id agar lebih cepat dicari
            foreach ($staseData as $s) {
                $staseList[$s['stase_id']] = $s['name'];
            }
        }

        // Tambahkan nama stase ke setiap logbook
        foreach ($logbooks as &$logbook) {
            $logbook['stase_name'] = $staseList[$logbook['stase_id']] ?? 'Tidak ada stase';
        }
        unset($logbook); // Hindari referensi tak terduga

        $data = [
            'title' => 'Detail Logbook Mahasiswa | SI-COASS',
            'mahasiswa' => $mahasiswa,
            'logbooks' => $logbooks,
            'pager' => $this->logbookModel->pager,
            'encryptedID' => $encryptedID,
            'keyword' => $keyword,
        ];

        return view('dokter/logbooks/detail', $data);
    }

    public function verify($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data logbook
        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data mahasiswa
        $mahasiswa = $this->mahasiswaModel->find($logbook['coass_id']);
        if (!$mahasiswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data mahasiswa tidak ditemukan');
        }

        // Ambil nama stase
        $stase = $this->staseModel->find($logbook['stase_id']);
        $encryptedCoassID = bin2hex($this->encrypter->encrypt($logbook['coass_id']));

        $data = [
            'mahasiswa' => $mahasiswa,
            'logbook' => array_merge($logbook, ['stase_name' => $stase['name']]),
            'encryptedID' => $encryptedID,
            'encryptedCoassID' => $encryptedCoassID,
            'validation' => \Config\Services::validation(),
        ];

        return view('dokter/logbooks/verify', $data);
    }

    public function updateVerification($encryptedID)
    {
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $validationRules = [
            'status' => [
                'label' => 'Status',
                'rules' => 'required|in_list[Not Verified,Verified,Rejected]'
            ],
            'feedback' => [
                'label' => 'Feedback',
                'rules' => 'permit_empty|string'
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update logbook
        $this->logbookModel->update($id, [
            'status' => $this->request->getPost('status'),
            'feedback' => $this->request->getPost('feedback'),
        ]);

        // Enkripsi coass_id untuk redirect ke halaman detail-logbook
        $encryptedCoassID = bin2hex($this->encrypter->encrypt($logbook['coass_id']));

        return redirect()->to('/dokter/logbook/detail-logbook/' . $encryptedCoassID)->with('success', 'Logbook berhasil diverifikasi.');
    }
}
