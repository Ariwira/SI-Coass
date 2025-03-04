<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

class Admin extends Controller
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

    public function dashboard()
    {
        $data['title'] = 'Dashboard | SI-COASS';
        return view('admin/dashboard', $data);
    }

    public function students()
    {
        // Get search keyword from GET request
        $keyword = $this->request->getGet('keyword');

        // Get current page from URL, default to 1 if not set
        $currentPage = $this->request->getGet('page_students') ?? 1;

        // Items per page
        $perPage = 10;

        // Get students with pagination and search
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
            'name'          => 'required|string|max_length[255]',
            'nim'           => 'required|numeric|is_unique[mahasiswa_coass.nim]',
            'email'         => 'required|valid_email|is_unique[users.email]',
            'date_of_birth' => 'required|valid_date',
            'place_of_birth' => 'required|string|max_length[255]',
            'gender'        => 'required|in_list[Male,Female,Other]',
            'religion'      => 'required|string|max_length[100]',
            'blood_group'   => 'required|in_list[A,B,AB,O,Unknown]',
            'phone'         => 'required|numeric|min_length[10]|max_length[15]',
            'address'       => 'required|string',
            'university'    => 'required|string|max_length[255]',
            'year'          => 'required|integer|greater_than[1999]|less_than_equal_to[' . date('Y') . ']',
            'photo'         => 'permit_empty|is_image[photo]|max_size[photo,2048]|mime_in[photo,image/png,image/jpg,image/jpeg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle file upload
        $photo = $this->request->getFile('photo');
        $photoName = null;

        if ($photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move('uploads/photos', $photoName);
        } else {
            // Jika tidak ada foto yang diunggah, gunakan default-avatar.png
            $photoName = 'default-avatar.jpg';

            // Pastikan file default-avatar.png tersedia di folder uploads/photos
            if (!file_exists('uploads/photos/default-avatar.jpg')) {
                copy('assets\img\default-avatar.jpg', 'uploads/photos/default-avatar.jpg');
            }
        }


        // Store mahasiswa data
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

    private function generateDefaultPassword()
    {
        return 'coass@' . date('Y');
    }


    /**
     * Method untuk menampilkan form edit.
     * Parameter $encryptedID adalah ID yang telah dienkripsi.
     */
    public function edit($encryptedID)
    {
        // Mendekripsi ID
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Get student data
        $student = $this->mahasiswaModel->find($id);

        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Get the user's email
        $userData = $this->userModel->find($student['user_id']);
        $student['email'] = $userData['email'] ?? '';

        $data = [
            'title'      => 'Edit Mahasiswa | SI-COASS',
            'validation' => \Config\Services::validation(),
            'student'    => $student,
            // Mengirimkan juga ID terenkripsi agar bisa digunakan di form action update
            'encryptedID' => $encryptedID
        ];
        return view('admin/students/edit', $data);
    }

    /**
     * Method untuk memproses update data mahasiswa.
     * Parameter $encryptedID adalah ID yang telah dienkripsi.
     */
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
            'name'          => 'required|string|max_length[255]',
            'date_of_birth' => 'required|valid_date',
            'place_of_birth' => 'required|string|max_length[255]',
            'gender'        => 'required|in_list[Male,Female,Other]',
            'religion'      => 'required|string|max_length[100]',
            'blood_group'   => 'required|in_list[A,B,AB,O,Unknown]',
            'phone'         => 'required|numeric|min_length[10]|max_length[15]',
            'address'       => 'required|string',
            'university'    => 'required|string|max_length[255]',
            'year'          => 'required|integer|greater_than[1999]|less_than_equal_to[' . date('Y') . ']',
            'photo'         => 'permit_empty|is_image[photo]|max_size[photo,2048]|mime_in[photo,image/png,image/jpg,image/jpeg]',
        ];

        // Aturan validasi untuk email
        $postEmail = $this->request->getPost('email');
        if ($postEmail !== $currentEmail) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
        } else {
            $rules['email'] = 'required|valid_email';
        }

        // Aturan validasi untuk NIM
        $postNim = $this->request->getPost('nim');
        if ($postNim !== $student['nim']) {
            $rules['nim'] = 'required|numeric|is_unique[mahasiswa_coass.nim]';
        } else {
            $rules['nim'] = 'required|numeric';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Penanganan upload file foto
        $photo = $this->request->getFile('photo');
        $photoName = $student['photo']; // Ambil nama foto saat ini
        if ($photo->isValid() && !$photo->hasMoved()) {
            // Hapus foto lama jika ada
            if ($photoName && file_exists('uploads/photos/' . $photoName)) {
                unlink('uploads/photos/' . $photoName);
            }
            $photoName = $photo->getRandomName();
            $photo->move('uploads/photos', $photoName);
        }

        // Update email di tabel user (jika berubah)
        $this->userModel->update($userId, [
            'email' => $postEmail
        ]);

        // Update data mahasiswa
        $this->mahasiswaModel->update($id, [
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

        return redirect()->to('admin/mahasiswa-coass')->with('success', 'Data mahasiswa berhasil diperbarui');
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
}
