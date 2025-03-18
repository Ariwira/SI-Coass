<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Edit Logbook</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('mahasiswa/logbook/update/' . $logbook['logbook_id']) ?>" method="POST">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="logbook_id" value="<?= $logbook['logbook_id']; ?>">
                        
                        <div class="mb-3">
                            <label for="coass_id" class="form-label">ID Coass</label>
                            <input type="number" class="form-control" id="coass_id" name="coass_id" value="<?= $logbook['coass_id']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stase_id" class="form-label">ID Stase</label>
                            <input type="number" class="form-control" id="stase_id" name="stase_id" value="<?= $logbook['stase_id']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?= $logbook['date']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="activity" class="form-label">Kegiatan</label>
                            <textarea class="form-control" id="activity" name="activity" required><?= $logbook['activity']; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn bg-gradient-success">Update</button>
                        <a href="<?= base_url('mahasiswa/logbook') ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>