<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\StaseModel;
use App\Models\DoctorModel;

class Stase extends Controller
{
    protected $staseModel;
    protected $doctorModel;
    protected $encrypter;

    public function __construct()
    {
        $this->staseModel = new StaseModel();
        $this->doctorModel = new DoctorModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_stases') ?? 1;
        $perPage = 10;

        if (!empty($keyword)) {
            $stases = $this->staseModel->search($keyword)->paginate($perPage, 'stases');
        } else {
            $stases = $this->staseModel->paginate($perPage, 'stases');
        }

        // Ambil nama dokter untuk setiap stase dan enkripsi ID stase
        foreach ($stases as &$stase) {
            $doctor = $this->doctorModel->getDoctorById($stase['doctor_id']);
            $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';

            // Enkripsi ID stase
            $stase['encrypted_id'] = bin2hex($this->encrypter->encrypt($stase['stase_id']));
        }

        $data = [
            'title'         => 'Manajemen Stase | SI-COASS',
            'stases'        => $stases,
            'pager'         => $this->staseModel->pager,
            'currentPage'   => $currentPage,
            'keyword'       => $keyword
        ];
        return view('admin/stase/index', $data);
    }

    public function create()
    {
        // Ambil daftar dokter untuk ditampilkan di dropdown
        $data['doctors'] = $this->doctorModel->getDoctorsWithUsers()->findAll();
        return view('admin/stase/create', $data);
    }

    public function store()
    {
        // Validasi input
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'doctor_id' => 'required|integer',
            'name' => 'required|min_length[3]',
            'description' => 'required',
            'department' => 'required',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil tanggal mulai dan tanggal selesai
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        // Hitung durasi dalam minggu
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        $durationWeeks = floor($interval->days / 7); // Hitung durasi dalam minggu

        // Tentukan status berdasarkan tanggal
        $currentDate = new \DateTime();
        if ($currentDate < $start) {
            $status = 'pending';
        } elseif ($currentDate >= $start && $currentDate <= $end) {
            $status = 'aktif';
        } else {
            $status = 'selesai';
        }

        // Simpan data
        $this->staseModel->save([
            'doctor_id' => $this->request->getPost('doctor_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_weeks' => $durationWeeks, // Simpan durasi dalam minggu
            'department' => $this->request->getPost('department'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status, // Menyimpan status
        ]);

        return redirect()->to('/admin/stase')->with('success', 'Stase berhasil ditambahkan.');
    }

    public function edit($encryptedID)
    {
        // Dekripsi ID stase
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $stase = $this->staseModel->find($id);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil daftar dokter untuk ditampilkan di dropdown
        $data['doctors'] = $this->doctorModel->getDoctorsWithUsers()->findAll();
        $data['stase'] = $stase;
        $data['encryptedID'] = $encryptedID;

        return view('admin/stase/edit', $data);
    }

    public function update($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $stase = $this->staseModel->find($id);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Validasi input
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'doctor_id' => 'required|integer',
            'name' => 'required|min_length[3]',
            'description' => 'required',
            'department' => 'required',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil tanggal mulai dan tanggal selesai
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        // Hitung durasi dalam minggu
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        $durationWeeks = floor($interval->days / 7); // Hitung durasi dalam minggu

        // Tentukan status berdasarkan tanggal
        $currentDate = new \DateTime();
        if ($currentDate < $start) {
            $status = 'pending';
        } elseif ($currentDate >= $start && $currentDate <= $end) {
            $status = 'aktif';
        } else {
            $status = 'selesai';
        }

        // Update data stase
        $this->staseModel->update($id, [
            'doctor_id' => $this->request->getPost('doctor_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_weeks' => $durationWeeks, // Simpan durasi dalam minggu
            'department' => $this->request->getPost('department'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status, // Memperbarui status
        ]);

        return redirect()->to('/admin/stase')->with('success', 'Stase berhasil diperbarui.');
    }

    public function delete($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Hapus data stase
        $stase = $this->staseModel->find($id);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $this->staseModel->delete($id);

        return redirect()->to('/admin/stase')->with('success', 'Stase berhasil dihapus.');
    }
}
