<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Tambah Stase</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/stase/store') ?>" method="POST">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Pilih Dokter</label>
                            <select class="form-select" id="doctor_id" name="doctor_id" required>
                                <option value="">Pilih Dokter</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?= $doctor['doctor_id']; ?>"><?= $doctor['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Stase</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label ">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Departemen</label>
                            <input type="text" class="form-control" id="department" name="department" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn bg-gradient-success">Simpan</button>
                        <a href="<?= base_url('admin/stase') ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>