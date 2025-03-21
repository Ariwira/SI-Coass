<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Stase</h5>
                </div>

                <!-- Search Form -->
                <div class="px-4 pt-3">
                    <form action="<?= base_url('mahasiswa/stase') ?>" method="GET" class="mb-3">
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="form-control ps-5 pe-5"
                                style="border-radius: 0.5rem;"
                                placeholder="Cari berdasarkan nama atau departemen..."
                                name="keyword" value="<?= $keyword ?? '' ?>">
                            <?php if (!empty($keyword)): ?>
                                <a href="<?= base_url('mahasiswa/stase') ?>"
                                    class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"
                                    style="cursor: pointer; background: transparent; border: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Stase</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Dokter</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Departemen</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Durasi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Mulai</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Selesai</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($stases)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 pt-6">
                                            <p class="text-md mb-0">Tidak ada data stase yang ditemukan</p>
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
                                                        echo '<span class="badge badge-sm bg-gradient-primary w-75 text-center">Selesai</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-sm bg-gradient-secondary w-75 text-center">Tidak Diketahui</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $stase['department']; ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $stase['duration_weeks']; ?> (Minggu)</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $stase['start_date']; ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $stase['end_date']; ?></p>
                                            </td>
                                            <td class="align-middle text-center pe-4">
                                                <a class="text-secondary fw-bold text-sm" href="<?= base_url('mahasiswa/stase/detail-stase/' . $encryptedID); ?>">
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
                        <?= $pager->links('stases', 'custom_pagination') ?>
                    </div>

                    <div class="px-4 py-2 text-center">
                        <p class="text-xs text-secondary mb-0">
                            Menampilkan <?= count($stases) ?> dari <?= $pager->getTotal('stases') ?> data stase
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