<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\LogbookModel;
use App\Models\MahasiswaModel;
use App\Models\StaseModel; // Pastikan Anda mengimpor model StaseModel

class Logbook extends Controller
{
    protected $logbookModel;
    protected $mahasiswaModel;
    protected $staseModel; // Tambahkan ini
    protected $encrypter;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->staseModel = new StaseModel(); // Inisialisasi model Stase
        $this->encrypter = \Config\Services::encrypter();
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
            $students = $this->mahasiswaModel->paginate($perPage, 'students'); // Pastikan ini mengambil mahasiswa
        }

        // Ambil total logbook untuk setiap mahasiswa
        $totals = [];
        foreach ($students as $student) {
            $totals[$student['coass_id']] = $this->logbookModel->getTotals($student['coass_id']);
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

    public function create()
    {
        // Ambil daftar mahasiswa dan stase untuk ditampilkan di dropdown
        $data['mahasiswas'] = $this->mahasiswaModel->findAll();
        $data['stases'] = $this->staseModel->findAll(); // Ambil data stase
        return view('admin/logbooks/create', $data);
    }

    public function store()
    {
        // Validasi input menggunakan aturan dari model
        if (!$this->validate($this->logbookModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data logbook
        $this->logbookModel->save([
            'coass_id' => $this->request->getPost('coass_id'),
            'stase_id' => $this->request->getPost('stase_id'),
            'date' => $this->request->getPost('date'),
            'activity' => $this->request->getPost('activity'),
            'status' => $this->request->getPost('status'),
            'feedback' => $this->request->getPost('feedback'),
        ]);

        return redirect()->to('/admin/logbook')->with('success', 'Logbook berhasil ditambahkan.');
    }

    public function edit($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil daftar mahasiswa dan stase untuk ditampilkan di dropdown
        $data['mahasiswas'] = $this->mahasiswaModel->findAll();
        $data['stases'] = $this->staseModel->findAll(); // Ambil data stase
        $data['logbook'] = $logbook;
        $data['encryptedID'] = $encryptedID;

        return view('admin/logbooks/edit', $data);
    }

    public function update($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Validasi input menggunakan aturan dari model
        if (!$this->validate($this->logbookModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data logbook
        $this->logbookModel->update($id, [
            'coass_id' => $this->request->getPost('coass_id'),
            'stase_id' => $this->request->getPost('stase_id'),
            'date' => $this->request->getPost('date'),
            'activity' => $this->request->getPost('activity'),
            'status' => $this->request->getPost('status'),
            'feedback' => $this->request->getPost('feedback'),
        ]);

        return redirect()->to('/admin/logbooks')->with('success', 'Logbook berhasil diperbarui.');
    }

    public function delete($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Hapus data logbook
        $this->logbookModel->delete($id);

        return redirect()->to('/admin/logbooks')->with('success', 'Logbook berhasil dihapus.');
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

        // Ambil semua logbook yang terkait dengan mahasiswa dengan pagination
        $logbooks = $this->logbookModel->where('coass_id', $id)->paginate($perPage, 'logbooks');

        $data = [
            'title' => 'Detail Mahasiswa | SI-COASS',
            'mahasiswa' => $mahasiswa,
            'logbooks' => $logbooks,
            'pager' => $this->logbookModel->pager, // Tambahkan pager untuk logbook
            'encryptedID' => $encryptedID,
        ];

        return view('admin/logbooks/detail', $data);
    }
}
