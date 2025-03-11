<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 bg-gradient-info">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center bg-gradient-info">
                    <h5 class="text-white">Detail Logbook Mahasiswa</h5>
                </div>
                <div class="d-flex mb-4 p-4">
                    <!-- Gambar Mahasiswa -->
                    <div class="flex-shrink-0 me-4 pt-2">
                        <?php if (!empty($mahasiswa['photo'])): ?>
                            <img src="<?= esc(base_url('uploads/photos/' . $mahasiswa['photo'])) ?>" class="avatar" style="object-fit: cover; width: 200px; height: 200px;" alt="Foto Mahasiswa">
                        <?php else: ?>
                            <img src="<?= esc(base_url('assets/img/default-avatar.jpg')) ?>" class="avatar" style="object-fit: cover; width: 200px; height: 200px;" alt="Foto Default">
                        <?php endif; ?>
                    </div>

                    <!-- Data Diri Mahasiswa -->
                    <div class="flex-grow-1">
                        <table class="table table-borderless text-white mb-0"> <!-- Mengatur warna teks tabel menjadi putih -->
                            <tbody>
                                <tr>
                                    <td class="text-white"><strong>Nama</strong></td>
                                    <td class="text-white">: <?= esc($mahasiswa['name']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-white"><strong>NIM</strong></td>
                                    <td class="text-white">: <?= esc($mahasiswa['nim']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-white"><strong>Universitas</strong></td>
                                    <td class="text-white">: <?= esc($mahasiswa['university']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-white"><strong>Angkatan</strong></td>
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
                    <h5>Logbook</h5>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kegiatan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stase</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Feedback</th>
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
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['date']) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['activity']) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['stase_id']) ?></p> <!-- Ganti dengan nama stase jika ada -->
                                            </td>
                                            <td>
                                                <span class="badge badge-sm <?= $logbook['status'] === 'Disetujui' ? 'bg-gradient-success' : ($logbook['status'] === 'Ditolak' ? 'bg-gradient-danger' : 'bg-gradient-secondary') ?>">
                                                    <?= esc($logbook['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($logbook['feedback']) ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="mt-4 text-center">
                <a href="<?= base_url('admin/logbook') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Mahasiswa
                </a>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?= $this->endSection(); ?>