<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\LogbookModel;
use App\Models\MahasiswaModel;
use App\Models\StaseModel;
use App\Models\MahasiswaStaseModel;


class Logbook extends Controller
{
    protected $logbookModel;
    protected $mahasiswaModel;
    protected $mahasiswaStaseModel;
    protected $staseModel;
    protected $encrypter;
    protected $db;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->staseModel = new StaseModel();
        $this->mahasiswaStaseModel = new MahasiswaStaseModel();
        $this->encrypter = \Config\Services::encrypter();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_students') ?? 1;
        $perPage = 10;

        // Ambil mahasiswa dengan pagination
        if (!empty($keyword)) {
            $students = $this->mahasiswaModel->search($keyword)->paginate($perPage, 'students');
        } else {
            $students = $this->mahasiswaModel->getStudentsWithUsers()->paginate($perPage, 'students');
        }

        // Ambil total logbook untuk setiap mahasiswa
        $totals = [];
        foreach ($students as $student) {
            $coass_id = $student['coass_id'];
            $totals[$coass_id] = $this->logbookModel->getTotals($coass_id);
        }

        $data = [
            'title'         => 'Manajemen Logbook | SI-COASS',
            'students'      => $students,
            'pager'         => $this->mahasiswaModel->pager,
            'currentPage'   => $currentPage,
            'keyword'       => $keyword,
            'totals'        => $totals
        ];

        return view('admin/logbooks/index', $data);
    }

    public function create($encryptedID)
    {
        // Dekripsi ID mahasiswa
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data mahasiswa
        $mahasiswa = $this->mahasiswaModel->find($id);
        if (!$mahasiswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil daftar stase untuk ditampilkan di dropdown
        $stases = $this->mahasiswaStaseModel->getStasesByCoassId($id);

        $data = [
            'title' => 'Tambah Logbook | SI-COASS',
            'mahasiswa' => $mahasiswa,
            'stases' => $stases,
            'encryptedID' => $encryptedID,
        ];

        return view('admin/logbooks/create', $data);
    }

    public function store()
    {
        $validationRules = [
            'coass_id' => [
                'label' => 'Coass ID',
                'rules' => 'required|integer'
            ],
            'stase_id' => [
                'label' => 'Stase ID',
                'rules' => 'required|integer'
            ],
            'date' => [
                'label' => 'Tanggal',
                'rules' => 'required|valid_date'
            ],
            'activity' => [
                'label' => 'Aktivitas',
                'rules' => 'required|string'
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'permit_empty|in_list[Not Verified,Verified,Rejected]'
            ],
            'feedback' => [
                'label' => 'Feedback',
                'rules' => 'permit_empty|string'
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->logbookModel->save([
            'coass_id' => $this->request->getPost('coass_id'),
            'stase_id' => $this->request->getPost('stase_id'),
            'date' => $this->request->getPost('date'),
            'activity' => $this->request->getPost('activity'),
            'status' => $this->request->getPost('status'),
            'feedback' => $this->request->getPost('feedback'),
        ]);

        $coass_id = $this->request->getPost('coass_id');
        $encryptedID = bin2hex($this->encrypter->encrypt($coass_id));

        return redirect()->to('/admin/logbook/detail-logbook/' . $encryptedID)->with('success', 'Logbook berhasil ditambahkan.');
    }

    public function edit($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data logbook berdasarkan ID
        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data mahasiswa berdasarkan coass_id dari logbook
        $mahasiswa = $this->mahasiswaModel->find($logbook['coass_id']);
        if (!$mahasiswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data mahasiswa tidak ditemukan');
        }

        // Ambil daftar stase untuk ditampilkan di dropdown
        $stases = $this->staseModel->findAll();

        // Enkripsi ID logbook untuk digunakan di view
        $encryptedID = bin2hex($this->encrypter->encrypt($id));
        $encryptedCoassID = bin2hex($this->encrypter->encrypt($mahasiswa['coass_id']));


        $data =  [
            'mahasiswa' => $mahasiswa,
            'stases' => $stases,
            'logbook' => $logbook,
            'encryptedID' => $encryptedID,
            'encryptedCoassID' => $encryptedCoassID,
            'validation' => \Config\Services::validation(),
        ];

        // Kirim data ke view
        return view('admin/logbooks/edit', $data);
    }

    public function update($encryptedID)
    {
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        if (!$this->logbookModel->find($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $validationRules = [
            'coass_id' => [
                'label' => 'Coass ID',
                'rules' => 'required|integer'
            ],
            'stase_id' => [
                'label' => 'Stase ID',
                'rules' => 'required|integer'
            ],
            'date' => [
                'label' => 'Tanggal',
                'rules' => 'required|valid_date'
            ],
            'activity' => [
                'label' => 'Aktivitas',
                'rules' => 'required|string'
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'permit_empty|in_list[Not Verified,Verified,Rejected]'
            ],
            'feedback' => [
                'label' => 'Feedback',
                'rules' => 'permit_empty|string'
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->logbookModel->update($id, [
            'coass_id' => $this->request->getPost('coass_id'),
            'stase_id' => $this->request->getPost('stase_id'),
            'date' => $this->request->getPost('date'),
            'activity' => $this->request->getPost('activity'),
            'status' => $this->request->getPost('status'),
            'feedback' => $this->request->getPost('feedback'),
        ]);

        $coass_id = $this->request->getPost('coass_id');
        $encryptedID = bin2hex($this->encrypter->encrypt($coass_id));

        return redirect()->to('/admin/logbook/detail-logbook/' . $encryptedID)->with('success', 'Logbook berhasil diperbarui.');
    }

    public function delete($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data logbook berdasarkan ID
        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil coass_id dari logbook
        $coass_id = $logbook['coass_id']; // Pastikan kolom ini ada di tabel logbook

        // Hapus data logbook
        $this->logbookModel->delete($id);

        // Enkripsi coass_id untuk pengalihan
        $encryptedCoassID = bin2hex($this->encrypter->encrypt($coass_id));

        // Redirect ke halaman detail mahasiswa
        return redirect()->to('/admin/logbook/detail-logbook/' . $encryptedCoassID)->with('success', 'Logbook berhasil dihapus.');
    }

    public function detail($encryptedID)
    {
        // Dekripsi ID mahasiswa
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data mahasiswa
        $mahasiswa = $this->mahasiswaModel->find($id);
        if (!$mahasiswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Pagination untuk logbook
        $perPage = 5; // Jumlah logbook per halaman

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');
        $logbooks = $this->logbookModel->getLogbooksByCoass($id, $perPage, $keyword);

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
            'title' => 'Detail Mahasiswa | SI-COASS',
            'mahasiswa' => $mahasiswa,
            'logbooks' => $logbooks,
            'pager' => $this->logbookModel->pager, // Tambahkan pager untuk logbook
            'encryptedID' => $encryptedID,
            'keyword' => $keyword, // Tambahkan keyword ke data
        ];

        return view('admin/logbooks/detail', $data);
    }
}
