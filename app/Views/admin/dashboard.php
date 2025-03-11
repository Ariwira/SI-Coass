<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid py-4">
    <div class="row ">
        <div class="col-lg-3 col-md-3 col-12 py-2">
            <div class="card">
                <span class="mask bg-gradient-info opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa-solid fa-user fa-lg text-dark opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                <?= $totalUsers; ?>
                            </h5>
                            <span class="text-white text-sm">Total Pengguna</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-12 py-2   ">
            <div class="card">
                <span class="mask bg-gradient-warning opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa-solid fa-user-tie fa-lg text-dark opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                <?= $totalMahasiswa; ?>
                            </h5>
                            <span class="text-white text-sm">Total Mahasiswa Coass</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-12 py-2">
            <div class="card">
                <span class="mask bg-gradient-danger opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa-solid fa-user-doctor fa-lg text-dark opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                <?= $totalDoctors; ?>
                            </h5>
                            <span class="text-white text-sm">Total Dokter</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-12 py-2">
            <div class="card">
                <span class="mask bg-gradient-success opacity-10 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="text-start">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa-solid fa-clipboard-list fa-lg text-dark opacity-10" aria-hidden="true"></i>
                            </div>
                            <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                <?= $totalStase; ?>
                            </h5>
                            <span class="text-white text-sm">Total Stase</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- tabel mahasiswa coass -->
    <div class="card my-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h5>Daftar Mahasiswa</h5>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mx-4 py-3 text-white fw-bold fs-6">
                    <i class="fa-solid fa-circle-info me-2"></i><?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="table p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mahasiswa</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NIM</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Universitas</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor Telepon</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Kelamin</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-md mb-0">Tidak ada data mahasiswa yang ditemukan</p>
                                    <?php if (!empty($keyword)): ?>
                                        <p class="text-sm text-secondary mb-0">Coba kata kunci pencarian lain</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <?php
                                // Mengenkripsi ID mahasiswa untuk setiap baris
                                $encryptedID = bin2hex(service('encrypter')->encrypt($student['coass_id']));
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex ps-3 px-2 py-1">
                                            <div>
                                                <?php if ($student['photo']): ?>
                                                    <img src="<?= base_url('uploads/photos/' . $student['photo']) ?>" class="avatar avatar-sm me-3" aria-label="foto pengguna" style="object-fit: cover; width: 40px; height: 40px;">
                                                <?php else: ?>
                                                    <img src="<?= base_url('assets/img/default-avatar.png') ?>" class="avatar avatar-sm me-3" aria-label="foto pengguna" style="object-fit: cover; width: 40px; height: 40px;">
                                                <?php endif; ?>
                                            </div>

                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= $student['name'] ?></h6>
                                                <p class="text-xs text-secondary mb-0"><?= $student['email'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $student['nim'] ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 text-wrap"><?= $student['university'] ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $student['phone'] ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            <?= $student['gender'] == 'Male' ? 'Laki-laki' : ($student['gender'] == 'Female' ? 'Perempuan' : '') ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 text-wrap"><?= $student['address'] ?></p>
                                    </td>
                                    <td class="pe-4 text-center position-relative">
                                        <div class="dropdown">
                                            <button class="p-3" style="all: unset;" type="button" id="dropdownMenuButton<?= $encryptedID ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i> <!-- Ikon titik tiga -->
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $encryptedID ?>">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/mahasiswa-coass/detail-mahasiswa/' . $encryptedID) ?>">
                                                        <i class="fa-solid fa-eye me-2" style="width: 16px;"></i> <span>Detail</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/mahasiswa-coass/edit-mahasiswa/' . $encryptedID) ?>">
                                                        <i class="fa-solid fa-edit me-2" style="width: 16px;"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item d-flex align-items-center text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $encryptedID ?>">
                                                        <i class="fa-solid fa-trash me-2" style="width: 16px;"></i>
                                                        <span>Hapus</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal<?= $encryptedID ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $encryptedID ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content bg-white">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel<?= $encryptedID ?>">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus mahasiswa ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal">Batal</button>
                                                <form action="<?= base_url('admin/mahasiswa-coass/delete-mahasiswa/' . $encryptedID) ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn bg-gradient-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Tombol Lihat Selengkapnya -->
            <div class="text-end py-3 px-4">
                <a href="<?= base_url('admin/mahasiswa-coass') ?>" class="btn bg-gradient-info">Lihat Selengkapnya</a>
            </div>
        </div>
    </div>
    <!-- end tabel mahasiswa coass-->
    <!-- tabel Dokter -->
    <div class="card my-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h5>Daftar Dokter</h5>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mx-4 py-3 text-white fw-bold fs-6">
                    <i class="fa-solid fa-circle-info me-2"></i><?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <div class="table p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Dokter</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor Identitas</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor Telepon</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Kelamin</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kualifikasi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($doctors)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-md mb-0">Tidak ada data dokter yang ditemukan</p>
                                    <?php if (!empty($keyword)): ?>
                                        <p class="text-sm text-secondary mb-0">Coba kata kunci pencarian lain</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($doctors as $doctor): ?>
                                <?php
                                $encryptedID = bin2hex(service('encrypter')->encrypt($doctor['doctor_id']));
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex ps-3 px-2 py-1">
                                            <div>
                                                <?php if ($doctor['photo']): ?>
                                                    <img src="<?= base_url('uploads/photos/' . $doctor['photo']) ?>" class="avatar avatar-sm me-3" aria-label="foto pengguna" style="object-fit: cover; width: 40px; height: 40px;">
                                                <?php else: ?>
                                                    <img src="<?= base_url('assets/img/default-avatar.jpg') ?>" class="avatar avatar-sm me-3" aria-label="foto pengguna" style="object-fit: cover; width: 40px; height: 40px;">
                                                <?php endif; ?>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= $doctor['name'] ?></h6>
                                                <p class="text-xs text-secondary mb-0"><?= $doctor['email'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $doctor['id_card'] ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $doctor['phone'] ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            <?= $doctor['gender'] == 'Male' ? 'Laki-laki' : ($doctor['gender'] == 'Female' ? 'Perempuan' : '') ?>
                                        </p>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $doctor['qualification'] ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 text-wrap"><?= $doctor['address'] ?></p>
                                    </td>
                                    <td class="pe-4 text-center position-relative">
                                        <div class="dropdown">
                                            <button style="all: unset;" class="" type="button" id="dropdownMenuButton<?= $encryptedID ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $encryptedID ?>">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/dokter/detail-dokter/' . $encryptedID) ?>">
                                                        <i class="fa-solid fa-eye me-2" style="width: 16px;"></i> <span>Detail</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/dokter/edit-dokter/' . $encryptedID) ?>">
                                                        <i class="fa-solid fa-edit me-2" style="width: 16px;"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item d-flex align-items-center text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $encryptedID ?>">
                                                        <i class="fa-solid fa-trash me-2" style="width: 16px;"></i>
                                                        <span>Hapus</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal<?= $encryptedID ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $encryptedID ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content bg-white">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel<?= $encryptedID ?>">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus dokter ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal">Batal</button>
                                                <form action="<?= base_url('admin/dokter/delete-dokter/' . $encryptedID) ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn bg-gradient-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Tombol Lihat Selengkapnya -->
            <div class="text-end py-3 px-4">
                <a href="<?= base_url('admin/dokter') ?>" class="btn bg-gradient-info">Lihat Selengkapnya</a>
            </div>
        </div>
    </div>
    <!-- end tabel doctor-->
    <!-- tabel stase -->
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h5>Daftar Stase</h5>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mx-4 py-3 text-white fw-bold fs-6">
                    <i class="fa-solid fa-circle-info me-2"></i><?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="table p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Stase</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Dokter</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Durasi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Departemen</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Mulai</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Selesai</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stases)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-md mb-0">Tidak ada data stase yang ditemukan</p>
                                    <?php if (!empty($keyword)): ?>
                                        <p class="text-sm text-secondary mb-0">Coba kata kunci pencarian lain</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stases as $stase): ?>
                                <?php
                                $encryptedID = bin2hex(service('encrypter')->encrypt($stase['stase_id']));
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0 "><?= $stase['name']; ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 "><?= $stase['doctor_name']; ?></p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <?php
                                        switch ($stase['status']) {
                                            case 'pending':
                                                echo '<span class="badge badge-sm bg-gradient-warning w-75 text-center">Pending</span>';
                                                break;
                                            case 'aktif':
                                                echo '<span class="badge badge-sm bg-gradient-success w-75 text-center">Aktif</span>';
                                                break;
                                            case 'selesai':
                                                echo '<span class="badge badge-sm bg-gradient-secondary w-75 text-center">Selesai</span>';
                                                break;
                                            default:
                                                echo '<span class="badge badge-sm bg-gradient-secondary w-75 text-center">Tidak Diketahui</span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $stase['duration_weeks']; ?> (Minggu)</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $stase['department']; ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $stase['start_date']; ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $stase['end_date']; ?></p>
                                    </td>
                                    <td class="pe-4 text-center position-relative">
                                        <div class="dropdown">
                                            <button style="all: unset;" class="" type="button" id="dropdownMenuButton<?= $encryptedID ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $encryptedID ?>">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/stase/detail-stase/' . $encryptedID); ?>">
                                                        <i class="fa-solid fa-eye me-2" style="width: 16px;"></i> <span>Detail</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/stase/edit-stase/' . $encryptedID); ?>">
                                                        <i class="fa-solid fa-edit me-2" style="width: 16px;"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item d-flex align-items-center text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $encryptedID ?>">
                                                        <i class="fa-solid fa-trash me-2" style="width: 16px;"></i>
                                                        <span>Hapus</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal<?= $encryptedID ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $encryptedID ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content bg-white">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel<?= $encryptedID ?>">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close fa-solid fa-xmark text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus stase ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal">Batal</button>
                                                <form action="<?= base_url('admin/stase/delete-stase/' . $encryptedID); ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn bg-gradient-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Tombol Lihat Selengkapnya -->
            <div class="text-end py-3 px-4">
                <a href="<?= base_url('admin/stase') ?>" class="btn bg-gradient-info">Lihat Selengkapnya</a>
            </div>
        </div>
    </div>
    <!-- end tabel stase -->

</div>

<?= $this->endSection(''); ?>