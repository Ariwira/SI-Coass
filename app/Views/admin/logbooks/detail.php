<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 bg-gradient-info">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center bg-gradient-info">
                    <h5 class="text-white">Data Mahasiswa</h5>
                </div>
                <div class="d-flex mb-4 p-4">
                    <!-- Gambar Mahasiswa -->
                    <div class="flex-shrink-0 me-4 pt-2">
                        <?php if (!empty($mahasiswa['photo'])): ?>
                            <img src="<?= esc(base_url('uploads/photos/' . $mahasiswa['photo'])) ?>" class="avatar" style="object-fit: cover; width: 160px; height: 160px;" alt="Foto Mahasiswa">
                        <?php else: ?>
                            <img src="<?= esc(base_url('assets/img/default-avatar.jpg')) ?>" class="avatar" style="object-fit: cover; width: 160px; height: 160px;" alt="Foto Default">
                        <?php endif; ?>
                    </div>

                    <!-- Data Diri Mahasiswa -->
                    <div class="flex-grow-1">
                        <table class="table table-borderless text-white mb-0"> <!-- Mengatur warna teks tabel menjadi putih -->
                            <tbody>
                                <tr>
                                    <td class="text-white fw-bold w-15">Nama</td>
                                    <td class="text-white">: <?= esc($mahasiswa['name']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-white fw-bold w-15">NIM</td>
                                    <td class="text-white">: <?= esc($mahasiswa['nim']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-white fw-bold w-15">Univtas</td>
                                    <td class="text-white">: <?= esc($mahasiswa['university']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-white fw-bold w-15">Angkatan</td>
                                    <td class="text-white">: <?= esc($mahasiswa['year']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <!-- Card Tabel Logbook -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Daftar Logbook</h5>
                </div>
                <div class="table">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kegiatan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Stase</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Feedback</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logbooks)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-md mb-0">Tidak ada logbook untuk mahasiswa ini.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logbooks as $logbook): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['date']) ?></p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 text-wrap"><?= esc($logbook['activity']) ?></p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['stase_id']) ?></p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm w-75 text-center <?= $logbook['status'] === 'Verified' ? 'bg-gradient-success' : ($logbook['status'] === 'Not_Verified' ? 'bg-gradient-danger' : 'bg-gradient-secondary') ?>">
                                                <?= esc($logbook['status'] === 'Verified' ? 'Disetujui' : ($logbook['status'] === 'Not_Verified' ? 'Ditolak' : 'Pending')) ?>
                                            </span>
                                        </td>

                                        <td class="pe-4">
                                            <p class="text-xs font-weight-bold mb-0 text-wrap"><?= esc($logbook['feedback']) ?></p>
                                        </td>
                                        <td class="pe-4 text-center position-relative">
                                            <div class="dropdown">
                                                <button style="all: unset;" class="" type="button" id="dropdownMenuButton<?= $encryptedID ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $encryptedID ?>">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/stase/edit-logbook/' . $encryptedID); ?>">
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
                                                    Apakah Anda yakin ingin menghapus logbook ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal">Batal</button>
                                                    <form action="<?= base_url('admin/stase/delete-logbook/' . $encryptedID); ?>" method="POST">
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
                    <?= $pager->links('logbooks', 'custom_pagination') ?>
                </div>

                <div class="px-4 py-2 text-center">
                    <p class="text-xs text-secondary mb-0">
                        Menampilkan <?= count($logbooks) ?> dari <?= $pager->getTotal('logbooks') ?> data logbook
                        <?php if (!empty($keyword)): ?>
                            untuk pencarian "<?= $keyword ?>"
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div>
            <div class="mx-0 btn bg-gradient-info">
                <a href="<?= base_url('admin/logbook') ?>" class="text-xs text-white">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Mahasiswa
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>