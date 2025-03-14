<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <!-- Main Content Card with Info Gradient -->
    <div class="card bg-gradient-info shadow-lg border-0 mb-4">
        <!-- Header -->
        <div class="card-header bg-transparent border-0 pb-0">
            <h4 class="text-white mb-0">
                <i class="fas fa-user-graduate me-2"></i>
                Profile Mahasiswa Coass
            </h4>
        </div>
        
        <div class="card-body text-white">
            <div class="row">
                <!-- Profile Column -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="text-center">
                    <div class="avatar-section mb-3">
                            <?php if (!empty($mahasiswa['assets/img/default-avatar.jpg'])): ?>
                            <img src="<?= base_url($mahasiswa['photo']) ?>" alt="Profile Photo" class="rounded-circle" width="150">
                             <?php else: ?>
                            <img src="<?= base_url('assets/img/default-avatar.jpg') ?>" alt="Default Avatar" class="rounded-circle" width="150">
                            <?php endif; ?>
                            <h4 class="mb-1 mt-3 fw-bold text-white">
                                <?= esc($mahasiswa['name'] ?? 'Nama Mahasiswa') ?>
                            </h4>
                            <span class="badge bg-white text-info fw-bold">
                                <?= esc($mahasiswa['nim'] ?? 'NIM Belum Terdaftar') ?>
                            </span>
                        </div>
                        
                        <div class="contact-info mt-4">
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-sm bg-gradient-success">
                                    <i class="fas fa-edit me-2 font-weight 500"></i>Edit Profil
                                </a>
                            </div>
                        </div>
                        <div class="contact-info ">
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-sm bg-gradient-primary">
                                    <i class="fas fa-edit me-2 font-weight 500"></i>Tambah Logbook
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Column -->
                <div class="col-lg-8">
                    <div class="row g-4">
                        <!-- Personal Data -->
                        <div class="col-md-6">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-id-card text-white p-2 rounded-circle me-2"></i>
                                Data Pribadi
                            </h6>
                            <div>
                                <p class="mb-1">Tempat Tanggal Lahir</p>
                                <p class="fw-bold mb-3">
                                    <?= esc(($mahasiswa['place_of_birth'] ?? '-') . ', ' . ($mahasiswa['date_of_birth'] ?? '-')) ?>
                                </p>
                                <p class="mb-1">Jenis Kelamin</p>
                                <p class="fw-bold mb-3">
                                    <?= esc($mahasiswa['gender'] ?? '-') ?>
                                </p> 
                            </div>
                        </div>

                        <!-- Academic Data -->
                        <div class="col-md-6">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-university text-white p-2 rounded-circle me-2"></i>
                                Data Akademik
                            </h6>
                            <div>
                                <p class="mb-1">Universitas</p>
                                <p class="fw-bold mb-3">
                                    <?= esc($mahasiswa['university'] ?? '-') ?>
                                </p>
                                <p class="mb-1">Tahun Angkatan</p>
                                <p class="fw-bold mb-3">
                                    <?= esc($mahasiswa['year'] ?? '-') ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal light opacity-4">

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-address-book text-white p-2 rounded-circle me-2"></i>
                                Kontak
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1">Nomor HP</p>
                                    <p class="fw-bold">
                                        <?= esc($mahasiswa['mobile_no'] ?? '-') ?>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1">Alamat</p>
                                    <p class="fw-bold">
                                        <?= esc($mahasiswa['address'] ?? '-') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
