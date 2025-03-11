<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Tambah Logbook</h5>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
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

                    <form action="<?= base_url('admin/logbook/store') ?>" method="POST" class="mx-4">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="coass_id" class="form-label">Mahasiswa</label>
                            <select name="coass_id" id="coass_id" class="form-select" required>
                                <option value="">Pilih Mahasiswa</option>
                                <?php foreach ($mahasiswas as $mahasiswa): ?>
                                    <option value="<?= $mahasiswa['coass_id'] ?>"><?= $mahasiswa['name'] ?> (<?= $mahasiswa['nim'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="stase_id" class="form-label">Stase</label>
                            <select name="stase_id" id="stase_id" class="form-select" required>
                                <option value="">Pilih Stase</option>
                                <?php foreach ($stases as $stase): ?>
                                    <option value="<?= $stase['stase_id'] ?>"><?= $stase['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="activity" class="form-label">Aktivitas</label>
                            <textarea name="activity" id="activity" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Not Verified">Not Verified</option>
                                <option value="Verified">Verified</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback</label>
                            <textarea name="feedback" id="feedback" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn bg-gradient-success">Simpan Logbook</button>
                            <a href="<?= base_url('admin/logbook') ?>" class="btn bg-gradient-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>