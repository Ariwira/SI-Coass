<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Mahasiswa</h5>
                    <a class="btn bg-gradient-success" href="<?= base_url('admin/mahasiswa-coass/tambah-mahasiswa') ?>">
                        <i class="fa-solid fa-plus fa-lg me-2"></i> <span>Tambah Mahasiswa</span>
                    </a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success mx-4 py-3 text-white fw-bold fs-6">
                            <i class="fa-solid fa-circle-info me-2"></i><?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Search Form -->
                    <div class="px-4 pt-3">
                        <form action="<?= base_url('admin/mahasiswa-coass') ?>" method="GET" class="mb-3">
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control ps-5 pe-5"
                                    style="border-radius: 0.5rem;"
                                    placeholder="Cari berdasarkan nama, NIM, email, atau universitas..."
                                    name="keyword" value="<?= $keyword ?? '' ?>">
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('admin/mahasiswa-coass') ?>"
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"
                                        style="cursor: pointer; background: transparent; border: none;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

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
                                                            <img src="<?= base_url('uploads/photos/' . $student['photo']) ?>" class="avatar avatar-sm me-3" style="object-fit: cover; width: 40px; height: 40px;">
                                                        <?php else: ?>
                                                            <img src="<?= base_url('assets/img/default-avatar.png') ?>" class="avatar avatar-sm me-3" style="object-fit: cover; width: 40px; height: 40px;">
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

                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links('students', 'custom_pagination') ?>
                    </div>

                    <div class="px-4 py-2 text-center">
                        <p class="text-xs text-secondary mb-0">
                            Menampilkan <?= count($students) ?> dari <?= $pager->getTotal('students') ?> data mahasiswa
                            <?php if (!empty($keyword)): ?>
                                untuk pencarian "<?= $keyword ?>"
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>