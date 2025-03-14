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

                    <div class="table p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kegiatan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logbooks)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-md mb-0">Tidak ada data logbook yang ditemukan</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logbooks as $logbook): ?>
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $logbook['date']; ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $logbook['activity']; ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php
                                                switch ($logbook['status']) {
                                                    case 'Not Verified':
                                                        echo '<span class="badge badge-sm bg-gradient-warning w-75 text-center">Not Verified</span>';
                                                        break;
                                                    case 'Verified':
                                                        echo '<span class="badge badge-sm bg-gradient-success w-75 text-center">Verified</span>';
                                                        break;
                                                    case 'Pending':
                                                        echo '<span class="badge badge-sm bg-gradient-secondary w-75 text-center">Pending</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-sm bg-gradient-secondary w-75 text-center">Tidak Diketahui</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $logbook['feedback'] ?? 'Tidak ada umpan balik'; ?></p>
                                            </td>
                                            <td class="text-center position-relative">
                                                <div class="dropdown">
                                                    <button style="all: unset;" class="" type="button" id="dropdownMenuButton<?= $logbook['logbook_id'] ?>" data-bs-toggle="dropdown" aria-label="edit button" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical" style="width: 48px;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $logbook['logbook_id'] ?>">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('mahasiswa/logbook/edit/' . $logbook['logbook_id']); ?>">
                                                                <i class="fa-solid fa-edit me-2" style="width: 16px;"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item d-flex align-items-center text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $logbook['logbook_id'] ?>">
                                                                <i class="fa-solid fa-trash me-2" style="width: 16px;"></i>
                                                                <span>Hapus</span>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="deleteModal<?= $logbook['logbook_id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $logbook['logbook_id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content bg-white">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $logbook['logbook_id'] ?>">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close fa-solid fa-xmark text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus logbook ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal">Batal</button>
                                                        <form action="<?= base_url('mahasiswa/logbook/delete/' . $logbook['logbook_id']); ?>" method="POST">
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
                        <!-- Pagination can be added here if needed -->
                    </div>

                    <div class="px-4 py-2 text-center">
                        <p class="text-xs text-secondary mb-0">
                            <!-- Additional information can be added here -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>