<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Tambah Logbook</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('mahasiswa/logbook/store') ?>" method="POST">
                        <?= csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label for="coass_id" class="form-label">ID Coass</label>
                            <input type="number" class="form-control" id="coass_id" name="coass_id" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stase_id" class="form-label">ID Stase</label>
                            <input type="number" class="form-control" id="stase_id" name="stase_id" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="activity" class="form-label">Kegiatan</label>
                            <textarea class="form-control" id="activity" name="activity" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn bg-gradient-success">Simpan</button>
                        <a href="<?= base_url('mahasiswa/logbook') ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>