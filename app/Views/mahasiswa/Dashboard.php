<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header p-3 bg-gradient-primary">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-user-graduate me-2"></i>
                        Profile Mahasiswa Coass
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Profile Column -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="avatar-section">
                        <?php if (!empty($mahasiswa['assets/img/default-avatar.jpg'])): ?>
                            <img src="<?= base_url($mahasiswa['photo']) ?>" 
                                 class="rounded-circle mb-3" 
                                 alt="Foto Profil"
                                 style="width: 150px; height: 150px; object-fit: cover">
                        <?php else: ?>
                            <div class="avatar-placeholder rounded-circle mb-3">
                                <i class="fas fa-user-circle fa-5x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                        <h4 class="mb-1"><?= esc($mahasiswa['name'] ?? 'Nama Mahasiswa') ?></h4>
                        <p class="text-muted mb-3"><?= esc($mahasiswa['nim'] ?? 'NIM Belum Terdaftar') ?></p>
                    </div>
                    
                    <div class="contact-info">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="row">
                        <!-- Personal Data -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-id-card me-2"></i>Data Pribadi
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <strong>Tempat Tanggal Lahir</strong><br>
                                    <?= esc(($mahasiswa['place_of_birth'] ?? '-') . ', ' . ($mahasiswa['date_of_birth'] ?? '-')) ?>
                                </li>
                                <li class="mb-3">
                                    <strong>Jenis Kelamin</strong><br>
                                    <?= esc($mahasiswa['gender'] ?? '-') ?>
                                </li>
                                <li class="mb-3">
                                    <strong>Agama</strong><br>
                                    <?= esc($mahasiswa['religion'] ?? '-') ?>
                                </li>
                            </ul>
                        </div>

                        <!-- Academic Data -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-university me-2"></i>Data Akademik
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <strong>Universitas</strong><br>
                                    <?= esc($mahasiswa['university'] ?? '-') ?>
                                </li>
                                <li class="mb-3">
                                    <strong>Tahun Angkatan</strong><br>
                                    <?= esc($mahasiswa['year'] ?? '-') ?>
                                </li>
                                <li class="mb-3">
                                    <strong>Email</strong><br>
                                    <?= esc($mahasiswa['email'] ?? '-') ?>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr class="horizontal dark">

                    <!-- Contact Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-address-book me-2"></i>Kontak
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nomor HP</strong><br>
                                    <?= esc($mahasiswa['mobile_no'] ?? '-') ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Alamat</strong><br>
                                    <?= esc($mahasiswa['address'] ?? '-') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-gradient-success shadow-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="text-white mb-0">Total Mahasiswa Coass Aktif</h5>
                            <h1 class="text-white mb-0"><?= $total_coass ?? 0 ?></h1>
                            <span class="text-white text-sm">Terdaftar dalam sistem</span>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-users fa-3x text-white opacity-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
