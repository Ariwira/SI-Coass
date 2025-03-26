<?= $this->extend('layout/template'); ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Penilaian</h5>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success mx-4 py-3 text-white fw-bold fs-6">
                            <i class="fa-solid fa-circle-info me-2"></i><?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Search Form -->
                    <div class="px-4 pt-3">
                        <form action="<?= base_url('mahasiswa/penilaian') ?>" method="GET" class="mb-3">
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control ps-5 pe-5"
                                    style="border-radius: 0.5rem;"
                                    placeholder="Cari berdasarkan stase atau dokter..."
                                    name="keyword" value="<?= $keyword ?? '' ?>">
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('mahasiswa/penilaian') ?>"
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stase</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dokter</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nilai</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Feedback</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($penilaian)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-md mb-0">Tidak ada data penilaian yang ditemukan</p>
                                            <?php if (!empty($keyword)): ?>
                                                <p class="text-sm text-secondary mb-0">Coba kata kunci pencarian lain</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($penilaian as $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0 "><?= esc($item['stase_name']); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 "><?= esc($item['doctor_name']); ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?= isset($item['date']) && !empty($item['date']) ? esc($item['date']) : '-' ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?= isset($item['score']) && !empty($item['score']) ? esc($item['score']) : '-' ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?= isset($item['feedback']) && !empty($item['feedback']) ? esc($item['feedback']) : '-' ?>
                                                </p>
                                            </td>

                                            <td class="align-middle text-center pe-4">
                                                <a class="text-secondary fw-bold text-sm" href="<?= base_url('mahasiswa/penilaian/detail-penilaian/' . bin2hex($encrypter->encrypt($item['penilaian_id']))) ?>">
                                                    <i class="fa-solid fa-eye me-1" style="width: 16px;"></i> <span>Detail</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links('penilaian', 'custom_pagination') ?>
                    </div>

                    <div class="px-4 py-2 text-center">
                        <p class="text-xs text-secondary mb-0">
                            Menampilkan <?= count($penilaian) ?> dari <?= esc($total) ?> data penilaian
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>