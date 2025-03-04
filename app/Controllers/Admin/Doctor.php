<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\DoctorModel;
use App\Models\UserModel;

class Doctor extends Controller
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
        $keyword = $this->request->getGet('keyword');
        $currentPage = $this->request->getGet('page_doctors') ?? 1;
        $perPage = 10;

        if (!empty($keyword)) {
            $doctors = $this->doctorModel->search($keyword)->paginate($perPage, 'doctors');
        } else {
            $doctors = $this->doctorModel->getDoctorsWithUsers()->paginate($perPage, 'doctors');
        }

        $data = [
            'title' => 'Manajemen Dokter | SI-COASS',
            'doctors' => $doctors,
            'pager' => $this->doctorModel->pager,
            'currentPage' => $currentPage,
            'keyword' => $keyword
        ];
        return view('admin/doctors/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Dokter | SI-COASS',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/doctors/create', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'id_card' => 'required|numeric|min_length[16]|max_length[100]|is_unique[doctors.id_card]',
            'email'         => 'required|valid_email|is_unique[users.email]',
            'date_of_birth' => 'required|valid_date',
            'place_of_birth' => 'required|string|max_length[255]',
            'gender' => 'required|in_list[Male,Female]',
            'mother_tongue' => 'permit_empty|string|max_length[100]',
            'marital_status' => 'permit_empty|in_list[Single,Married,Divorced,Widowed]',
            'religion' => 'required|in_list[Islam,Hindu,Protestan, Katolik, Buddha, Konghucu]',
            'blood_group' => 'required|in_list[A,B,AB,O,Unknown]',
            'city' => 'required|string|max_length[255]',
            'address' => 'required|string',
            'state' => 'required|string|max_length[100]',
            'qualification' => 'required|string|max_length[255]',
            'nationality' => 'required|string|max_length[100]',
            'phone' => 'required|string|max_length[20]',
            'mobile_no' => 'permit_empty|string|max_length[20]',
            'photo' => 'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
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

        $this->doctorModel->save([
            'user_id' => $this->userModel->insert(['email' => $this->request->getPost('email'), 'password' => password_hash($this->generateDefaultPassword(), PASSWORD_DEFAULT), 'role' => 'Dokter']),
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
            'photo'         => $photoName
        ]);


        return redirect()->to('admin/dokter')->with('success', 'Data dokter berhasil ditambahkan');
    }

    public function edit($encryptedID)
    {

        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $doctor = $this->doctorModel->find($id);
        if (!$doctor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $userData = $this->userModel->find($doctor['user_id']);
        $doctor['email'] = $userData['email'] ?? '';

        $data = [
            'title' => 'Edit Dokter | SI-COASS',
            'validation' => \Config\Services::validation(),
            'doctor' => $doctor,
            'encryptedID' => $encryptedID
        ];

        return view('admin/doctors/edit', $data);
    }

    public function update($encryptedID)
    {
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $doctor = $this->doctorModel->find($id);
        if (!$doctor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        $userId = $doctor['user_id'];

        $user = $this->userModel->find($doctor['user_id']);
        $currentEmail = $user['email'];

        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'date_of_birth' => 'required|valid_date',
            'place_of_birth' => 'required|string|max_length[255]',
            'gender' => 'required|in_list[Male,Female]',
            'mother_tongue' => 'permit_empty|string|max_length[100]',
            'marital_status' => 'permit_empty|in_list[Single,Married,Divorced,Widowed]',
            'religion' => 'required|in_list[Islam,Hindu,Protestan, Katolik, Buddha, Konghucu]',
            'blood_group' => 'required|in_list[A,B,AB,O,Unknown]',
            'city' => 'required|string|max_length[255]',
            'address' => 'required|string',
            'state' => 'required|string|max_length[100]',
            'qualification' => 'required|string|max_length[255]',
            'nationality' => 'required|string|max_length[100]',
            'phone' => 'required|string|max_length[20]',
            'mobile_no' => 'permit_empty|string|max_length[20]',
            'photo' => 'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
        ];

        // Aturan validasi untuk email
        $postEmail = $this->request->getPost('email');
        if ($postEmail !== $currentEmail) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
        } else {
            $rules['email'] = 'required|valid_email';
        }

        $postIdCard = $this->request->getPost('id_card');
        if ($postIdCard !== $doctor['id_card']) {
            $rules['id_card'] = 'required|numeric|min_length[16]|max_length[100]|is_unique[doctors.id_card]';
        } else {
            $rules['id_card'] = 'required|numeric';
        }

        // Validate the input
        if (!$this->validate($rules)) {
            log_message('error', print_r($this->validator->getErrors(), true)); // Log errors for debugging
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        $photoName = $doctor['photo']; // Current photo name
        if ($photo->isValid() && !$photo->hasMoved()) {
            // Delete old photo if exists
            if ($photoName && file_exists('uploads/photos/' . $photoName)) {
                unlink('uploads/photos/' . $photoName);
            }
            $photoName = $photo->getRandomName();
            $photo->move('uploads/photos', $photoName);
        }

        // Update user email if changed
        $this->userModel->update($userId, [
            'email' => $postEmail
        ]);

        // Update doctor data
        $this->doctorModel->update($id, [
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

        return redirect()->to('admin/dokter')->with('success', 'Data dokter berhasil diperbarui');
    }
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
            // Ambil data dokter terlebih dahulu
            $doctor = $this->doctorModel->find($id);

            if (!$doctor) {
                throw new \Exception('Data dokter tidak ditemukan');
            }

            // Delete photo if exists and not default
            if (
                !empty($doctor['photo']) &&
                $doctor['photo'] !== 'default-avatar.jpg' &&
                file_exists('uploads/photos/' . $doctor['photo'])
            ) {
                unlink('uploads/photos/' . $doctor['photo']);
            }
            // Hapus data dokter
            $this->doctorModel->delete($id);

            // Hapus akun pengguna yang terkait
            $this->userModel->delete($doctor['user_id']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menghapus data dokter');
            }

            return redirect()->to('admin/dokter')->with('success', 'Data dokter dan akun berhasil dihapus');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('admin/dokter')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateDefaultPassword()
    {
        return 'dokter@' . date('Y');
    }
}
