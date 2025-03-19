<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

class Mahasiswa extends Controller
{
    protected $mahasiswaModel;
    protected $userModel;
    protected $encrypter;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->userModel = new UserModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_students') ?? 1;
        $perPage = 10;

        if (!empty($keyword)) {
            $students = $this->mahasiswaModel->search($keyword)->paginate($perPage, 'students');
        } else {
            $students = $this->mahasiswaModel->getStudentsWithUsers()->paginate($perPage, 'students');
        }

        $data = [
            'title'         => 'Manajemen Mahasiswa Coass | SI-COASS',
            'students'      => $students,
            'pager'         => $this->mahasiswaModel->pager,
            'currentPage'   => $currentPage,
            'keyword'       => $keyword
        ];
        return view('admin/students/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Mahasiswa | SI-COASS',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/students/create', $data);
    }

    public function store()
    {
        $rules = [
            'name' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required|string|max_length[255]'
            ],
            'nim' => [
                'label' => 'NIM',
                'rules' => 'required|numeric|is_unique[mahasiswa_coass.nim]'
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]'
            ],
            'date_of_birth' => [
                'label' => 'Tanggal Lahir',
                'rules' => 'required|valid_date'
            ],
            'place_of_birth' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required|string|max_length[255]'
            ],
            'gender' => [
                'label' => 'Jenis Kelamin',
                'rules' => 'required|in_list[Male,Female,Other]'
            ],
            'religion' => [
                'label' => 'Agama',
                'rules' => 'required|in_list[Islam,Hindu,Protestan,Katolik,Buddha,Konghucu]'
            ],
            'blood_group' => [
                'label' => 'Golongan Darah',
                'rules' => 'required|in_list[A,B,AB,O,Unknown]'
            ],
            'phone' => [
                'label' => 'Nomor Telepon',
                'rules' => 'required|numeric|min_length[10]|max_length[15]'
            ],
            'mobile_no' => [
                'label' => 'Nomor HP',
                'rules' => 'permit_empty|string|max_length[20]'
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'required|string'
            ],
            'university' => [
                'label' => 'Universitas',
                'rules' => 'required|string|max_length[255]'
            ],
            'year' => [
                'label' => 'Tahun Masuk',
                'rules' => 'required|integer|greater_than[1999]|less_than_equal_to[' . date('Y') . ']'
            ],
            'photo' => [
                'label' => 'Foto Profil',
                'rules' => 'permit_empty|is_image[photo]|max_size[photo,2048]|mime_in[photo,image/png,image/jpg,image/jpeg]'
            ]
        ];


        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle file upload
        $photo = $this->request->getFile('photo');
        $photoName = $photo->isValid() && !$photo->hasMoved() ? $photo->getRandomName() : 'default-avatar.jpg';

        if ($photo->isValid() && !$photo->hasMoved()) {
            $photo->move('uploads/photos', $photoName);
        }

        $this->mahasiswaModel->save([
            'user_id'       => $this->userModel->insert(['email' => $this->request->getPost('email'), 'password' => password_hash($this->generateDefaultPassword(), PASSWORD_DEFAULT), 'role' => 'Mahasiswa Coass']),
            'name'          => $this->request->getPost('name'),
            'nim'           => $this->request->getPost('nim'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'place_of_birth' => $this->request->getPost('place_of_birth'),
            'gender'        => $this->request->getPost('gender'),
            'religion'      => $this->request->getPost('religion'),
            'blood_group'   => $this->request->getPost('blood_group'),
            'phone'         => $this->request->getPost('phone'),
            'mobile_no'     => $this->request->getPost('mobile_no'),
            'address'       => $this->request->getPost('address'),
            'university'    => $this->request->getPost('university'),
            'year'          => $this->request->getPost('year'),
            'photo'         => $photoName
        ]);

        return redirect()->to('admin/mahasiswa-coass')->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    /**
     * Method untuk menghapus data mahasiswa.
     * Parameter $encryptedID adalah ID yang telah dienkripsi.
     */
    public function delete($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Begin Transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get student data first
            $student = $this->mahasiswaModel->find($id);

            if (!$student) {
                throw new \Exception('Data mahasiswa tidak ditemukan');
            }

            // Delete photo if exists and not default
            if (
                !empty($student['photo']) &&
                $student['photo'] !== 'default-avatar.jpg' &&
                file_exists('uploads/photos/' . $student['photo'])
            ) {
                unlink('uploads/photos/' . $student['photo']);
            }

            // Delete student data
            $this->mahasiswaModel->delete($id);

            // Delete user account
            $this->userModel->delete($student['user_id']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menghapus data');
            }

            return redirect()->to('admin/mahasiswa-coass')->with('success', 'Data mahasiswa dan akun berhasil dihapus');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('admin/mahasiswa-coass')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detail($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $student = $this->mahasiswaModel->find($id);
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $userData = $this->userModel->find($student['user_id']);
        $student['email'] = $userData['email'] ?? '';

        $data = [
            'title'      => 'Detail Mahasiswa | SI-COASS',
            'validation' => \Config\Services::validation(),
            'student'    => $student,
            'user'       => $userData,
            'encryptedID' => $encryptedID
        ];
        return view('admin/students/detail', $data);
    }

    public function update($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $student = $this->mahasiswaModel->find($id);
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        $userId = $student['user_id'];

        // Ambil data user untuk mendapatkan email saat ini
        $user = $this->userModel->find($userId);
        $currentEmail = $user['email'];

        // Buat aturan validasi dasar
        $rules = [
            'name' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required|string|max_length[255]'
            ],
            'date_of_birth' => [
                'label' => 'Tanggal Lahir',
                'rules' => 'required|valid_date'
            ],
            'place_of_birth' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required|string|max_length[255]'
            ],
            'gender' => [
                'label' => 'Jenis Kelamin',
                'rules' => 'required|in_list[Male,Female,Other]'
            ],
            'religion' => [
                'label' => 'Agama',
                'rules' => 'required|in_list[Islam,Hindu,Protestan,Katolik,Buddha,Konghucu]'
            ],
            'blood_group' => [
                'label' => 'Golongan Darah',
                'rules' => 'required|in_list[A,B,AB,O,Unknown]'
            ],
            'phone' => [
                'label' => 'Nomor Telepon',
                'rules' => 'required|numeric|min_length[10]|max_length[15]'
            ],
            'mobile_no' => [
                'label' => 'Nomor HP',
                'rules' => 'permit_empty|numeric|min_length[10]|max_length[15]'
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'required|string'
            ],
            'university' => [
                'label' => 'Universitas',
                'rules' => 'required|string|max_length[255]'
            ],
            'year' => [
                'label' => 'Tahun Masuk',
                'rules' => 'required|integer|greater_than[1999]|less_than_equal_to[' . date('Y') . ']'
            ],
            'photo' => [
                'label' => 'Foto Profil',
                'rules' => 'permit_empty|is_image[photo]|max_size[photo,2048]|mime_in[photo,image/png,image/jpg,image/jpeg]'
            ]
        ];

        // Aturan validasi untuk email
        $postEmail = $this->request->getPost('email');
        if ($postEmail !== $currentEmail) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
        } else {
            $rules['email'] = 'required|valid_email';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        $photoName = $student['photo']; // Current photo name
        if ($photo->isValid() && !$photo->hasMoved()) {
            // Delete old photo if exists and is not the default avatar
            if ($photoName && $photoName !== 'default-avatar.jpg' && file_exists('uploads/photos/' . $photoName)) {
                unlink('uploads/photos/' . $photoName);
            }
            $photoName = $photo->getRandomName();
            $photo->move('uploads/photos', $photoName);
        }

        // Update email di tabel user (jika berubah)
        $this->userModel->update($userId, [
            'email' => $postEmail
        ]);

        // Update data mahasiswa tanpa mengubah NIM
        $this->mahasiswaModel->update($id, [
            'name'          => $this->request->getPost('name'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'place_of_birth' => $this->request->getPost('place_of_birth'),
            'gender'        => $this->request->getPost('gender'),
            'religion'      => $this->request->getPost('religion'),
            'blood_group'   => $this->request->getPost('blood_group'),
            'phone'         => $this->request->getPost('phone'),
            'mobile_no'     => $this->request->getPost('mobile_no'),
            'address'       => $this->request->getPost('address'),
            'university'    => $this->request->getPost('university'),
            'year'          => $this->request->getPost('year'),
            'photo'         => $photoName
        ]);

        return redirect()->to('admin/mahasiswa-coass')->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    public function updatePassword($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $student = $this->mahasiswaModel->find($id);
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $userId = $student['user_id'];

        // Aturan validasi untuk password
        $rules = [
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => 'required|min_length[8]'
            ],
            'confirm_password' => [
                'label' => 'Konfirmasi Kata Sandi',
                'rules' => 'required|matches[password]'
            ]
        ];


        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update password
        $this->userModel->update($userId, [
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('admin/mahasiswa-coass')->with('success', 'Password berhasil diperbarui');
    }

    private function generateDefaultPassword()
    {
        return 'coass@' . date('Y');
    }
}
