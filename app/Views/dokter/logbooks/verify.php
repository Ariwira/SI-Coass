<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Verifikasi Logbook</h5>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <form action="<?= base_url('dokter/logbook/update-verification/' . $encryptedID) ?>" method="POST" class="mx-4">
                        <?= csrf_field() ?>

                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger mx-4 py-3 text-white fw-bold fs-6">
                                <i class="fa-solid fa-circle-info me-2"></i>
                                <ul>
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Informasi Logbook (Tidak Bisa Diedit) -->
                        <div class="mb-3">
                            <label class="form-label">Nama Mahasiswa</label>
                            <input type="text" class="form-control" value="<?= esc($mahasiswa['name']) ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stase</label>
                            <input type="text" class="form-control" value="<?= esc($logbook['stase_name']) ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="text" class="form-control" value="<?= esc($logbook['date']) ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Aktivitas</label>
                            <textarea class="form-control" disabled><?= esc($logbook['activity']) ?></textarea>
                        </div>

                        <!-- Input yang Bisa Diedit -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Pilih Status</option>
                                <option value="Not Verified" <?= old('status', $logbook['status']) == 'Not Verified' ? 'selected' : '' ?>>Diproses</option>
                                <option value="Verified" <?= old('status', $logbook['status']) == 'Verified' ? 'selected' : '' ?>>Disetujui</option>
                                <option value="Rejected" <?= old('status', $logbook['status']) == 'Rejected' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback (Opsional)</label>
                            <textarea name="feedback" id="feedback" class="form-control"><?= old('feedback', $logbook['feedback']) ?></textarea>
                        </div>

                        <button type="submit" class="btn bg-gradient-success">Simpan</button>
                        <a href="<?= base_url('dokter/logbook/detail-logbook/' . $encryptedCoassID) ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>