<?php

namespace App\Controllers\Mahasiswa;

use CodeIgniter\Controller;
use App\Models\LogbookModel;
use App\Models\StaseModel;
use App\Models\MahasiswaStaseModel;

class Logbook extends Controller
{
    protected $logbookModel;
    protected $encrypter;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        // Ambil coass_id dari sesi pengguna yang sedang login
        $coass_id = session()->get('coass_id'); 

        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        $perPage = 10; // Jumlah data per halaman
        
        // Ambil keyword untuk pencarian
        $keyword = $this->request->getVar('keyword');

        // Ambil data logbook berdasarkan coass_id dengan pagination
        $logbooks = $this->logbookModel->getLogbooksByCoassId($coass_id, $perPage, $currentPage, $keyword);

        // Inisialisasi pager
        $pager = $this->logbookModel->pager;

        // Kirim data ke view
        return view('mahasiswa/logbooks/index', [
            'logbooks' => $logbooks,
            'pager' => $pager,
            'keyword' => $keyword
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
            log_message('debug', 'Data berhasil disimpan ke database.'); 
            return redirect()->to('/mahasiswa/logbook')->with('success', 'Logbook berhasil ditambahkan.');
        } else {
            log_message('debug', 'Error: ' . print_r($this->logbookModel->errors(), true)); 
            return redirect()->back()->with('errors', $this->logbookModel->errors());
        }
    }

    public function edit($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        
        $data['logbook'] = $this->logbookModel->find($id);
        $data['encryptedID'] = $encryptedID;
        
        return view('mahasiswa/logbooks/edit', $data);
    }

    public function update($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        
        $data = $this->request->getPost();
        $data['logbook_id'] = $id;

        if ($this->logbookModel->save($data)) {
            return redirect()->to('/mahasiswa/logbook')->with('success', 'Logbook berhasil diperbarui.');
        } else {
            return redirect()->back()->with('errors', $this->logbookModel->errors());
        }
    }

    public function delete($encryptedID)
    {
        // Dekripsi ID logbook
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        
        $this->logbookModel->delete($id);
        return redirect()->to('/mahasiswa/logbook')->with('success', 'Logbook berhasil dihapus.');
    }
}