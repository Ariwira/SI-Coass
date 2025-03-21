<?php

namespace App\Controllers\Dokter;

use App\Models\DoctorModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class DoctorProfile extends Controller
{
    protected $doctorModel;
    protected $userModel;
    protected $encrypter;

    public function __construct()
    {
        $this->doctorModel = new DoctorModel();
        $this->userModel = new UserModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        // Ambil doctor_id dari sesi
        $doctorId = session()->get('doctor_id');
        if (!$doctorId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Mendapatkan data dokter berdasarkan doctor_id
        $doctor = $this->doctorModel->find($doctorId);
        if (!$doctor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Ambil data user untuk mendapatkan email
        $userData = $this->userModel->find($doctor['user_id']);
        $doctor['email'] = $userData['email'] ?? '';

        $data = [
            'title' => 'Profil Dokter | SI-COASS',
            'doctor' => $doctor,
        ];
        return view('dokter/profile/index', $data);
    }

    public function update()
    {
        // Ambil doctor_id dari sesi
        $doctorId = session()->get('doctor_id');
        if (!$doctorId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Mendapatkan data dokter berdasarkan doctor_id
        $doctor = $this->doctorModel->find($doctorId);
        if (!$doctor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        $userId = $doctor['user_id'];

        // Ambil data user untuk mendapatkan email saat ini
        $user = $this->userModel->find($userId);
        $currentEmail = $user['email'];

        // Buat aturan validasi dasar
        $rules = [
            'name' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required|min_length[3]|max_length[255]'
            ],
            'date_of_birth' => [
                'label' => 'Tanggal Lahir',
                'rules' => 'required|valid_date'
            ],
            'place_of_birth' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required|max_length[255]'
            ],
            'gender' => [
                'label' => 'Jenis Kelamin',
                'rules' => 'required|in_list[Male,Female]'
            ],
            'mother_tongue' => [
                'label' => 'Bahasa Ibu',
                'rules' => 'permit_empty|max_length[100]'
            ],
            'marital_status' => [
                'label' => 'Status Pernikahan',
                'rules' => 'permit_empty|in_list[Single,Married,Divorced,Widowed]'
            ],
            'religion' => [
                'label' => 'Agama',
                'rules' => 'required|in_list[Islam,Hindu,Protestan,Katolik,Buddha,Konghucu]'
            ],
            'blood_group' => [
                'label' => 'Golongan Darah',
                'rules' => 'required|in_list[A,B,AB,O,Unknown]'
            ],
            'city' => [
                'label' => 'Kota',
                'rules' => 'required|max_length[255]'
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'required'
            ],
            'state' => [
                'label' => 'Provinsi',
                'rules' => 'required|max_length[100]'
            ],
            'qualification' => [
                'label' => 'Kualifikasi',
                'rules' => 'required|max_length[255]'
            ],
            'nationality' => [
                'label' => 'Kewarganegaraan',
                'rules' => 'required|max_length[100]'
            ],
            'phone' => [
                'label' => 'Nomor Telepon',
                'rules' => 'required|numeric|min_length[10]|max_length[15]'
            ],
            'mobile_no' => [
                'label' => 'Nomor HP',
                'rules' => 'permit_empty|numeric|min_length[10]|max_length[15]'
            ],
            'photo' => [
                'label' => 'Foto Profil',
                'rules' => 'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
            ]
        ];

        // Aturan validasi untuk email
        $postEmail = $this->request->getPost('email');
        if ($postEmail !== $currentEmail) {
            $rules['email'] = [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]'
            ];
        } else {
            $rules['email'] = [
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ];
        }

        // Aturan validasi untuk ID Card
        $postIdCard = $this->request->getPost('id_card');
        if ($postIdCard !== $doctor['id_card']) {
            $rules['id_card'] = [
                'label' => 'Nomor Identitas',
                'rules' => 'required|numeric|min_length[16]|max_length[100]|is_unique[doctors.id_card]'
            ];
        } else {
            $rules['id_card'] = [
                'label' => 'Nomor Identitas',
                'rules' => 'required|numeric'
            ];
        }

        // Validasi input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle upload foto
        $photo = $this->request->getFile('photo');
        $photoName = $doctor['photo']; // Nama foto saat ini
        if ($photo->isValid() && !$photo->hasMoved()) {
            // Hapus foto lama jika ada dan bukan avatar default
            if ($photoName && $photoName !== 'default-avatar.jpg' && file_exists('uploads/photos/' . $photoName)) {
                unlink('uploads/photos/' . $photoName);
            }
            $photoName = $photo->getRandomName();
            $photo->move('uploads/photos', $photoName);
        }

        // Update email pengguna jika berubah
        $this->userModel->update($userId, [
            'email' => $postEmail
        ]);

        // Update data dokter
        $this->doctorModel->update($doctorId, [
            'name' => $this->request->getPost('name'),
            'id_card' => $this->request->getPost('id_card'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'place_of_birth' => $this->request->getPost('place_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'mother_tongue' => $this->request->getPost('mother_tongue'),
            'marital_status' => $this->request->getPost('marital_status'),
            'religion' => $this->request->getPost('religion'),
            'blood_group' => $this->request->getPost('blood_group'),
            'city' => $this->request->getPost('city'),
            'address' => $this->request->getPost('address'),
            'state' => $this->request->getPost('state'),
            'qualification' => $this->request->getPost('qualification'),
            'nationality' => $this->request->getPost('nationality'),
            'phone' => $this->request->getPost('phone'),
            'mobile_no' => $this->request->getPost('mobile_no'),
            'photo' => $photoName
        ]);

        return redirect()->to('dokter/profil-dokter')->with('success', 'Profil dokter berhasil diperbarui');
    }

    public function updatePassword()
    {
        // Ambil doctor_id dari sesi
        $doctorId = session()->get('doctor_id');
        if (!$doctorId) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Mendapatkan data dokter berdasarkan doctor_id
        $doctor = $this->doctorModel->find($doctorId);
        if (!$doctor) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

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
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('error', 'Gagal memperbarui password. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }

        $userId = $doctor['user_id'];
        $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $this->userModel->update($userId, [
            'password' => $newPassword,
        ]);

        return redirect()->to('dokter/profil-dokter')->with('success', 'Password berhasil diperbarui.');
    }
}
