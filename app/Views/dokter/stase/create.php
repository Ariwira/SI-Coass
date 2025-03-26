<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header pb-0">
                    <h5>Tambah Stase</h5>
                </div>
                <div class="card-body pt-0">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger py-3 text-white fw-bold fs-6">
                            <?php foreach (session('errors') as $error): ?>
                                <i class="fa-solid fa-circle-info me-2"></i><?= esc($error) ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('dokter/stase/store') ?>" method="POST">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="doctor_id" value="<?= session()->get('doctor_id'); ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Stase</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
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
                        <a href="<?= base_url('dokter/stase') ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>