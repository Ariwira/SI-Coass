<?php

namespace App\Controllers\Mahasiswa;

use CodeIgniter\Controller;
use App\Models\LogbookModel;
use App\Models\StaseModel;
use App\Models\MahasiswaStaseModel;

class Logbook extends Controller
{
    protected $logbookModel;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
    }

    public function index()
    {
        // Ambil coass_id dari sesi pengguna yang sedang login
        $coass_id = session()->get('coass_id'); // Pastikan Anda menyimpan coass_id di sesi saat login

        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        $perPage = 10; // Jumlah data per halaman

        // Ambil data logbook berdasarkan coass_id dengan pagination
        $logbooks = $this->logbookModel->getLogbooksByCoassId($coass_id, $perPage, $currentPage);

        // Inisialisasi pager
        $pager = $this->logbookModel->pager;

        // Kirim data ke view
        return view('mahasiswa/logbooks/index', [
            'logbooks' => $logbooks,
            'pager' => $pager,
            'keyword' => $this->request->getVar('keyword') // Jika Anda menggunakan pencarian
        ]);
    }

    public function create()
    {
        $mahasiswaStaseModel = new MahasiswaStaseModel();

        $coass_id = session()->get('coass_id');

        $stases = $mahasiswaStaseModel->getStasesByCoassId($coass_id);

        return view('mahasiswa/logbooks/create', [
            'stases' => $stases
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        //status default
        if (!isset($data['status'])) {
            $data['status'] = 'Not Verified';
        }

        log_message('debug', 'Data yang diterima: ' . print_r($data, true)); // Log data yang diterima

        if ($this->logbookModel->save($data)) {
            log_message('debug', 'Data berhasil disimpan ke database.'); // Log jika berhasil
            return redirect()->to('/mahasiswa/logbook')->with('success', 'Logbook berhasil ditambahkan.');
        } else {
            log_message('debug', 'Error: ' . print_r($this->logbookModel->errors(), true)); // Log kesalahan
            return redirect()->back()->with('errors', $this->logbookModel->errors());
        }
    }

    public function edit($id)
    {
        $data['logbook'] = $this->logbookModel->find($id);
        return view('mahasiswa/logbooks/edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['logbook_id'] = $id;

        if ($this->logbookModel->save($data)) {
            return redirect()->to('/mahasiswa/logbook')->with('success', 'Logbook berhasil diperbarui.');
        } else {
            return redirect()->back()->with('errors', $this->logbookModel->errors());
        }
    }

    public function delete($id)
    {
        $this->logbookModel->delete($id);
        return redirect()->to('/mahasiswa/logbook')->with('success', 'Logbook berhasil dihapus.');
    }
}
