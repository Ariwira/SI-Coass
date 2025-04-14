<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 overflow-hidden position-relative border-radius-lg" style="background-image: url('<?= base_url(); ?>/assets/img/curved-images/white-curved.jpg'); background-size: cover;">
                <span class="mask bg-gradient-info"></span>
                <div class="card-header pb-0 d-flex justify-content-between align-items-center z-index-1 bg-transparent">
                    <h5 class="text-white">Detail Penilaian Stase</h5>
                </div>
                <div class="d-flex mb-4 p-4 z-index-1 flex-column flex-md-row">
                    <div class="col-12 col-md-7 pe-md-2 mb-3 mb-md-0">
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Nama Stase</div>
                            <div class=""><?= esc($penilaian['stase_name']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Dokter</div>
                            <div class=""><?= esc($penilaian['doctor_name']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Departemen</div>
                            <div class=""><?= esc($penilaian['department']) ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 ps-md-2">
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Tanggal penilaian$penilaian</div>
                            <div class=""><?= esc($penilaian['date']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Nilai</div>
                            <div class=""><?= esc($penilaian['score']) ?></div>
                        </div>
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Status</div>
                            <div class=""><?= $penilaian['score'] >= 70 ? 'Lulus' : 'Belum Lulus' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Catatan</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success mx-4 py-3 text-white fw-bold fs-6">
                            <i class="fa-solid fa-circle-info me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-body border card-plain border-radius-lg">
                                <h6 class="mb-3">Feedback Dokter</h6>
                                <div class="p-3 bg-light border-radius-lg">
                                    <?php if (!empty($penilaian['feedback'])): ?>
                                        <p class="text-sm mb-0"><?= esc($penilaian['feedback']) ?></p>
                                    <?php else: ?>
                                        <p class="text-sm mb-0 text-muted">Tidak ada feedback dari dokter.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Riwayat Perubahan Nilai -->
        <div class="col-12">
            <div class="card mb-4 ">
                <div class="card-header ">
                    <h5 class="mb-0">Riwayat Perubahan Nilai</h5>
                </div>
                <div class="card-body px-0 pt-0 pb-4">
                    <?php if (!empty($history)): ?>
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 130px;">Tanggal Perubahan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">aksi</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Pengubah</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nilai Lama</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nilai Baru</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Feedback Lama</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Feedback Baru</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($history as $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0 "><?= date('d-m-Y H:i', strtotime($item['created_at'])) ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php
                                                $action = $item['action'] ?? '-';

                                                $actionMap = [
                                                    'create' => ['label' => 'tambah', 'class' => 'bg-gradient-success'],
                                                    'update'   => ['label' => 'edit',   'class' => 'bg-gradient-info'],
                                                    'delete' => ['label' => 'hapus',  'class' => 'bg-gradient-danger'],
                                                ];

                                                $label = $actionMap[$action]['label'] ?? ($action ?: '-');
                                                $class = $actionMap[$action]['class'] ?? 'bg-gradient-secondary';
                                                ?>
                                                <span style="min-width: 80px; max-width: 100px;" class="badge badge-sm w-75 text-center <?= esc($class) ?>">
                                                    <?= esc($label) ?>
                                                </span>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 "><?= esc($item['user_name']) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 "><?= esc($item['old_score'] ?: '-') ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 "><?= esc($item['new_score'] ?: '-') ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 "><?= esc($item['old_feedback'] ?: '-') ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 "><?= esc($item['new_feedback'] ?: '-') ?></p>
                                            </td>


                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted px-4">Belum ada riwayat perubahan nilai.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <a href="<?= base_url('dokter/penilaian/detail-penilaian/' . esc($encryptedID)) ?>" class="btn bg-gradient-info">Kembali</a>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>