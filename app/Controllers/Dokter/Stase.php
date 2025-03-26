<?php

namespace App\Controllers\Dokter;

use CodeIgniter\Controller;
use App\Models\StaseModel;
use App\Models\DoctorModel;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaStaseModel;
use App\Models\UserModel;

class Stase extends Controller
{
    protected $staseModel;
    protected $doctorModel;
    protected $mahasiswaModel;
    protected $mahasiswaStaseModel;
    protected $userModel;
    protected $encrypter;

    public function __construct()
    {
        $this->staseModel = new StaseModel();
        $this->doctorModel = new DoctorModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->mahasiswaStaseModel = new MahasiswaStaseModel();
        $this->userModel = new UserModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        // Ambil doctor_id dari session
        $doctorId = session()->get('doctor_id');

        // Pastikan doctor_id ada dalam session
        if (!$doctorId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_stases') ?? 1;
        $perPage = 10;

        // Ambil stase yang dimiliki oleh dokter yang sedang login
        $stases = $this->staseModel->getStasesByDoctor($doctorId, $keyword, $perPage, $currentPage);

        // Enkripsi ID stase dan ambil nama dokter
        foreach ($stases as &$stase) {
            $doctor = $this->doctorModel->getDoctorById($stase['doctor_id']);
            $stase['doctor_name'] = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';

            $stase['encrypted_id'] = bin2hex($this->encrypter->encrypt($stase['stase_id']));
        }

        $data = [
            'title'         => 'Manajemen Stase| SI-COASS',
            'stases'        => $stases,
            'pager'         => $this->staseModel->pager,
            'currentPage'   => $currentPage,
            'keyword'       => $keyword
        ];

        return view('dokter/stase/index', $data);
    }

    public function create()
    {
        return view('dokter/stase/create');
    }

    public function store()
    {
        $rules = [
            'name' => [
                'label' => 'Nama Stase',
                'rules' => 'required|min_length[3]'
            ],
            'description' => [
                'label' => 'Deskripsi',
                'rules' => 'required'
            ],
            'department' => [
                'label' => 'Departemen',
                'rules' => 'required'
            ],
            'start_date' => [
                'label' => 'Tanggal Mulai',
                'rules' => 'required|valid_date'
            ],
            'end_date' => [
                'label' => 'Tanggal Selesai',
                'rules' => 'required|valid_date',
                'errors' => [
                    'valid_date' => 'Format Tanggal Selesai tidak valid.'
                ]
            ]
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Cek apakah end_date lebih awal dari start_date
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        if (strtotime($endDate) < strtotime($startDate)) {
            return redirect()->back()->withInput()->with('errors', ['end_date' => 'Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.']);
        }

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

        // Ambil doctor_id dari session
        $doctorId = session()->get('doctor_id');

        // Simpan data
        $this->staseModel->save([
            'doctor_id' => $doctorId, // Simpan ID dokter dari session
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_weeks' => $durationWeeks, // Simpan durasi dalam minggu
            'department' => $this->request->getPost('department'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status, // Menyimpan status
        ]);

        return redirect()->to('/dokter/stase')->with('success', 'Stase berhasil ditambahkan.');
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

        // Pastikan stase ini milik dokter yang sedang login
        $doctorId = session()->get('doctor_id');
        if ($stase['doctor_id'] != $doctorId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke stase ini.');
        }

        $data['stase'] = $stase;
        $data['encryptedID'] = $encryptedID;

        return view('dokter/stase/edit', $data);
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

        // Pastikan stase ini milik dokter yang sedang login
        $doctorId = session()->get('doctor_id');
        if ($stase['doctor_id'] != $doctorId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke stase ini.');
        }

        // Aturan validasi
        $rules = [
            'name' => [
                'label' => 'Nama',
                'rules' => 'required|min_length[3]'
            ],
            'description' => [
                'label' => 'Deskripsi',
                'rules' => 'required'
            ],
            'department' => [
                'label' => 'Departemen',
                'rules' => 'required'
            ],
            'start_date' => [
                'label' => 'Tanggal Mulai',
                'rules' => 'required|valid_date'
            ],
            'end_date' => [
                'label' => 'Tanggal Selesai',
                'rules' => 'required|valid_date'
            ]
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil tanggal mulai dan tanggal selesai
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        // Validasi tambahan: Cek jika end_date lebih awal dari start_date
        if (strtotime($endDate) < strtotime($startDate)) {
            return redirect()->back()->withInput()->with('errors', ['end_date' => 'Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.']);
        }

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
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_weeks' => $durationWeeks, // Simpan durasi dalam minggu
            'department' => $this->request->getPost('department'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status, // Memperbarui status
        ]);

        return redirect()->to('/dokter/stase')->with('success', 'Stase berhasil diperbarui.');
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

        // Pastikan stase ini milik dokter yang sedang login
        $doctorId = session()->get('doctor_id');
        if ($stase['doctor_id'] != $doctorId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke stase ini.');
        }

        $this->staseModel->delete($id);

        return redirect()->to('/dokter/stase')->with('success', 'Stase berhasil dihapus.');
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

        // Pastikan stase ini milik dokter yang sedang login
        $doctorId = session()->get('doctor_id');
        if ($stase['doctor_id'] != $doctorId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke stase ini.');
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

        return view('dokter/stase/detail', $data);
    }

    public function createMahasiswa($encryptedStaseId)
    {
        // Dekripsi stase_id
        $staseId = $this->encrypter->decrypt(hex2bin($encryptedStaseId));

        // Ambil data stase dan mahasiswa yang tersedia
        $stase = $this->staseModel->find($staseId);
        if (!$stase) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Pastikan stase ini milik dokter yang sedang login
        $doctorId = session()->get('doctor_id');
        if ($stase['doctor_id'] != $doctorId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke stase ini.');
        }

        $allMahasiswa = $this->mahasiswaModel->findAll(); // Ambil semua mahasiswa

        // Enkripsi stase_id untuk digunakan dalam URL
        $encryptedID = bin2hex($this->encrypter->encrypt($staseId));

        // Tampilkan view
        return view('dokter/stase/create_mahasiswa', [
            'stase' => $stase,
            'allMahasiswa' => $allMahasiswa,
            'encryptedID' => $encryptedID, // Sertakan encryptedID di sini
        ]);
    }

    public function addMahasiswaToStase()
    {
        // Ambil data mahasiswa yang dipilih dari input
        $coassIds = $this->request->getPost('coass_id'); // Pastikan ini adalah array
        $staseId = $this->request->getPost('stase_id'); // Ambil stase_id dari input

        // Pastikan stase_id tidak null
        if (empty($staseId)) {
            return redirect()->to('/dokter/stase')->with('error', 'Stase ID tidak ditemukan.');
        }

        // Pastikan data yang diterima adalah array
        if (is_array($coassIds) && count($coassIds) > 0) {
            foreach ($coassIds as $coassId) {
                // Validasi coass_id jika diperlukan
                if (!empty($coassId)) {
                    // Cek apakah mahasiswa sudah ada di stase
                    $existingEntry = $this->mahasiswaStaseModel->where('stase_id', $staseId)
                        ->where('coass_id', $coassId)
                        ->first();

                    // Ambil nama mahasiswa berdasarkan coass_id
                    $mahasiswa = $this->mahasiswaModel->find($coassId);
                    $mahasiswaName = $mahasiswa ? $mahasiswa['name'] : 'Mahasiswa tidak ditemukan';

                    if ($existingEntry) {
                        // Jika sudah ada, tampilkan pesan kesalahan dengan nama mahasiswa
                        return redirect()->back()->with('error', 'Mahasiswa ' . esc($mahasiswaName) . ' sudah ada di stase ini.');
                    }

                    // Jika belum ada, simpan data
                    $data = [
                        'stase_id' => $staseId, // Mengaitkan stase_id
                        'coass_id' => $coassId,
                    ];
                    $this->mahasiswaStaseModel->insert($data); // Simpan data ke tabel mahasiswa_stase
                }
            }

            // Enkripsi stase_id untuk digunakan dalam URL
            $encryptedStaseId = bin2hex($this->encrypter->encrypt($staseId));

            // Redirect ke halaman detail stase
            return redirect()->to('/dokter/stase/detail-stase/' . $encryptedStaseId)->with('success', 'Mahasiswa berhasil ditambahkan ke stase.');
        } else {
            return redirect()->back()->with('error', 'Silakan pilih setidaknya satu mahasiswa.');
        }
    }

    public function removeMahasiswaFromStase()
    {
        $staseId = $this->request->getPost('stase_id');
        $coassId = $this->request->getPost('coass_id');

        // Hapus mahasiswa dari stase
        $this->mahasiswaStaseModel->where('stase_id', $staseId)->where('coass_id', $coassId)->delete();

        // Enkripsi stase_id untuk digunakan dalam URL
        $encryptedStaseId = bin2hex($this->encrypter->encrypt($staseId));

        return redirect()->to('/dokter/stase/detail-stase/' . $encryptedStaseId)->with('success', 'Mahasiswa berhasil dihapus dari stase.');
    }
}
