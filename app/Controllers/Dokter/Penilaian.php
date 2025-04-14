<?php

namespace App\Controllers\Dokter;

use App\Models\MahasiswaModel;
use App\Models\PenilaianModel;
use App\Models\PenilaianHistoryModel;
use App\Models\StaseModel;
use App\Models\DoctorModel;
use CodeIgniter\Controller;

class Penilaian extends Controller
{
    protected $penilaianModel;
    protected $mahasiswaModel;
    protected $penilaianHistoryModel;
    protected $doctorModel;
    protected $staseModel;
    protected $encrypter;

    public function __construct()
    {
        $this->penilaianModel = new PenilaianModel();
        $this->penilaianHistoryModel = new PenilaianHistoryModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->doctorModel = new DoctorModel();
        $this->staseModel = new StaseModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $keyword = $this->request->getGet('keyword');
        $perPage = 10;

        $stases = !empty($keyword)
            ? $this->staseModel->where('doctor_id', $doctorID)->search($keyword)->paginate($perPage, 'stases')
            : $this->staseModel->where('doctor_id', $doctorID)->paginate($perPage, 'stases');

        foreach ($stases as &$stase) {
            $doctor = $this->doctorModel->getDoctorById($stase['doctor_id']);
            $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';
            $stase['encrypted_id'] = bin2hex($this->encrypter->encrypt($stase['stase_id']));
        }

        $data = [
            'title' => 'Penilaian | SI-COASS',
            'stases' => $stases,
            'pager' => $this->staseModel->pager,
            'keyword' => $keyword
        ];
        return view('dokter/penilaian/index', $data);
    }

    public function detail($encryptedID)
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $stase = $this->staseModel->where('stase_id', $id)->where('doctor_id', $doctorID)->first();
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $doctor = $this->doctorModel->getDoctorById($stase['doctor_id']);
        $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';

        $keyword = $this->request->getGet('keyword');
        $mahasiswaData = $this->mahasiswaModel->getMahasiswaByStase($id, $keyword);

        foreach ($mahasiswaData as &$mhs) {
            $penilaian = $this->penilaianModel->getPenilaian($id, $mhs['coass_id'], $doctorID);
            $mhs['penilaian'] = $penilaian ? [
                'date' => $penilaian['date'] ?? '-',
                'score' => $penilaian['score'] ?? '-',
                'feedback' => $penilaian['feedback'] ?? '-',
            ] : [
                'date' => '-',
                'score' => '-',
                'feedback' => '-',
            ];
            $mhs['encrypted_coass_id'] = bin2hex($this->encrypter->encrypt($mhs['coass_id']));
        }

        $data = [
            'title' => 'Detail Penilaian | SI-COASS',
            'stase' => $stase,
            'mahasiswa' => $mahasiswaData,
            'encryptedID' => $encryptedID,
            'keyword' => $keyword,
            'pager' => $this->mahasiswaModel->pager,
        ];

