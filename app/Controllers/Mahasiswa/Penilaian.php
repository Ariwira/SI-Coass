<?php
namespace App\Controllers\Mahasiswa;

use CodeIgniter\Controller;
use App\Models\PenilaianModel;
use App\Models\MahasiswaModel;
use App\Models\StaseModel;
use CodeIgniter\Encryption\Encryption;

class Penilaian extends Controller
{
    protected $penilaianModel;
    protected $encrypter;

    public function __construct()
    {
        $this->penilaianModel = new PenilaianModel();
        $this->encrypter = \Config\Services::encrypter(); // Inisialisasi encrypter
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $coass_id = session()->get('coass_id');

        // Ambil data penilaian dengan pagination
        $penilaian = $this->penilaianModel->getPenilaianByMahasiswa($coass_id, $keyword);
        
        // Inisialisasi pager
        $pager = \Config\Services::pager();

        $data = [
            'title' => 'Penilaian | SI-COASS',
            'penilaian' => $penilaian,
            'encrypter' => $this->encrypter,
            'pager' => $pager,
            'keyword' => $keyword,
            'total' => $this->penilaianModel->getTotalPenilaianByMahasiswa($coass_id, $keyword), // Ambil total penilaian
        ];
        return view('mahasiswa/penilaian/index', $data);
    }

    public function detail($encryptedID)
    {
        // Dekripsi ID penilaian
        try {
            $id = $this->encrypter->decrypt(hex2bin($encryptedID));
        } catch (\Exception $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $penilaian = $this->penilaianModel->getDetailPenilaian($id);
        if (!$penilaian) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Penilaian | SI-COASS',
            'penilaian' => $penilaian,
            'encryptedID' => $encryptedID,
        ];
        return view('mahasiswa/penilaian/detail', $data);
    }
}