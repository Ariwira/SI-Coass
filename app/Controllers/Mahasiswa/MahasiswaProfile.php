<?php

namespace App\Controllers\Mahasiswa;

use App\Models\MahasiswaModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class MahasiswaProfile extends Controller
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
        // Ambil coass_id dari sesi
        $coassId = session()->get('coass_id');
        if (!$coassId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Mendapatkan data mahasiswa berdasarkan coass_id
        $student = $this->mahasiswaModel->where('coass_id', $coassId)->first();
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data user untuk mendapatkan email
        $userData = $this->userModel->find($student['user_id']);
        $student['email'] = $userData['email'] ?? '';

        $data = [
            'title'      => 'Profil Mahasiswa | SI-COASS',
            'student'    => $student,
            'user'       => $userData,
        ];
        return view('mahasiswa/profile/index', $data);
    }

    public function update()
    {
        // Ambil coass_id dari sesi
        $coassId = session()->get('coass_id');
        if (!$coassId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Mendapatkan data mahasiswa berdasarkan coass_id
        $student = $this->mahasiswaModel->where('coass_id', $coassId)->first();
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
        $this->mahasiswaModel->update($student['coass_id'], [
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

        return redirect()->to('mahasiswa/profil-mahasiswa')->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword()
    {
        // Ambil coass_id dari sesi
        $coassId = session()->get('coass_id');
        if (!$coassId) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Mendapatkan data mahasiswa berdasarkan coass_id
        $student = $this->mahasiswaModel->where('coass_id', $coassId)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
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
            // Simpan error di flashdata
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('error', 'Gagal memperbarui password. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }

        // Update password
        $this->userModel->update($userId, [
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('mahasiswa/profil-mahasiswa')->with('success', 'Password berhasil diperbarui.');
    }
}
