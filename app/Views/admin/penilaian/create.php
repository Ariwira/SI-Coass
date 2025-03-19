<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Tambah Nilai untuk <?= esc($mahasiswa['name']) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url("admin/penilaian/detail-penilaian/store/$encryptedID/$encryptedCoassID") ?>" method="post">
                        <?= csrf_field() ?> <!-- CSRF Token -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal Penilaian</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>" required disabled>
                            <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">
                            <?php if (isset(session()->getFlashdata('errors')['date'])): ?>
                                <div class="text-danger text-xs pt-1">* <?= esc(session()->getFlashdata('errors')['date']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="score" class="form-label">Nilai</label>
                            <input type="number" class="form-control" id="score" name="score" step="1" required>
                            <?php if (isset(session()->getFlashdata('errors')['score'])): ?>
                                <div class="text-danger text-xs pt-1">* <?= esc(session()->getFlashdata('errors')['score']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="3"></textarea>
                            <?php if (isset(session()->getFlashdata('errors')['feedback'])): ?>
                                <div class="text-danger text-xs pt-1">* <?= esc(session()->getFlashdata('errors')['feedback']) ?></div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn bg-gradient-success me-2">Simpan</button>
                        <a href="<?= base_url("admin/penilaian/detail-penilaian/$encryptedID") ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>