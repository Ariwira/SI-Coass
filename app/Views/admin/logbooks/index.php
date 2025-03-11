<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Mahasiswa</h5>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success mx-4 py-3 text-white fw-bold fs-6">
                            <i class="fa-solid fa-circle-info me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Search Form -->
                    <div class="px-4 pt-3">
                        <form action="<?= base_url('admin/logbook') ?>" method="GET" class="mb-3">
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control ps-5 pe-5"
                                    style="border-radius: 0.5rem;"
                                    placeholder="Cari berdasarkan nama, NIM..."
                                    name="keyword" value="<?= $keyword ?? '' ?>">
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('admin/logbook') ?>"
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NIM</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Logbook</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Disetujui</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Ditolak</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-md mb-0">Tidak ada data logbook yang ditemukan</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students as $student): ?>
                                        <?php
                                        // Ambil total logbook untuk mahasiswa ini
                                        $totals = $totals[$student['coass_id']] ?? [
                                            'total_logbooks' => 0,
                                            'total_verified' => 0,
                                            'total_not_verified' => 0
                                        ];

                                        // Misalkan kita menggunakan coass_id sebagai ID yang dienkripsi
                                        $encryptedID = bin2hex(service('encrypter')->encrypt($student['coass_id'])); // Contoh enkripsi sederhana
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex ps-3 px-2 py-1">
                                                    <div>
                                                        <?php if (!empty($student['photo'])): ?>
                                                            <img src="<?= esc(base_url('uploads/photos/' . $student['photo'])) ?>" class="avatar avatar-sm me-3" style="object-fit: cover; width: 40px; height: 40px;" alt="Foto Mahasiswa">
                                                        <?php else: ?>
                                                            <img src="<?= esc(base_url('assets/img/default-avatar.jpg')) ?>" class="avatar avatar-sm me-3" style="object-fit: cover; width: 40px; height: 40px;" alt="Foto Default">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?= esc($student['name']) ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($student['nim']); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($totals['total_logbooks']); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($totals['total_verified']); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($totals['total_not_verified']); ?></p>
                                            </td>
                                            <td class="pe-4 text-center position-relative">
                                                <div class="dropdown">
                                                    <button style="all: unset;" class="" type="button" id="dropdownMenuButton<?= esc($encryptedID) ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= esc($encryptedID) ?>">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/logbook/detail-logbook/' . esc($encryptedID)) ?>">
                                                                <i class="fa-solid fa-eye me-2" style="width: 16px;"></i> <span>Detail</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links('students', 'custom_pagination') ?> <!-- Ganti 'logbooks' menjadi 'students' -->
                    </div>

                    <div class="px-4 py-2 text-center">
                        <p class="text-xs text-secondary mb-0">
                            Menampilkan <?= count($students) ?> dari <?= esc($pager->getTotal('students')) ?> data mahasiswa
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>