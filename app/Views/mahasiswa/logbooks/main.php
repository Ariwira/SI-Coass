<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Daftar Logbook</h5>
                    <a class="btn bg-gradient-success" href="<?= base_url('mahasiswa/logbook/create') ?>">
                        <i class="fa-solid fa-plus fa-lg me-2"></i> <span>Tambah Logbook</span>
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
                        <form action="<?= base_url('mahasiswa/logbook') ?>" method="GET" class="mb-3">
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control ps-5 pe-5"
                                    style="border-radius: 0.5rem;"
                                    placeholder="Cari berdasarkan kegiatan atau nama stase..."
                                    name="keyword" value="<?= $keyword ?? '' ?>">
                                <?php if (!empty($keyword)): ?>
                                    <a href="<?= base_url('mahasiswa/logbook') ?>"
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Stase</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kegiatan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logbooks)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-md mb-0">Tidak ada data logbook yang ditemukan</p>
                                            <?php if (!empty($keyword)): ?>
                                                <p class="text-sm text-secondary mb-0">Coba kata kunci pencarian lain</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logbooks as $logbook): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0"><?= $logbook['date']; ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $logbook['stase_name']; ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $logbook['activity']; ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php
                                                switch ($logbook['status']) {
                                                    case 'Verified':
                                                        echo '<span class="badge badge-sm bg-gradient-success w-75 text-center">Disetujui</span>';
                                                        break;
                                                    case 'Pending':
                                                        echo '<span class="badge badge-sm bg-gradient-warning w-75 text-center">Ditolak</span>';
                                                        break;
                                                    case 'Not Verified':
                                                        echo '<span class="badge badge-sm bg-gradient-secondary w-75 text-center">Diproses</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-sm bg-gradient-secondary w-75 text-center">Tidak Diketahui</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td class="pe-4 text-center position-relative">
                                                <div class="dropdown">
                                                    <button style="all: unset;" class="" type="button" id="dropdownMenuButton<?= $logbook['logbook_id'] ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $logbook['logbook_id'] ?>">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('mahasiswa/logbook/edit/' . $logbook['logbook_id']) ?>">
                                                                <i class="fa-solid fa-edit me-2" style="width: 16px;"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center text-danger" href="<?= base_url('mahasiswa/logbook/delete/' . $logbook['logbook_id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus logbook ini?');">
                                                                <i class="fa-solid fa-trash me-2" style="width: 16px;"></i>
                                                                <span>Hapus</span>
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
</div>
<?= $this->endSection(); ?>