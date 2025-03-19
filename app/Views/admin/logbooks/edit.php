<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Edit Logbook</h5>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <form action="<?= base_url('admin/logbook/update/' . $encryptedID) ?>" method="POST" class="mx-4">
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

                        <!-- Hidden input untuk coass_id -->
                        <input type="hidden" name="coass_id" value="<?= esc($mahasiswa['coass_id']) ?>">

                        <div class="mb-3">
                            <label for="stase_id" class="form-label">Stase</label>
                            <select name="stase_id" id="stase_id" class="form-select" required>
                                <option value="">Pilih Stase</option>
                                <?php foreach ($stases as $stase): ?>
                                    <option value="<?= $stase['stase_id'] ?>" <?= old('stase_id', $logbook['stase_id']) == $stase['stase_id'] ? 'selected' : '' ?>><?= $stase['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($validation) && $validation->getError('stase_id')): ?>
                                <div class="text-danger"><?= $validation->getError('stase_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" name="date" id="date" class="form-control" value="<?= old('date', $logbook['date']) ?>" required>
                            <?php if (isset($validation) && $validation->getError('date')): ?>
                                <div class="text-danger"><?= $validation->getError('date') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="activity" class="form-label">Aktivitas</label>
                            <textarea name="activity" id="activity" class="form-control" required><?= old('activity', $logbook['activity']) ?></textarea>
                            <?php if (isset($validation) && $validation->getError('activity')): ?>
                                <div class="text-danger"><?= $validation->getError('activity') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Pilih Status</option>
                                <option value="Not Verified" <?= old('status', $logbook['status']) == 'Not Verified' ? 'selected' : '' ?>>Diproses</option>
                                <option value="Verified" <?= old('status', $logbook['status']) == 'Verified' ? 'selected' : '' ?>>Disetujui</option>
                                <option value="Rejected" <?= old('status', $logbook['status']) == 'Rejected' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                            <?php if (isset($validation) && $validation->getError('status')): ?>
                                <div class="text-danger"><?= $validation->getError('status') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback (Opsional)</label>
                            <textarea name="feedback" id="feedback" class="form-control"><?= old('feedback', $logbook['feedback']) ?></textarea>
                        </div>

                        <button type="submit" class="btn bg-gradient-success">Simpan</button>
                        <a href="<?= base_url('admin/logbook/detail-logbook/' . $encryptedID) ?>" class="btn bg-gradient-info">
                            Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>