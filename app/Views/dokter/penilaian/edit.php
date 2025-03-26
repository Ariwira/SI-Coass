<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Edit Nilai untuk <?= esc($mahasiswa['name']) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url("dokter/penilaian/detail-penilaian/update/$encryptedID/$encryptedCoassID") ?>" method="post">
                        <?= csrf_field() ?>
                        <div class=" mb-3">
                            <label for="date" class="form-label">Tanggal Penilaian</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?= esc($penilaian['date']) ?>" required disabled>
                            <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="score" class="form-label">Nilai</label>
                            <input type="number" class="form-control" id="score" name="score" value="<?= esc($penilaian['score']) ?>" step="1" required>
                            <?php if (isset(session()->getFlashdata('errors')['score'])): ?>
                                <div class="text-danger"><?= esc(session()->getFlashdata('errors')['score']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="3"><?= esc($penilaian['feedback']) ?></textarea>
                        </div>
                        <button type="submit" class="btn bg-gradient-success me-2">Update</button>
                        <a href="<?= base_url("dokter/penilaian/detail-penilaian/$encryptedID") ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>