<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 overflow-hidden position-relative border-radius-lg" style="background-image: url('<?= base_url(); ?>/assets/img/curved-images/white-curved.jpg'); background-size: cover;">
                <span class="mask bg-gradient-info"></span>
                <div class="card-header pb-0 d-flex justify-content-between align-items-center z-index-1 bg-transparent">
                    <h5 class="text-white">Data Mahasiswa</h5>
                </div>
                <div class="d-flex mb-4 p-4 z-index-1">
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
                        <div class="d-flex flex-column flex-md-row">
                            <div class="col-12 col-md-6 pe-md-2">
                                <div class="text-white mb-3">
                                    <div class="fw-bold text-sm">Nama</div>
                                    <div class=""><?= esc($mahasiswa['name']) ?></div>
                                </div>
                                <div class="text-white mb-3">
                                    <div class="fw-bold text-sm">NIM</div>
                                    <div class=""><?= esc($mahasiswa['nim']) ?></div>
                                </div>
                                <div class="text-white mb-3">
                                    <div class="fw-bold text-sm">Universitas</div>
                                    <div class=""><?= esc($mahasiswa['university']) ?></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 ps-md-2">
                                <div class="text-white mb-3">
                                    <div class="fw-bold text-sm">Angkatan</div>
                                    <div class=""><?= esc($mahasiswa['year']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <!-- Card Tabel Logbook -->
            <div class="card mb-4 ">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Logbook</h5>
                </div>
                <!-- Search Form -->
                <div class="px-4 pt-3">
                    <form action="<?= base_url('dokter/logbook/detail-logbook/' . $encryptedID) ?>" method="GET" class="mb-3">
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="form-control ps-5 pe-5"
                                style="border-radius: 0.5rem;"
                                placeholder="Cari berdasarkan stase atau deskripsi..."
                                name="keyword" value="<?= esc($keyword ?? '') ?>">
                            <?php if (!empty($keyword)): ?>
                                <a href="<?= base_url('dokter/logbook/detail-logbook/' . $encryptedID) ?>"
                                    class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"
                                    style="cursor: pointer; background: transparent; border: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="table">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Stase</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi Kegiatan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Feedback</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logbooks)): ?>
                                <tr>
                                    <td colspan="6" class="text-center pt-6 ">
                                        <p class="text-md mb-0">Tidak ada logbook untuk mahasiswa ini.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logbooks as $logbook): ?>
                                    <?php
                                    // Ambil ID logbook yang sesuai untuk mahasiswa ini
                                    $logbookId = $logbook['logbook_id']; // Ganti dengan cara Anda mendapatkan ID logbook
                                    $encryptedID = bin2hex(service('encrypter')->encrypt($logbookId)); // Enkripsi ID logbook
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['date']) ?></p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['stase_name']) ?></p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 text-wrap"><?= esc($logbook['activity']) ?></p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span style="min-width: 80px; max-width: 100px;" class="badge badge-sm w-75 text-center <?= $logbook['status'] === 'Verified' ? 'bg-gradient-success' : ($logbook['status'] === 'Rejected' ? 'bg-gradient-danger' : 'bg-gradient-primary') ?>">
                                                <?= esc($logbook['status'] === 'Verified' ? 'Disetujui' : ($logbook['status'] === 'Not Verified' ? 'Diproses' : 'Ditolak')) ?>
                                            </span>
                                        </td>
                                        <td class="pe-4">
                                            <p class="text-xs font-weight-bold mb-0 text-wrap"><?= esc($logbook['feedback']) ?></p>
                                        </td>
                                        <td class="align-middle text-center pe-4">
                                            <a class="text-secondary fw-bold text-sm" href="<?= base_url('dokter/logbook/verifikasi-logbook/' . esc($encryptedID)) ?>">
                                                <i class="fa-solid fa-pen-to-square me-1" style="width: 16px;"></i> <span>Verifikasi</span>
                                            </a>
                                        </td>
                                    </tr>

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
    </div>
</div>
<?= $this->endSection(); ?>