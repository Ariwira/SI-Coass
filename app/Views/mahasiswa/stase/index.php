<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Stase</h5>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
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
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($stases as $stase): ?>
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
                                                    <button style="all: unset;" class="" type="button" id="dropdownMenuButton<?= $stase['stase_id'] ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $stase['stase_id'] ?>">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('mahasiswa/stase/detail/' . $stase['stase_id']); ?>">
                                                                <i class="fa-solid fa-eye me-2" style="width: 16px;"></i> <span>Detail</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('mahasiswa/logbook/tambah/' . $stase['stase_id']); ?>">
                                                                <i class="fa-solid fa-plus me-2" style="width: 16px;"></i>
                                                                <span>Tambah Logbook</span>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>