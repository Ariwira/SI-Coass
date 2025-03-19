<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\LogbookModel;
use App\Models\MahasiswaModel;
use App\Models\StaseModel;

class Logbook extends Controller
{
    protected $logbookModel;
    protected $mahasiswaModel;
    protected $staseModel;
    protected $encrypter;
    protected $db;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->staseModel = new StaseModel();
        $this->encrypter = \Config\Services::encrypter();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_students') ?? 1; // Ganti 'page_logbooks' menjadi 'page_students'
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

            // Hitung total logbooks
            $total_logbooks = $this->db->table('logbooks')->where('coass_id', $coass_id)->countAllResults();

            // Hitung total yang diverifikasi
            $total_verified = $this->db->table('logbooks')->where(['coass_id' => $coass_id, 'status' => 'Verified'])->countAllResults();

            // Hitung total yang ditolak
            $total_rejected = $this->db->table('logbooks')->where(['coass_id' => $coass_id, 'status' => 'Rejected'])->countAllResults();

            // Simpan hasil ke dalam array
            $totals[$coass_id] = [
                'total_logbooks' => $total_logbooks,
                'total_verified' => $total_verified,
                'total_rejected' => $total_rejected,
            ];
        }

        $data = [
            'title'         => 'Manajemen Logbook | SI-COASS',
            'students'      => $students,
            'pager'         => $this->mahasiswaModel->pager, // Pastikan ini menggunakan pager mahasiswa
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
        $stases = $this->staseModel->findAll();

        $data = [
            'title' => 'Tambah Logbook | SI-COASS',
            'mahasiswa' => $mahasiswa, // Kirim data mahasiswa ke view
            'stases' => $stases,
            'encryptedID' => $encryptedID, // Tambahkan encryptedID ke data
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

        $data =  [
            'mahasiswa' => $mahasiswa,
            'stases' => $stases,
            'logbook' => $logbook,
            'encryptedID' => $encryptedID,
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
        $currentPage = $this->request->getGet('page_logbooks') ?? 1; // Ambil halaman saat ini
        $perPage = 5; // Jumlah logbook per halaman

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        // Ambil semua logbook yang terkait dengan mahasiswa dengan pagination
        if (!empty($keyword)) {
            $logbooks = $this->logbookModel->select('logbooks.*, stase.name as stase_name')
                ->join('stase', 'stase.stase_id = logbooks.stase_id') // Bergabung dengan tabel stase
                ->where('logbooks.coass_id', $id)
                ->groupStart()
                ->like('logbooks.activity', $keyword) // Filter berdasarkan aktivitas
                ->orLike('stase.name', $keyword) // Filter berdasarkan nama stase
                ->groupEnd()
                ->orderBy('logbooks.updated_at', 'DESC') // Urutkan berdasarkan updated_at secara menurun
                ->paginate($perPage, 'logbooks');
        } else {
            $logbooks = $this->logbookModel->where('coass_id', $id)
                ->orderBy('updated_at', 'DESC') // Urutkan berdasarkan updated_at secara menurun
                ->paginate($perPage, 'logbooks');
        }

        // Ambil stase dari logbook pertama jika ada
        $stase = null;
        if (!empty($logbooks)) {
            // Ambil stase_id dari logbook pertama
            $stase_id = $logbooks[0]['stase_id'];
            // Ambil data stase berdasarkan stase_id
            $stase = $this->staseModel->find($stase_id);
        }

        $data = [
            'title' => 'Detail Mahasiswa | SI-COASS',
            'mahasiswa' => $mahasiswa,
            'logbooks' => $logbooks,
            'pager' => $this->logbookModel->pager, // Tambahkan pager untuk logbook
            'encryptedID' => $encryptedID,
            'stase' => $stase, // Tambahkan data stase
            'keyword' => $keyword, // Tambahkan keyword ke data
        ];

        return view('admin/logbooks/detail', $data);
    }
}
