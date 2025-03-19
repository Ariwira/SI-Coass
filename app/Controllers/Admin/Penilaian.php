<?php

namespace App\Controllers\Admin;

use App\Models\MahasiswaModel;
use App\Models\PenilaianModel;
use App\Models\StaseModel;
use App\Models\DoctorModel;
use CodeIgniter\Controller;

class Penilaian extends Controller
{
    protected $penilaianModel;
    protected $mahasiswaModel;
    protected $doctorModel;
    protected $staseModel;
    protected $encrypter;

    public function __construct()
    {
        $this->penilaianModel = new PenilaianModel();
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
        // Dekripsi ID stase
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data stase
        $stase = $this->staseModel->find($id);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil nama dokter untuk stase ini
        $doctor = $this->doctorModel->getDoctorById($stase['doctor_id']);
        $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';

        // Pagination setup
        $pager = \Config\Services::pager();
        $perPage = 5; // Jumlah data per halaman
        $currentPage = $this->request->getGet('page') ? $this->request->getGet('page') : 1;

        // Tangani pencarian
        $keyword = $this->request->getGet('keyword');
        $mahasiswaQuery = $this->mahasiswaModel->getStudentsWithUsers()
            ->join('mahasiswa_stase', 'mahasiswa_stase.coass_id = mahasiswa_coass.coass_id')
            ->where('mahasiswa_stase.stase_id', $id);

        // Jika ada keyword, filter mahasiswa berdasarkan keyword
        if ($keyword) {
            $mahasiswaQuery->groupStart()
                ->like('mahasiswa_coass.name', $keyword)
                ->orLike('mahasiswa_coass.nim', $keyword)
                ->orLike('mahasiswa_coass.university', $keyword)
                ->groupEnd();
        }

        // Ambil daftar mahasiswa yang terdaftar dalam stase ini dengan pagination
        $mahasiswaData = $mahasiswaQuery->paginate($perPage, 'mahasiswa');

        // Ambil total mahasiswa untuk pagination
        $totalMahasiswa = $mahasiswaQuery->countAllResults();

        // Ambil semua mahasiswa untuk modal
        $allMahasiswa = $this->mahasiswaModel->findAll();

        // Ambil data penilaian untuk setiap mahasiswa
        foreach ($mahasiswaData as &$mhs) {
            $penilaian = $this->penilaianModel->where('coass_id', $mhs['coass_id'])
                ->where('stase_id', $id)
                ->where('doctor_id', $stase['doctor_id']) // Tambahkan filter doctor_id
                ->first();

            // Jika penilaian tidak ditemukan, set nilai default
            if ($penilaian) {
                $mhs['penilaian'] = [
                    'date' => $penilaian['date'] ?? '-',
                    'score' => $penilaian['score'] ?? '-',
                    'feedback' => $penilaian['feedback'] ?? '-',
                ];
            } else {
                $mhs['penilaian'] = [
                    'date' => '-',
                    'score' => '-',
                    'feedback' => '-',
                ];
            }

            // Encrypt coass_id
            $mhs['encrypted_coass_id'] = bin2hex($this->encrypter->encrypt($mhs['coass_id']));
        }

        $data = [
            'title' => 'Detail Stase | SI-COASS',
            'stase' => $stase,
            'mahasiswa' => $mahasiswaData,
            'allMahasiswa' => $allMahasiswa,
            'pager' => $pager, // Kirimkan pager ke view
            'encryptedID' => $encryptedID,
            'keyword' => $keyword,
            'totalMahasiswa' => $totalMahasiswa, // Kirimkan total mahasiswa ke view
        ];

        return view('admin/penilaian/detail', $data);
    }

    public function create($encryptedID, $encryptedCoassID)
    {
        // Dekripsi ID stase dan coass_id
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID)); // Decrypt coass_id
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data stase
        $stase = $this->staseModel->find($staseID);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Stase tidak ditemukan');
        }

        // Ambil data mahasiswa
        $mahasiswa = $this->mahasiswaModel->find($coassID);
        if (!$mahasiswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Mahasiswa tidak ditemukan');
        }

        $data = [
            'title' => 'Tambah Nilai | SI-COASS',
            'stase' => $stase,
            'mahasiswa' => $mahasiswa,
            'encryptedID' => $encryptedID,
            'encryptedCoassID' => $encryptedCoassID, // Pass the encrypted coass_id to the view
        ];

        return view('admin/penilaian/create', $data);
    }

    public function store($encryptedID, $encryptedCoassID)
    {
        // Dekripsi ID stase dan coass_id
        try {
            if (!ctype_xdigit($encryptedID) || !ctype_xdigit($encryptedCoassID)) {
                throw new \Exception("Invalid encrypted ID");
            }
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.')->withInput();
        }

        // Validasi input
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

        $validation = \Config\Services::validation();

        if (!$this->validate($rules, $customErrors)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Ambil data dari form
        $data = [
            'stase_id' => $staseID,
            'coass_id' => $coassID,
            'date' => $this->request->getPost('date'),
            'score' => $this->request->getPost('score'),
            'feedback' => $this->request->getPost('feedback'),
        ];

        // Ambil doctor_id dari stase
        $stase = $this->staseModel->find($staseID);
        if (!$stase) {
            return redirect()->back()->with('error', 'Stase tidak ditemukan.')->withInput();
        }

        $data['doctor_id'] = $stase['doctor_id'];

        // Cek apakah doctor_id valid
        if (!$this->doctorModel->find($data['doctor_id'])) {
            return redirect()->back()->with('error', 'Dokter tidak ditemukan.')->withInput();
        }

        // Simpan data ke database
        if ($this->penilaianModel->insert($data)) {
            return redirect()->to(base_url("admin/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan nilai.');
        }
    }

    public function edit($encryptedID, $encryptedCoassID)
    {
        // Dekripsi ID stase dan coass_id
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data penilaian
        $penilaian = $this->penilaianModel->where('stase_id', $staseID)
            ->where('coass_id', $coassID)
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

        return view('admin/penilaian/edit', $data);
    }

    public function update($encryptedID, $encryptedCoassID)
    {
        // Dekripsi ID stase dan coass_id
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.')->withInput();
        }

        // Validasi input
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

        $validation = \Config\Services::validation();

        if (!$this->validate($rules, $customErrors)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Ambil data dari form
        $data = [
            'date' => $this->request->getPost('date'),
            'score' => $this->request->getPost('score'),
            'feedback' => $this->request->getPost('feedback'),
        ];

        // Update data ke database
        $this->penilaianModel->where('stase_id', $staseID)
            ->where('coass_id', $coassID)
            ->set($data)
            ->update();

        return redirect()->to(base_url("admin/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil diperbarui.');
    }

    public function delete($encryptedID, $encryptedCoassID)
    {
        // Dekripsi ID stase dan coass_id
        try {
            $staseID = $this->encrypter->decrypt(hex2bin($encryptedID));
            $coassID = $this->encrypter->decrypt(hex2bin($encryptedCoassID));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        // Hapus data dari database
        $this->penilaianModel->where('stase_id', $staseID)
            ->where('coass_id', $coassID)
            ->delete();

        return redirect()->to(base_url("admin/penilaian/detail-penilaian/$encryptedID"))->with('success', 'Nilai berhasil dihapus.');
    }
}
