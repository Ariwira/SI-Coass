<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 overflow-hidden position-relative border-radius-lg" style="background-image: url('<?= base_url(); ?>/assets/img/curved-images/white-curved.jpg'); background-size: cover;">
                <span class="mask bg-gradient-info"></span>
                <div class="card-header pb-0 d-flex justify-content-between align-items-center z-index-1 bg-transparent">
                    <h5 class="text-white">Detail Stase</h5>
                </div>
                <div class="d-flex mb-4 p-4 z-index-1 flex-column flex-md-row">
                    <div class="col-12 col-md-7 pe-md-2 mb-3 mb-md-0">
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Nama Stase</div>
                            <div class=""><?= esc($stase['name']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Deskripsi</div>
                            <div class=""><?= esc($stase['description']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Status</div>
                            <div class=""><?= esc($stase['status']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Durasi</div>
                            <div class=""><?= esc($stase['duration_weeks']) ?> minggu</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 ps-md-2">
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Dokter</div>
                            <div class=""><?= esc($stase['doctor_name']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Departemen</div>
                            <div class=""><?= esc($stase['department']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Tanggal Mulai</div>
                            <div class=""><?= esc($stase['start_date']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Tanggal Akhir</div>
                            <div class=""><?= esc($stase['end_date']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Mahasiswa</h5>
                </div>
                <div class="card-body px-0 pt-0 pb-2">

                    <!-- Search Form -->
                    <div class="px-4 pt-3">
                        <form action="<?= base_url('mahasiswa/stase/detail-stase/' . $encryptedID) ?>" method="GET" class="mb-3">
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control ps-5 pe-5"
                                    style="border-radius: 0.5rem;"
                                    placeholder="Cari berdasarkan nama, NIM, atau universitas..."
                                    name="keyword" value="<?= $keyword ?? '' ?>">
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('mahasiswa/stase/detail-stase/' . $encryptedID) ?>"
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"
                                        style="cursor: pointer; background: transparent; border: none;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NIM</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Universitas</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor Telepon</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($mahasiswa)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center pt-6">
                                            <p class="text-md mb-0">Tidak ada mahasiswa terdaftar dalam stase ini.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($mahasiswa as $mhs): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex ps-3 px-2 py-1">
                                                    <div>
                                                        <?php if (!empty($mhs['photo'])): ?>
                                                            <img src="<?= esc(base_url('uploads/photos/' . $mhs['photo'])) ?>"
                                                                class="avatar avatar-sm me-3"
                                                                style="object-fit: cover; width: 40px; height: 40px;"
                                                                alt="Foto Mahasiswa">
                                                        <?php else: ?>
                                                            <img src="<?= esc(base_url('assets/img/default-avatar.jpg')) ?>"
                                                                class="avatar avatar-sm me-3"
                                                                style="object-fit: cover; width: 40px; height: 40px;"
                                                                alt="Foto Default">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm text-wrap"><?= esc($mhs['name']) ?></h6>
                                                        <p class="text-xs font-weight-bold mb-0"><?= esc($mhs['email']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($mhs['nim']) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 text-wrap"><?= esc($mhs['university']) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= esc($mhs['phone']) ?></p>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links('mahasiswa', 'custom_pagination') ?>
                    </div>

                    <div class="px-4 py-2 text-center">
                        <p class="text-xs text-secondary mb-0">
                            Menampilkan <?= count($mahasiswa) ?> dari <?= $pager->getTotal('mahasiswa') ?> data mahasiswa
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
</div>

<?= $this->endSection(); ?>