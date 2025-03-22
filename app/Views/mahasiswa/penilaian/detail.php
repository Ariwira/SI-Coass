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
                            <div class=""><?= esc($penilaian['department'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 ps-md-2">
                        <div class="text-white mb-3">
                            <div class="fw-bold text-sm">Tanggal Penilaian</div>
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

                    <?php if (!empty($penilaian['criteria'])): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-body border card-plain border-radius-lg">
                                <h6 class="mb-3">Rincian Penilaian</h6>
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kriteria</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Nilai</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($penilaian['criteria'] as $criteria): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?= esc($criteria['name']) ?></h6>
                                                            <p class="text-xs text-secondary mb-0"><?= esc($criteria['description'] ?? '') ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-gradient-<?= $criteria['score'] >= 70 ? 'success' : 'warning' ?>"><?= esc($criteria['score']) ?></span>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0"><?= esc($criteria['notes'] ?? '-') ?></p>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($penilaian['attachments'])): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-body border card-plain border-radius-lg">
                                <h6 class="mb-3">Lampiran</h6>
                                <div class="row">
                                    <?php foreach ($penilaian['attachments'] as $attachment): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card card-body border-0 shadow-sm">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-file-pdf text-danger me-3 fa-2x"></i>
                                                <div>
                                                    <p class="text-xs font-weight-bold mb-0"><?= esc($attachment['name']) ?></p>
                                                    <a href="<?= base_url('uploads/attachments/' . $attachment['file']) ?>" 
                                                       class="text-xs text-primary" target="_blank">
                                                        Lihat Dokumen
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="<?= base_url('mahasiswa/penilaian') ?>" class="btn bg-gradient-success">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>