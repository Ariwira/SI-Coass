<?php

namespace App\Controllers\Mahasiswa;

use CodeIgniter\Controller;
use App\Models\LogbookModel;

class Logbook extends Controller
{
    protected $logbookModel;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
    }

    public function redirect()
    {
        $data['logbooks'] = $this->logbookModel->findAll();
        return view('mahasiswa/logbooks/main', $data);
    }

    public function create()
    {
        return view('mahasiswa/logbooks/buat');
    }

    public function store()
    {
        $data = $this->request->getPost();

        if ($this->logbookModel->save($data)) {
            return redirect()->to('/mahasiswa/logbook')->with('success', 'Logbook berhasil ditambahkan.');
        } else {
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