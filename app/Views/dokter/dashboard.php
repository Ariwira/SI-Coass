<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid py-4">
    <h5 class="mb-4">Selamat Datang Dokter <?= $doctor['name'] ?></h5>
    <div class="card bg-gradient-info p-4">
        <div class="d-flex align-items-center">
            <div class="">
                <?php if (!empty($doctor['photo'])): ?>
                    <img src="<?= esc(base_url('uploads/photos/' . $doctor['photo'])) ?>" class="avatar" style="object-fit: cover; width: 80px; height: 80px;" alt="Foto docto$doctor">
                <?php else: ?>
                    <img src="<?= esc(base_url('assets/img/default-avatar.jpg')) ?>" class="avatar" style="object-fit: cover; width: 80px; height: 80px;" alt="Foto Default">
                <?php endif; ?>
            </div>
            <div class="ms-4">
                <h6 class="text-white fw-bolder mb-0"><?= $doctor['name'] ?></h6>
                <p class="text-white text-sm"><?= $doctor['id_card'] ?></p>
            </div>
        </div>
        <div class="mt-4">
            <div class="text-white">
                <div class="d-flex">
                    <p class="w-25 mb-1">Alamat</p>
                    <span>: <?= esc($doctor['address'] ?? '-'); ?></span>
                </div>
                <div class="d-flex">
                    <p class="w-25 mb-1">Kabupaten</p>
                    <span>: <?= esc($doctor['state'] ?? '-'); ?></span>
                </div>
                <div class="d-flex">
                    <p class="w-25 mb-1">Jenis Kelamin</p>
                    <span>: <?= esc($doctor['gender'] ?? '-'); ?></span>
                </div>
                <div class="d-flex mb-1">
                    <p class="w-25">Nomor Telepon</p>
                    <span>: <?= esc($doctor['mobile_no'] ?? '-'); ?></span>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>