        return view('dokter/penilaian/detail', $data);
    }

    public function create($encryptedID, $encryptedCoassID)
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $stase = $this->staseModel->where('stase_id', $staseID)->where('doctor_id', $doctorID)->first();
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Stase tidak ditemukan');
        }

        $mahasiswa = $this->mahasiswaModel->find($coassID);
        if (!$mahasiswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Mahasiswa tidak ditemukan');
        }

        $data = [
            'title' => 'Tambah Nilai | SI-COASS',
            'stase' => $stase,
            'mahasiswa' => $mahasiswa,
            'encryptedID' => $encryptedID,
            'encryptedCoassID' => $encryptedCoassID,
        ];

        return view('dokter/penilaian/create', $data);
    }

    public function store($encryptedID, $encryptedCoassID)
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.')->withInput();
        }

        $rules = [
            'score' => 'required|integer|less_than_equal_to[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'stase_id' => $staseID,
            'coass_id' => $coassID,
            'doctor_id' => $doctorID,
            'date' => $this->request->getPost('date'),
            'score' => $this->request->getPost('score'),
            'feedback' => $this->request->getPost('feedback'),
        ];

        $userID = session()->get('id');
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($this->penilaianModel->insert($data)) {
            // Create history record
            $historyData = [
                'penilaian_id' => $this->penilaianModel->insertID(),
                'user_id' => $userID,
                'old_score' => null,
                'new_score' => $data['score'],
                'old_feedback' => null,
                'new_feedback' => $data['feedback'],
                'action' => 'create',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->penilaianHistoryModel->insert($historyData);

            return redirect()->to(base_url("dokter/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil ditambahkan.');
        }
        return redirect()->back()->with('error', 'Gagal menambahkan nilai.');
    }

    public function edit($encryptedID, $encryptedCoassID)
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $penilaian = $this->penilaianModel->where('stase_id', $staseID)
            ->where('coass_id', $coassID)
            ->where('doctor_id', $doctorID)
            ->first();

        if (!$penilaian) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Penilaian tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Nilai | SI-COASS',
            'stase' => $this->staseModel->find($staseID),
            'mahasiswa' => $this->mahasiswaModel->find($coassID),
            'penilaian' => $penilaian,
            'encryptedID' => $encryptedID,
            'encryptedCoassID' => $encryptedCoassID,
        ];

        return view('dokter/penilaian/edit', $data);
    }

    public function update($encryptedID, $encryptedCoassID)
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.')->withInput();
        }

        $data = [
            'date' => $this->request->getPost('date'),
            'score' => $this->request->getPost('score'),
            'feedback' => $this->request->getPost('feedback'),
        ];

        // Ambil penilaian yang ada untuk mendapatkan nilai lama
        $penilaian = $this->penilaianModel->getPenilaian($staseID, $coassID);
        if (!$penilaian) {
            return redirect()->back()->with('error', 'Penilaian tidak ditemukan.')->withInput();
        }

        // Update the penilaian
        $this->penilaianModel->updatePenilaian($staseID, $coassID, $data);


        $userID = session()->get('id');
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Create history record
        $historyData = [
            'penilaian_id' => $penilaian['penilaian_id'],
            'user_id' => $userID,
            'old_score' => $penilaian['score'],
            'new_score' => $data['score'],
            'old_feedback' => $penilaian['feedback'],
            'new_feedback' => $data['feedback'],
            'action' => 'update',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->penilaianHistoryModel->insert($historyData);

        return redirect()->to(base_url("dokter/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil diperbarui.');
    }

    public function delete($encryptedID, $encryptedCoassID)
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        // Ambil penilaian yang ada untuk mendapatkan informasi sebelum dihapus
        $penilaian = $this->penilaianModel->getPenilaian($staseID, $coassID);
        if (!$penilaian) {
            return redirect()->back()->with('error', 'Penilaian tidak ditemukan.');
        }

        $userID = session()->get('id');
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Create history record before deletion
        $historyData = [
            'penilaian_id' => $penilaian['penilaian_id'],
            'user_id' => $userID,
            'old_score' => $penilaian['score'],
            'new_score' => null,
            'old_feedback' => $penilaian['feedback'],
            'new_feedback' => null,
            'action' => 'delete',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->penilaianHistoryModel->insert($historyData);

        // Delete the penilaian
        $this->penilaianModel->where('stase_id', $staseID)
            ->where('coass_id', $coassID)
            ->where('doctor_id', $doctorID)
            ->delete();

        return redirect()->to(base_url("dokter/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil dihapus.');
    }

    public function detailNilaiMahasiswa($encryptedID, $encryptedCoassID)
    {
        $doctorID = session()->get('doctor_id');
        if (!$doctorID) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $penilaian = $this->penilaianModel->getDetailPenilaianWithStaseAndDoctor($staseID, $coassID);

        if (!$penilaian) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Penilaian tidak ditemukan');
        }

        // Prepare the data for the view
        $penilaianData = [
            'date' => $penilaian['date'] ?? '-',
            'score' => $penilaian['score'] ?? '-',
            'feedback' => $penilaian['feedback'] ?? '-',
            'stase_name' => $penilaian['stase_name'] ?? 'Stase tidak ditemukan',
            'doctor_name' => $penilaian['doctor_name'],
            'department' => $penilaian['department'] ?? 'Departemen tidak ditemukan',
        ];

        // Ambil riwayat dari model
        $penilaianHistoryModel = new PenilaianHistoryModel();
        $history = $penilaianHistoryModel->getHistoryWithUserNames($penilaian['penilaian_id'] ?? null);


        $data = [
            'title' => 'Detail Penilaian Mahasiswa | SI-COASS',
            'penilaian' => $penilaianData,
            'history' => $history,
            'encryptedID' => $encryptedID,
            'encryptedCoassID' => $encryptedCoassID,
        ];

        return view('dokter/penilaian/detail_nilai_mahasiswa', $data);
    }
}
