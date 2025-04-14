<?php

namespace App\Controllers\Admin;

use App\Models\MahasiswaModel;
use App\Models\PenilaianModel;
use App\Models\PenilaianHistoryModel;
use App\Models\StaseModel;
use App\Models\DoctorModel;
use CodeIgniter\Controller;

class Penilaian extends Controller
{
    protected $penilaianModel;
    protected $penilaianHistoryModel;
    protected $mahasiswaModel;
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
        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_stases') ?? 1;
        $perPage = 10;

        $stases = !empty($keyword)
            ? $this->staseModel->search($keyword)->paginate($perPage, 'stases')
            : $this->staseModel->paginate($perPage, 'stases');

        foreach ($stases as &$stase) {
            $doctor = $this->doctorModel->getDoctorById($stase['doctor_id']);
            $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';
            $stase['encrypted_id'] = bin2hex($this->encrypter->encrypt($stase['stase_id']));
        }

        $data = [
            'title'         => 'Manajemen Penilaian | SI-COASS',
            'stases'        => $stases,
            'pager'         => $this->staseModel->pager,
            'currentPage'   => $currentPage,
            'keyword'       => $keyword
        ];
        return view('admin/penilaian/index', $data);
    }

    public function detail($encryptedID)
    {
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $stase = $this->staseModel->find($id);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $doctor = $this->doctorModel->getDoctorById($stase['doctor_id']);
        $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';

        $keyword = $this->request->getGet('keyword');
        $mahasiswaData = $this->mahasiswaModel->getMahasiswaByStase($id, $keyword);
        $allMahasiswa = $this->mahasiswaModel->getAllMahasiswa();

        foreach ($mahasiswaData as &$mhs) {
            $penilaian = $this->penilaianModel->getPenilaian($id, $mhs['coass_id']);
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
            'title' => 'Detail Stase | SI-COASS',
            'stase' => $stase,
            'mahasiswa' => $mahasiswaData,
            'allMahasiswa' => $allMahasiswa,
            'encryptedID' => $encryptedID,
            'keyword' => $keyword,
            'pager' => $this->mahasiswaModel->pager,

        ];

        return view('admin/penilaian/detail', $data);
    }

    public function create($encryptedID, $encryptedCoassID)
    {
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $stase = $this->staseModel->find($staseID);
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

        return view('admin/penilaian/create', $data);
    }

    public function store($encryptedID, $encryptedCoassID)
    {
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.')->withInput();
        }

        $rules = [
            'score' => 'required|integer|less_than_equal_to[100]'
        ];

        $customErrors = [
            'score' => [
                'required' => 'Nilai tidak boleh kosong.',
                'integer' => 'Nilai harus berupa angka.',
                'less_than_equal_to' => 'Nilai harus kurang dari atau sama dengan 100.'
            ]
        ];

        if (!$this->validate($rules, $customErrors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'stase_id' => $staseID,
            'coass_id' => $coassID,
            'date' => $this->request->getPost('date'),
            'score' => $this->request->getPost('score'),
            'feedback' => $this->request->getPost('feedback'),
        ];

        $stase = $this->staseModel->find($staseID);
        if (!$stase) {
            return redirect()->back()->with('error', 'Stase tidak ditemukan.')->withInput();
        }

        $data['doctor_id'] = $stase['doctor_id'];

        if ($this->penilaianModel->insertPenilaian($data)) {
            // Tambahkan riwayat penilaian
            $historyData = [
                'penilaian_id' => $this->penilaianModel->insertID(), // Ambil ID penilaian yang baru saja ditambahkan
                'user_id' => session()->get('id'), // Ambil ID pengguna yang sedang login
                'old_score' => null, // Tidak ada nilai lama saat membuat
                'new_score' => $data['score'],
                'old_feedback' => null, // Tidak ada feedback lama saat membuat
                'new_feedback' => $data['feedback'],
                'action' => 'create', // Tindakan yang dilakukan
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->penilaianHistoryModel->insert($historyData);

            return redirect()->to(base_url("admin/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan nilai.');
        }
    }

    public function edit($encryptedID, $encryptedCoassID)
    {
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $penilaian = $this->penilaianModel->getPenilaian($staseID, $coassID);
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

        return view('admin/penilaian/edit', $data);
    }

    public function update($encryptedID, $encryptedCoassID)
    {
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

        // Ambil penilaian yang ada untuk mendapatkan nilai lama
        $penilaian = $this->penilaianModel->getPenilaian($staseID, $coassID);
        if (!$penilaian) {
            return redirect()->back()->with('error', 'Penilaian tidak ditemukan.')->withInput();
        }

        $data = [
            'date' => $this->request->getPost('date'),
            'score' => $this->request->getPost('score'),
            'feedback' => $this->request->getPost('feedback'),
        ];

        $this->penilaianModel->updatePenilaian($staseID, $coassID, $data);

        // Tambahkan riwayat penilaian
        $historyData = [
            'penilaian_id' => $penilaian['penilaian_id'], // ID penilaian yang diperbarui
            'user_id' => session()->get('id'), // ID pengguna yang sedang login
            'old_score' => $penilaian['score'], // Nilai lama
            'new_score' => $data['score'], // Nilai baru
            'old_feedback' => $penilaian['feedback'], // Feedback lama
            'new_feedback' => $data['feedback'], // Feedback baru
            'action' => 'update', // Tindakan yang dilakukan
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->penilaianHistoryModel->insert($historyData);

        return redirect()->to(base_url("admin/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil diperbarui.');
    }

    public function delete($encryptedID, $encryptedCoassID)
    {
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

        // Simpan riwayat sebelum menghapus penilaian
        $historyData = [
            'penilaian_id' => $penilaian['penilaian_id'], // ID penilaian yang dihapus
            'user_id' => session()->get('id'), // ID pengguna yang sedang login
            'old_score' => $penilaian['score'], // Nilai lama
            'new_score' => null, // Tidak ada nilai baru saat dihapus
            'old_feedback' => $penilaian['feedback'], // Feedback lama
            'new_feedback' => null, // Tidak ada feedback baru saat dihapus
            'action' => 'delete', // Tindakan yang dilakukan
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->penilaianHistoryModel->insert($historyData);

        // Hapus nilai dan feedback dari penilaian tanpa menghapus record
        $this->penilaianModel->update($penilaian['penilaian_id'], [
            'score' => null,
            'feedback' => null,
        ]);

        return redirect()->to(base_url("admin/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil dihapus.');
    }


    public function detailNilaiMahasiswa($encryptedID, $encryptedCoassID)
    {
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data penilaian dan relasi dari model
        $penilaian = $this->penilaianModel->getDetailPenilaianWithStaseAndDoctor($staseID, $coassID);

        // Default nilai jika tidak ditemukan
        $penilaianData = [
            'date' => $penilaian['date'] ?? '-',
            'score' => $penilaian['score'] ?? '-',
            'feedback' => $penilaian['feedback'] ?? '-',
            'stase_name' => $penilaian['stase_name'] ?? 'Stase tidak ditemukan',
            'doctor_name' => $penilaian['doctor_name'] ?? 'Dokter tidak ditemukan',
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

        return view('admin/penilaian/detail_nilai_mahasiswa', $data);
    }
}
